new Vue({
    el: '#app',
    data: {
        selected: {
            type: 'wing',
            id: null,
        },
        form: {
            name_hu: null,
            name_en: null,
            db: null,
            hossz: null,
        },
        error: {
            show: false,
            message: 'Keresd a buzi fejlesztőket, hogy tudj miért rinyálni.'
        }
    },
    mounted() {
        this.getUrlParameters()
        this.getData()
    },
    methods: {
        getData: async function () {
            let url = '';
            if (this.$data.selected.type === 'wing') {
                url = '/wings/get-wing?id=' + this.$data.selected.id
            } else {
                url = '/poles/get-pole?id=' + this.$data.selected.id
            }

            let response = await this.getRequest(url)
            this.$data.form.name_hu = response.data.name_hu;
            this.$data.form.name_en = response.data.name_en;
            this.$data.form.db = response.data.db;
            this.$data.form.hossz = response.data.hossz;
        },
        getUrlParameters: function () {
            let params = new URLSearchParams(window.location.search);

            let paramsObj = Array.from(params.entries()).reduce((obj, [key, value]) => ({...obj, [key]: value}), {});

            if (typeof  paramsObj.type  == 'undefined' || typeof  paramsObj.id  == 'undefined') {
                location.href = '/admin'
            }
            this.$data.selected.type = paramsObj.type;
            this.$data.selected.id = paramsObj.id;
        },
        getRequest: async function (url) {
            try {
                return await axios.get(url)
            } catch (error) {
                this.$data.error.show = true
                console.log(error)
            }
        },
        postRequest: async function (url, data) {
            try {
                const options = {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                };

                return await axios.post(url, data, options)
            } catch (error) {
                this.$data.error.show = true
                console.log(error)
            }
        },
        sendForm: async function () {
            let url = '';
            if (this.$data.selected.type === 'wing') {
                url = '/wings/update-wing';
            } else {
                url = '/poles/update-pole';
            }
            let formData = new FormData();
            formData.append('id', this.$data.selected.id);
            Object.entries(this.$data.form).forEach(([key, value]) => {
                if (this.$data.selected.type === 'wing') {
                    if (key !== 'hossz') {
                        formData.append(key, value);
                    }
                } else {
                    formData.append(key, value);
                }
            })
            this.$data.error.show = false
            let response = await this.postRequest(url, formData)
            if (response.data.status == 'success') {
                location.href = '/admin'
            }
        }
    }
});