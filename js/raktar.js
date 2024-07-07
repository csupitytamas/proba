new Vue({
    el: '#app',
    data: {
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
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData: async function () {
            let url = '/storage/on-field';
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
        toggleForm() {
            this.showForm = this.selectedOption === 'kitoro' || this.selectedOption === 'rudak';
        },
        addRow() {
            let table;
            let newRow = document.createElement('tr');
            newRow.addEventListener('click', this.selectRow);
            if (this.selectedOption === 'kitoro') {
                table = document.querySelector('.wings-table tbody');
                newRow.innerHTML = `<td>${this.newRow.name}</td><td>${this.newRow.number}</td><td><img src="img/kep1.jpg" alt="Példa kép" style="max-width: 200px; max-height: 200px;"></td>`;
            } else if (this.selectedOption === 'rudak') {
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
        selectRow(event) {
            if (this.selectedRow) {
                this.selectedRow.classList.remove('selected');
            }
            this.selectedRow = event.currentTarget;
            this.selectedRow.classList.add('selected');
        },
        deleteRow(event) {
            event.stopPropagation();
            const row = event.target.closest('tr');
            row.remove();
        },
        deleteSelectedRow() {
            if (this.selectedRow) {
                if (confirm('Delete?')) {
                    this.selectedRow.remove();
                    this.selectedRow = null;
                }
            } else {
                alert('Select to delete.');
            }

        },
        moveToWarehouse() {
            if (this.selectedRow) {
                if (confirm('Move to the Storage?')) {
                    this.selectedRow.remove();
                    this.selectedRow = null;
                }
            } else {
                alert('Select to move');
            }
        },
        refresh: function () {
            // TODO url-nek le kell kérned js-el az aktualis url-t es hozzafuzni a hossz parametert
            // TODO JS-el ellenőrizni, hogy az url-ben található-e kérdőjel ha igen akkor "&hossz=" ha nem akkor pedig "?hossz="
            let url = window.location.href;
            if (url.includes('?')) {
                param = "&hossz=";
                if (this.data.searchableParameters.length !== null)
                    window.location = url + param + this.data.searchableParameters.length;
            }
            else {
                param ="?hossz="
                if (this.data.searchableParameters.length !== null)
                    window.location = url + param + this.data.searchableParameters.length;
            }
        }
    }
});