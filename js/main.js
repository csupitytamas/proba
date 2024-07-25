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
        removeFromField: {
            wings: [],
            poles: [],
        },
    },
    mounted() {
        this.$data.cookie = this.getCookie();
        this.getData();
        this.getAvailableWingsAndPoles();
    },
    methods: {
        getData: async function () {
            let url = '/main/on-field';
            let response = await this.getRequest(url)
            this.$data.wingsOnField = response.data.wings
            this.$data.polesOnField = response.data.poles
        },
        getAvailableWingsAndPoles: async function () {
            let url = '/storage/on-field?without=true';
            let response = await this.getRequest(url);
            this.$data.availableWings = response.data.wings
            this.$data.availablePoles = response.data.poles
        },
        getSelectedWings: function (remove = false) {
            let wingsTable = document.getElementById('wings-table')
            let selectedWingsCells = Array.from(wingsTable.querySelectorAll('.selected'));
            selectedWingsCells.forEach((selectedChild) => {
                if (remove) {
                    selectedChild.classList.remove('selected')
                }
                else {
                    this.$data.removeFromField.wings.push(selectedChild.getAttribute('value'))
                }
            });
        },
        getSelectedPoles: function (remove = false) {
            let polesTable = document.getElementById('poles-table')
            let selectedPolesCells = Array.from(polesTable.querySelectorAll('.selected'));

            selectedPolesCells.forEach((selectedChild) => {
                if (remove) {
                    selectedChild.classList.remove('selected')
                }
                else {
                    this.$data.removeFromField.poles.push(selectedChild.getAttribute('value'))
                }
            });
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
        postRequest: async function (url, data) {
            try {
                const options = {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                };

                return await axios.post(url, data, options)
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
        selectRow(event) {
            if (event.currentTarget.classList.contains('selected')) {
                event.currentTarget.classList.remove('selected');
            } else {
                event.currentTarget.classList.add('selected');
            }
        },
        deleteSelectedRows: async function () {
            this.$data.removeFromField.wings = []
            this.$data.removeFromField.poles = []
            this.getSelectedWings();
            this.getSelectedPoles();
            if (this.$data.removeFromField.wings.length !== 0) {
                let url = '/main/delete-wing'
                let data = {}
                data['wings'] = this.$data.removeFromField.wings
                let response = await this.postRequest(url, data)
                if (response.data.status == 'success') {
                    await this.reloadData();
                }
            }
            if (this.$data.removeFromField.poles.length !== 0) {
                let url = '/main/delete-pole'
                let data = {}
                data['poles'] = this.$data.removeFromField.poles
                let response = await this.postRequest(url, data)
                if (response.data.status == 'success') {
                    await this.reloadData();
                }
            }
        },
        moveToWarehouse: async function () {
            try {
                let data = {}
                let url = ''
                if (this.selectedOption === 'wing') {
                    data['id'] = this.addToField.wing.type
                    data['db'] = this.addToField.wing.counter
                    url = '/main/new-wing'
                } else {
                    data['id'] = this.addToField.pole.type
                    data['db'] = this.addToField.pole.counter
                    url = '/main/new-pole'
                }

                let response = await this.postRequest(url, data)
                if (response.data.status == 'success') {
                    await this.reloadData()
                }
            } catch (error) {
                console.log(error)
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
        },
        reloadData: async function () {
            await this.getData();
            this.getSelectedWings(true);
            this.getSelectedPoles(true);
            await this.getAvailableWingsAndPoles()
            this.resetSelection()
            this.hideForms()
        },
        resetSelection: function() {
            this.selectedOption = '';
            this.addToField.wing.type = '';
            this.addToField.pole.type = '';
        },
        hideForms: function () {
            this.form.show = false;
            this.form.wingForm.show = false;
            this.form.poleForm.show = false;
        }
    }
});