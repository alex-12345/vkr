import axios from 'axios'

const Request = {
    server : "http://sapechat.ru/api/",
    postUser() {
        /*axios({
            method: 'get',
            url: this.server + "auth/login_check/",
            data: {
                "username":"johndoe@mail.com",
                "password":"rbrbvjhf"
            },
            headers: {
                'Content-Type': 'application/json'
            }
        })        
        .then(response => {
            console.log('response: ', response)
            this.$router.push('/')
        })
        .catch(error => {
            console.log('error: ', error)
        });*/
        axios.get(this.server + 'auth/login_check/', { "username":"johndoe@mail.com", "password":"rbrbvjhf" })
        .then(response => {
            console.log(response.data);
        });
    }
};

export default Request