new Vue({
    el: '#app',
    data: {
        cookie: null,
        form: {
            username: "",
            password: ""
        },
        notification: {
            show: false,
            message: ''
        },
        showPassword:false
    },
    mounted() {
        this.$data.cookie = this.getCookie();
    },
    methods:{
       login: function () {
            let response = this.getRequest('/auth/login')
            if (response.data.status == 'success') {
                window.location = '/';
            }
        }
    },
    changePassword: function () {
        this.data.showPassword = !this.data.showPassword;
    },
    getRequest: async function (url) {
        try {
            return await axios.get(url)
        } catch (error) {
            console.log(error)
            this.$data.notification.show = true;
            this.$data.notification.message = error.data.message
        }
    },
    getCookie: function () {
        let cookieArr = document.cookie.split("; ");

        for (let i = 0; i < cookieArr.length; i++) {
            let cookiePair = cookieArr[i].split("=");

            if ('lang' === cookiePair[0]) {
                return decodeURIComponent(cookiePair[1]);
            }
        }

        return null;
    },
});