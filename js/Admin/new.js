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
        },
        selectedFile: null
    },
    methods: {
        onFileChange(event) {
            this.selectedFile = event.target.file;
        },
        postRequest: async function (url, data) {
            try {
                const options = {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                };

                return await axios.post(url, data, options)
            } catch (error) {
                this.$data.error.show = true
            }
        },
        sendForm: async function () {
            let url = '';
            if (this.$data.selected.type === 'wing') {
                url = '/wings/new-wing';
            } else {
                url = '/poles/new-pole';
            }
            let formData = new FormData();
            Object.entries(this.$data.form).forEach(([key, value]) => {
                if (this.$data.selected.type === 'wing') {
                    if (key !== 'hossz') {
                        formData.append(key, value);
                    }
                } else {
                    formData.append(key, value);
                }
            })

            if (this.selectedFile) {
                formData.append('file', this.selectedFile);
            }

            this.$data.error.show = false
            let response = await this.postRequest(url, formData)
            if (response.data.status == 'success') {
                location.href = '/admin'
            }
        }
    }
});