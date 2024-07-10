new Vue({
    el: '#app',
    data: {
        languageSelector: {
            show: false,
            selectedLanguage: 'en',
            selectedLanguageEn: true
        },
        search: {
            searchFreeText: '',
            searchType: [],
            searchExtraParams: []
        }
    },
    methods: {
        changeLanguage: async function () {
            this.$data.languageSelector.selectedLanguage = this.$data.languageSelector.selectedLanguageEn ? 'en' : 'hu'
            let url = '/switch-lang?lang=' + this.$data.languageSelector.selectedLanguage;
            let response = await this.getRequest(url)

            if (response.data.status === 'success') {
                this.$data.languageSelector.selectedLanguage = response.data.selected_lang
            } else {
                console.log('Valami hiba van')
            }
        },
        getRequest: async function (url) {
            try {
                return await axios.get(url)
            } catch (error) {
                console.log(error)
            }
        },
        toggleForm() {
            this.showForm = this.selectedOption === 'kitoro' || this.selectedOption === 'rudak';
        }
    }
});