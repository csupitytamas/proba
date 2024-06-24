<?php
session_start();
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"]) && isset($_POST["password"])){
        $email = $_POST["email"];
        $passwd = $_POST["password"];
    }
}

if (file_exists('users.json')) {
    $users = json_decode(file_get_contents('users.json'), true);
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>ShowJumpEditor</title>
    <link rel="stylesheet" href="css/mainpage.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
</head>
<body>
<h1>Samorin Show Jumps</h1>
<header>
    <p>User</p>
        <template id="modal-template">
            <div class="modal" v-if="showModal">
                <div class="modal-content">
                    <form method="post">
                        <label>Username: <input type="email" name="email" required></label><br><br>
                        <label>Password: <input type="password" name="password" required></label><br><br>
                        <input @click="openModal" type="submit" value="Login">
                    </form>
                </div>
            </div>
         </div>
     </template>
</header>
<button onclick="location.href='main.php'">Main</button>
<button onclick="location.href='respect.php'">Respect</button>
<button onclick="location.href='farriers.php'">Farriers</button>
<button onclick="location.href='raktar.php'">Storage</button>
<script>
    new Vue({
        el: 'header',
        data() {
            return {
                showModal: false
            };
        },
        methods: {
            openModal() {
                this.showModal = true;
            },
            closeModal() {
                this.showModal = false;
            }
        }
    });
</script>
</body>
</html>