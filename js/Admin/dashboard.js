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
        calculateWingUrl: function (id) {
            return '/admin/edit?type=wing&id=' + id
        },
        calculatePoleUrl: function (id) {
            return '/admin/edit?type=pole&id=' + id
        },
        deleteItem: async function (id, wing = true) {
            let url = '';
            if (wing) {
                url = '/wings/delete-wing?id=' + id
            } else {
                url = '/poles/delete-pole?id=' + id
            }

            let response = await this.getRequest(url)
            if (response.data.status === 'success') {
                await this.getWingsData();
                await this.getPolesData();
            }
        }
    }
});