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
    methods: {
        async postRequest(url, data) {
            const options = {
                headers: {'Content-Type': 'multipart/form-data'}
            };
            return await axios.post(url, data, options);
        },
        async sendForm(event) {
            try {
                let url = '';
                if (this.selected.type === 'wing') {
                    url = '/wings/new-wing';
                } else {
                    url = '/poles/new-pole';
                }
                let formData = new FormData();
                Object.entries(this.form).forEach(([key, value]) => {
                    formData.append(key, value);
                });
                const fileInput = event.target.closest('div').querySelector('input[type="file"]');
                if (fileInput && fileInput.files.length > 0) {
                    formData.append('kep', fileInput.files[0]);
                }
                const response = await this.postRequest(url, formData);
                if(response) {
                    location.href = '/admin'
                }
            } catch (error) {
                this.error.show = true;
            }
        }
    }

});