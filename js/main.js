new Vue({
    el: '#app',
    data: {
        cookie: null,
        showLabel: false,
        form: {
            show: false,
            wingForm: {
                show: false
            },
            poleForm: {
                show: false
            }
        },
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
    },
    mounted() {
        this.$data.cookie = this.getCookie();
        this.getData();
        this.getAvailableWings();
        this.getAvailablePoles();
    },
    methods: {
        getData: async function () {
            let url = '/main/on-field';
            let response = await this.getRequest(url)
            console.log(response.data)
            this.$data.polesOnField = response.data.poles
            this.$data.wingsOnField = response.data.wings
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
        switchLanguage: async function () {
            let url = '/switch-lang?lang=' + this.$data.selectedLang;
            let response = await this.getRequest(url)
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
            this.form.show = true;
            if (this.selectedOption === 'wing') {
                this.form.poleForm.show = false;
                this.form.wingForm.show = true;
            }
            if (this.selectedOption === 'role') {
                this.form.wingForm.show = false;
                this.form.poleForm.show = true;
            }
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
        },
        setWingMaxPieces: function (pieces) {
            this.addToField.wing.max = parseInt(pieces)
        },
        setPoleMaxPieces: function (pieces) {
            this.addToField.pole.max = parseInt(pieces)
        },
        checkWingMaxPieces: function () {
            if (this.addToField.wing.counter > this.addToField.wing.max) {
                this.addToField.wing.counter = this.addToField.wing.max;
            }
            if (this.addToField.wing.counter <= 0) {
                this.addToField.wing.counter = this.addToField.wing.min;
            }
        },
        checkPoleMaxPieces: function () {
            if (this.addToField.pole.counter > this.addToField.pole.max) {
                this.addToField.pole.counter = this.addToField.pole.max;
            }
            if (this.addToField.pole.counter <= 0) {
                this.addToField.pole.counter = this.addToField.pole.min;
            }
        }
    }
});