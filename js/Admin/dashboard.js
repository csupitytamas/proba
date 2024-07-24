new Vue({
    el: '#app',
    data: {
        showNew: false,
        showEdit: false,
        selectedOption: '',
        selectedRow: null,
        searchableParameters: {
            length: null,
        },
        poles: null,
        wings: null
    },

    mounted() {
        this.getWingsData();
        this.getPolesData();
    },
    methods: {
        getWingsData: async function () {
            let url = '/wings/get-wings';
            let response = await this.getRequest(url)
            this.$data.wings = response.data
            console.log(this.wings)
        },
        getPolesData: async function () {
            let url = '/poles/get-poles';
            let response = await this.getRequest(url)
            this.$data.poles = response.data
        },
        getRequest: async function (url) {
            try {
                return await axios.get(url)
            } catch (error) {
                console.log(error)
            }
        },
        calculateWingUrl: function (id, edit = true) {
            if (edit) {
                return '/wings/edit-wing?id=' + id
            }
            return '/wings/delete-wing?id=' + id
        },
        calculatePoleUrl: function (id, edit = true) {
            if (edit) {
                return '/poles/edit-pole?id=' + id
            }
            return '/poles/delete-pole?id=' + id
        }
    }
});