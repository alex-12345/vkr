Vagrant.configure("2") do |config|

	config.env.enable
	config.vm.box = "ubuntu/xenial64"

	config.vm.network "forwarded_port", guest: 8010, host: 8010, protocol: "tcp"
	config.vm.network "forwarded_port", guest: 8012, host: 8012, protocol: "tcp"

	config.vm.synced_folder ".", "/vagrant", disabled: true
	config.vm.synced_folder "./file_storage", "/var/www/corporate_chat/file_storage"
	config.vm.synced_folder "./main_api/var", "/var/www/corporate_chat/main_api/var", disabled: true
	config.vm.synced_folder "./main_api/vendor", "/var/www/corporate_chat/main_api/vendor", disabled: true
	config.vm.synced_folder "./main_api", "/var/www/corporate_chat/main_api"
	config.vm.synced_folder "./notification_server", "/var/www/corporate_chat/notification_server"
	config.vm.synced_folder "./tools", "/var/www/corporate_chat/tools"

	config.vm.provider "virtualbox" do |vb|
		vb.name = "CorporateChatVagrant"
		vb.gui = false
		vb.memory = "1024"
		vb.cpus = 1
	end

	config.vm.provision "shell", inline: <<-SHELL
		export APP_URL=#{ENV['APP_URL']}
		export APP_SECRET=#{ENV['APP_SECRET']}
		export DATABASE_URL=#{ENV['DATABASE_URL']}
		export MAILER_DSN=#{ENV['MAILER_DSN']}
		export NOTIFICATION_SENDER_EMAIL=#{ENV['NOTIFICATION_SENDER_EMAIL']}	
		
		if ! [ -d /etc/php/7.4 ]; then
			echo "PHP installation"
			sudo apt-get install software-properties-common > /dev/null
			sudo add-apt-repository ppa:ondrej/php > /dev/null
			sudo apt-get update > /dev/null
			sudo apt-get install php7.4 php-cli php7.4-fpm php7.4-xml php7.4-mbstring php7.4-mysql zip unzip php7.4-zip php7.4-curl -y> /dev/null
		else
			echo "PHP already installed!"
		fi
		
		if ! [ -f /usr/bin/composer ]; then
			echo "Composer installation"
			php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');"
			#TODO add checksum checking
			php /tmp/composer-setup.php
			sudo mv composer.phar /usr/bin/composer
			php -r "unlink('/tmp/composer-setup.php');"
		else 
			echo "Composer already installed!"
		fi
		
		if ! [ -f /usr/sbin/nginx ]; then
			echo "Nginx installation"
			sudo apt-add-repository ppa:nginx/stable > /dev/null
			sudo apt update > /dev/null
			sudo apt install nginx -y > /dev/null
		else 
			echo "Nginx already installed!"
		fi
		if ! [ -f /usr/bin/mysql ]; then
			echo "Mysql installation"
			sudo debconf-set-selections <<< 'mysql-server-5.7 mysql-server/root_password password rbrbvjhf' >> /dev/null
			sudo debconf-set-selections <<< 'mysql-server-5.7 mysql-server/root_password_again password rbrbvjhf' >> /dev/null
			sudo apt-get install mysql-server-5.7 -y >> /dev/null
			sudo service mysql start >> /dev/null
		else 
			echo "Mysql already installed!"
		fi
		
		echo "CREATE DATABASE IF NOT EXISTS corporate_chat" | sudo mysql -u root -prbrbvjhf &>> /dev/null
		if ! [ -f /var/www/corporate_chat/main_api/vendor/composer/autoload_classmap.php ];then
			cd /var/www/corporate_chat/main_api
			composer install --no-dev --optimize-autoloader &> /dev/null
		fi
		
		if ! [ -f /var/www/corporate_chat/notification_server/vendor/composer/autoload_classmap.php ];then
			cd /var/www/corporate_chat/notification_server
			composer install --no-dev --optimize-autoloader &> /dev/null
		fi
		
		if ! [ -f /var/www/corporate_chat/main_api/public/swagger/swagger.yaml ];then
			cd /var/www/corporate_chat/main_api/public
			mkdir swagger &> /dev/null
			wget -q https://github.com/swagger-api/swagger-ui/archive/master.zip >> /dev/null
			unzip master.zip "swagger-ui-master/dist/*" -d . >> /dev/null
			mv swagger-ui-master/dist/* swagger >> /dev/null
			rm master.zip -R swagger-ui-master
			sed -i "s#https://petstore.swagger.io/v2/swagger.json#swagger.yaml#g" swagger/index.html
			cd ../
			vendor/bin/openapi --format yaml --output public/swagger/swagger.yaml src swagger/swagger.php >> /dev/null
		fi
		
		cd /var/www/corporate_chat
		mkdir -p main_api/config/jwt
		mkdir -p notification_server/jwt
		if ! [ -f /var/www/corporate_chat/main_api/config/jwt/private.pem ];then
			openssl genpkey -out main_api/config/jwt/private.pem -aes256 -pass pass:2024 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 >> /dev/null
			openssl pkey -in main_api/config/jwt/private.pem -passin pass:2024 -out main_api/config/jwt/public.pem -pubout >> /dev/null
			cp main_api/config/jwt/public.pem notification_server/jwt/public.pem
		fi
		
		cd /var/www/corporate_chat/main_api
		php bin/console doctrine:schema:update --force >> /dev/null
		
		cd /var/www/corporate_chat
		echo "APP_URL=http://${APP_URL}:8010
APP_ENV=prod
APP_SECRET=${APP_SECRET}
DATABASE_URL=${DATABASE_URL}
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=2024
MAILER_DSN=${MAILER_DSN}
NOTIFICATION_SENDER_EMAIL=${NOTIFICATION_SENDER_EMAIL}
NOTIFICATION_SERVER=http://localhost:8011
		" > main_api/.env
		echo "HTTP_SERVER=http://0.0.0.0:8011
WS_SERVER=websocket://0.0.0.0:8012
APP_SECRET=${APP_SECRET}
PUBLIC_KEY=jwt/public.pem
		" > notification_server/.env
		sudo cp tools/vagrant/nginx.conf /etc/nginx/sites-available/corporate_chat
		sudo ln -s /etc/nginx/sites-available/corporate_chat /etc/nginx/sites-enabled/corporate_chat &> /dev/null
		sudo service nginx restart
		cd /var/www/corporate_chat/notification_server
		sudo php index.php start -d > /dev/null
	  SHELL
end
