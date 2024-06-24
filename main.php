<?php
try {
    include_once('Mysql.php');
    $mysql = new Mysql();
    $hossz = null;
    if (isset($_GET['hossz'])) {
        $hossz = $_GET['hossz'];
    }
    $sqlPalyak = "SELECT `id` FROM palyak";
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
    <title>Main Arena</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
</head>

<body>
<h1>MAIN ARENA</h1>
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
            <tr @click="selectRow()">
                <td v-for="pole in this.$data.poles.data">{{ pole }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div>
        <select class="addRowTo"  v-model="selectedOption" @change="toggleForm">
            <option value="kitoro">WINGS</option>
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
                <select id="length" v-model="newRow.length" @change="">
                    <option value="3.5">3,5 meter</option>
                    <option value="3">3 meter</option>
                    <option value="2.5">2,5 meter</option>
                    <option value="2">2 meter</option>
                </select> <br>
            </div>
            <button class="button" type="submit">OK</button>
        </form>
    </div>
    <div>
        <button class="button" @click="moveToWarehouse">Storage</button>
        <button class="buttondel" @click="deleteSelectedRow">Delete</button>
    </div>
</div>
<script src="js/app.js"></script>
</body>
</html>