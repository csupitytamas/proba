new Vue({
    el: '#app',
    data: {
        cookie: null,
        showLabel: false,
        showForm: false,
        selectedOption: '',
        newRow: {
            name: '',
            number: '',
            length: ''
        },
        selectedRow: null,
        searchableParameters: {
            length: null,
        },
        poles: [],
        wings: [],
        selectedCategory: '',
        inputValue: '',
        selectedLength: ''
    },
    mounted() {
        this.$data.cookie = this.getCookie();
        this.getData();
    },
    watch: {
        selectedCategory(value) {
            if (value === 'name') {
                this.selectedLength = '';
            } else if (value === 'number') {
                this.selectedLength = '';
            } else if (value === 'length') {
                this.inputValue = '';
            }
        }
    },
    methods: {
        getData: async function () {
            let url = '/storage/on-field';
            let response = await this.getRequest(url)
            console.log(response.data)
            this.$data.poles = response.data.poles
            this.$data.wings = response.data.wings
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
        },
    }

});
