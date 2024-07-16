new Vue({
    el: '#app',
    data: {
        cookie: 'en',
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
    mounted() {
        this.calculateSelectedLang();
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
        getCookie: function (cvalue) {
            let cookieArr = document.cookie.split("; ");

            for(let i = 0; i < cookieArr.length; i++) {
                let cookiePair = cookieArr[i].split("=");

                if (cvalue === cookiePair[0]) {
                    return decodeURIComponent(cookiePair[1]);
                }
            }

            return null;
        },
        calculateSelectedLang: function () {
            let cookieLang = this.getCookie('lang')
            if (this.$data.cookie !== cookieLang) {
                this.$data.cookie = cookieLang
            }
            if (this.$data.cookie === 'hu') {
                this.$data.languageSelector.selectedLanguageEn = false
            }
            else {
                this.$data.languageSelector.selectedLanguageEn = true
            }
        },
        toggleForm() {
            this.showForm = this.selectedOption === 'kitoro' || this.selectedOption === 'rudak';
        }
    },
});