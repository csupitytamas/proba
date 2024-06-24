<?php
try {
    include_once('Mysql.php');
    $mysql = new Mysql();
    $hossz = null;
    if (isset($_GET['hossz'])) {
        $hossz = $_GET['hossz'];
    }
    $sqlKitoro = "SELECT `neve`, `db`, `kep` FROM kitoro";
    $queryKitoro = $mysql->query($sqlKitoro);

    $sqlRudak = "SELECT `neve`, `db`, `hossz`, `kep` FROM rudak";
    if(!is_null($hossz)) {
        $sqlRudak = "SELECT `neve`, `db`, `hossz`, `kep` FROM rudak WHERE hossz = '{$hossz}'";
    }
    $queryRudak = $mysql->query($sqlRudak);

} catch (Exception $exception) {
    die( $exception->getMessage() );
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storage</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
</head>
<body>
<h1>Storage</h1>
<div id="app">
    <div class="table-container">
        <table class="tablak wings-table">
            <thead>
            <tr>
                <th colspan="4" style="font-weight: bold">WINGS</th>
            </tr>
            <tr>
                <th>Name</th>
                <th>Number of Items</th>
                <th>Picture</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($queryKitoro as $kitoro) {
                $result = "<tr @click=\"selectRow()\">";
                foreach ( $kitoro as $key => $value) {
                    $result .= "<td>" . $value . "</td>";
                }
                $result .= "</tr>";
                echo $result;
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="table-container">
        <table class="tablak poles-table">
            <thead>
            <tr>
                <th colspan="5" style="font-weight: bold">POLES</th>
            </tr>
            <tr>
                <th>Name</th>
                <th>Number of Items</th>
                <th>Length</th>
                <th>Picture</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($queryRudak as $rudak) {
                $result = "<tr @click=\"selectRow()\">";
                foreach ( $rudak as $key => $value) {
                    $result .= "<td>" . $value . "</td>";
                }
                $result .= "</tr>";
                echo $result;
            }
            ?>
            </tbody>
        </table>
    </div>
    <div>
        <select class="addRowTo" id="addRowTo" v-model="selectedOption" @change="toggleForm">
            <option value="kitörők">WINGS</option>
            <option value="rudak">POLES</option>
        </select>
    </div>
    <div id="formContainer" v-show="showForm">
        <form @submit.prevent="addRow">
            <label for="name">Name:</label>
            <input type="search" id="name" v-model="newRow.name"> <br>
            <label for="darabszam">Number:</label>
            <input type="number" id="darabszam" v-model="newRow.number" min="1"> <br>
            <div v-if="selectedOption === 'rudak'">
                <label for="length">Length:</label>
                <select id="length" v-model="newRow.length">
                    <option value="3.5">3.5 meter</option>
                    <option value="3">3 meter</option>
                    <option value="2.5">2.5 meter</option>
                    <option value="2">2 meter</option>
                </select> <br>
            </div>
            <button class="button" type="submit">OK</button>
        </form>
    </div>
    <div>
        <button class="button" @click="moveTo('main')">Main</button>
        <button class="button" @click="moveTo('respect')">Respect</button>
        <button class="button" @click="moveTo('farriers')">Farriers</button>
        <button class="buttondel" @click="deleteSelectedRow">Delete</button>
    </div>
    <script>
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
                }
            },
            methods: {
                toggleForm() {
                    this.showForm = this.selectedOption === 'kitörők' || this.selectedOption === 'rudak';
                },
                addRow() {
                    let table;
                    let newRow = document.createElement('tr');
                    newRow.addEventListener('click', this.selectRow);
                    if (this.selectedOption === 'kitörők') {
                        if(this.newRow.number % 2 !== 0){
                            alert('The number must be even for WINGS.');
                            this.showLabel = false;
                            return;
                        }
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
                }
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
                }
                refresh: function () {
                    // TODO url-nek le kell kerned js-el az aktualis url-t es hozzafuzni a hossz parametert
                    let url =
                        // TODO JS-el ellenőrizni, hogy az url-ben található-e kérdőjel ha igen akkor "&hossz=" ha nem akkor pedig "?hossz="
                        let param = "?hossz="
                    if ()
                    {
                        param = "&hossz="
                    }
                    window.location = url + param + this.data.searchableParameters.length
                }
            }
            search() {
                let searchInput = document.getElementById('searchInput').value.toLowerCase();
                let rows = document.querySelectorAll('.tablak tbody tr');

                rows.forEach(row => {
                    let found = false;
                    row.querySelectorAll('td').forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchInput)) {
                            found = true;
                        }
                    });

                    if (found) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

        });
    </script>
</body>
</html>