new Vue({
    el: '#app',
    data: {
        showForm: false,
        selectedButton: '',
        selectedOption: '',
        searchableParameters: {
            length: null,
        },
        poles: [],
        wings: [],
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData: async function () {
            let url = 'Nem tudom';
            let response = await this.getRequest(url)
            console.log(response.data)
            this.$data.poles = response.data.poles
            this.$data.wings = response.data.wings
        },
        getRequest: async function (url) {
            try {
                return await axios.get(url)
            } catch (error) {
                console.log(error)
            }
        },

    }
});