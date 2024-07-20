new Vue({
    el: '#app',
    data: {
        cookie: 'en',
        loginData: {
            username: null,
            password: null,
            errors: []
        },
        notification: {
            show: false,
            message: ''
        },
        showPassword:false
    },
    mounted() {
        this.$data.cookie = this.getCookie('lang');
    },
    methods: {
        sendLogin: async function () {
            let url = '/auth/login'
            let formData = new FormData();
            if (this.$data.loginData.username == null || typeof this.$data.loginData.username == 'undefined') {
                this.$data.loginData.errors = 'Please '
            }
            formData.append('username', this.$data.loginData.username)
            formData.append('password', this.$data.loginData.password)

            let response = await this.postRequest(url)
            console.log(response.data)
        },
        postRequest: async function (url, data) {
            try {
                return await axios.post(url, data)
            } catch (error) {
                console.log(error)
            }
        },
        changePassword: function () {
            this.data.showPassword = !this.data.showPassword;
        },
        getCookie: function (cvalue) {
            let cookieArr = document.cookie.split("; ");

            for(let i = 0; i < cookieArr.length; i++) {
                let cookiePair = cookieArr[i].split("=");

                if (cvalue === cookiePair[0]) {
                    return decodeURIComponent(cookiePair[1]);
                }
            }

            return null;
        }
    },
});