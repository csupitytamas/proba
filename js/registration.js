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
        }
    },
    mounted() {
        this.$data.cookie = this.getCookie();
    },
    methods: {
        registration: function () {
            let response = this.getRequest('/auth/registration')
            if (response.data.status == 'success') {
                window.location = '/';
            }
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

            for(let i = 0; i < cookieArr.length; i++) {
                let cookiePair = cookieArr[i].split("=");

                if ('lang' === cookiePair[0]) {
                    return decodeURIComponent(cookiePair[1]);
                }
            }

            return null;
        }
    }
});