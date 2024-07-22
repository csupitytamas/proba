new Vue({
    el: '#app',
    data: {
        showNew: false,
        showEdit: false,
        selectedOption: '',
    },
    selectedRow: null,
    searchableParameters: {
        length: null,
    },
    addToField: {
        pole: {
            type: null,
            counter: null,
            min: 1,
            max: 0,
        },
        wing: {
            type: null,
            counter: null,
            min: 2,
            max: 0
        }
    },
    polesOnField: [],
    wingsOnField: [],
    availableWings: [],
    availablePoles: [],
    removeFromField: {
        wings: [],
        poles: [],
    },

    mounted() {
        this.getData();
    },
    methods: {
        getData: async function () {
            let url = '/admin/on-field';
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
        getAvailableWings: async function () {
            let url = '/wings/get-wings';
            let response = await this.getRequest(url);
            this.$data.availableWings = response.data
        },
        getAvailablePoles: async function () {
            let url = '/poles/get-poles';
            let response = await this.getRequest(url);
            this.$data.availablePoles = response.data
        },
        selectRow(event) {
            if (event.currentTarget) {
                event.currentTarget.classList.remove('selected');
            }
            event.currentTarget.classList.add('selected');
        },
        addRow() {
            let table;
            let newRow = document.createElement('tr');
            newRow.addEventListener('click', this.selectRow);
            if (this.selectedOption === 'wings') {
                table = document.querySelector('.wings-table tbody');
                newRow.innerHTML = `<td>${this.newRow.name}</td><td>${this.newRow.number}</td><td><img src="img/kep1.jpg" alt="Példa kép" style="max-width: 200px; max-height: 200px;"></td>`;
            } else if (this.selectedOption === 'poles') {
                table = document.querySelector('.poles-table tbody');
                newRow.innerHTML = `<td>${this.newRow.name}</td><td>${this.newRow.number}</td><td>${this.newRow.length}</td><td><img src="img/kep1.jpg" alt="Példa kép" style="max-width: 200px; max-height: 200px;">`;
            }
            table.appendChild(newRow);


            this.showLabel = true;

            this.newRow = {
                name: '',
                number: '',
                length: ''
            };
        },

        deleteRow(event) {
            event.stopPropagation();
            const row = event.target.closest('tr');
            row.remove();
        },
        toggleNew() {
            this.showNew = !this.showNew;
        },
        toggleEdit() {
            this.showEdit = !this.showEdit;
        },

    }
});