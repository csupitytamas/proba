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
            kep: null
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
            if (this.selected.type === 'wing') {
                url = '/wings/get-wing?id=' + this.selected.id
            } else {
                url = '/poles/get-pole?id=' + this.selected.id
            }

            let response = await this.getRequest(url)
            this.form.name_hu = response.data.name_hu;
            this.form.name_en = response.data.name_en;
            this.form.db = response.data.db;
            this.form.hossz = response.data.hossz;
            this.form.kep = response.data.kep;
        },
        getUrlParameters: function () {
            let params = new URLSearchParams(window.location.search);

            let paramsObj = Array.from(params.entries()).reduce((obj, [key, value]) => ({...obj, [key]: value}), {});

            if (typeof  paramsObj.type  == 'undefined' || typeof  paramsObj.id  == 'undefined') {
                location.href = '/admin'
            }
            this.selected.type = paramsObj.type;
            this.selected.id = paramsObj.id;
        },
        getRequest: async function (url) {
            try {
                return await axios.get(url)
            } catch (error) {
                this.error.show = true
                console.log(error)
            }
        },
        postRequest: async function (url, data) {
            try {
                const options = {
                    headers: {'Content-Type': 'multipart/form-data'}
                };

                return await axios.post(url, data, options)
            } catch (error) {
                this.error.show = true
                console.log(error)
            }
        },
        sendForm: async function (event) {
            try {
                let url = '';
                if (this.selected.type === 'wing') {
                    url = '/wings/update-wing';
                } else {
                    url = '/poles/update-pole';
                }
                let formData = new FormData();
                formData.append('id', this.selected.id);
                Object.entries(this.form).forEach(([key, value]) => {
                    if (this.selected.type === 'wing') {
                        if (key !== 'hossz') {
                            formData.append(key, value);
                        }
                    } else {
                        formData.append(key, value);
                    }
                })
                const fileInput = event.target.closest('div').querySelector('input[type="file"]');
                if (fileInput && fileInput.files.length > 0) {
                    formData.append('kep', fileInput.files[0]);
                }
                this.error.show = false
                let response = await this.postRequest(url, formData)
                if (response.data.status == 'success') {
                    location.href = '/admin'
                }
            } catch (error) {
                console.log(error)
                this.error.show = true;
            }
        }
    }
});