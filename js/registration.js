new Vue({
    el: '#app',
    data: {
        notification: {
            show: false,
            message: ''
        }
    },
    mounted() {
        this.registration();
    },
    methods: {
        registration: function () {
            let response = this.getRequest('/auth/registration')
            if (response.data.status == 'success') {
                this.$data.notification.show = true;
                this.$data.notification.message = response.data.message
            }
        },
        getRequest: async function (url) {
            try {
                return await axios.get(url)
            } catch (error) {
                console.log(error)
            }
        }
    }
});