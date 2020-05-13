import axios from 'axios'

const Request = {
    server : "http://sapechat.ru/api/",
    getToken(user) {
        axios.post(this.server + "auth/login_check", user)
        .then(response => {
            /*this.$store.actions.changeSubmitStatusLogin('OK')
            console.log('Status: ', this.$store.getters.submitStatusLogin())*/
            console.log('response: ', response)
            this.$router.push('/')
        })
        .catch(error => {
            /*this.$store.actions.changeSubmitStatusLogin('ERROR')
            console.log('Status: ', this.$store.getters.submitStatusLogin())*/
            console.log('error: ', error)
        });
    }
};

export default Request