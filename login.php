<?php
session_start();

class Authenticator {
    private $conn;

    // Constructor
    public function __construct($servername, $username, $password, $dbname, $port) {
        // Crear conexión a la base de datos
        $this->conn = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    // Método para verificar las credenciales del usuario
    public function authenticate($username, $password) {
        // Consulta SQL para obtener la contraseña almacenada del usuario
        $sql = "SELECT contrasena FROM usuarios WHERE usuario='$username'";
        $result = $this->conn->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['contrasena'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                return true;
            }
        }
        return false;
    }

    // Método para cerrar la conexión a la base de datos
    public function closeConnection() {
        $this->conn->close();
    }
}

// Crear una instancia de la clase Authenticator
$authenticator = new Authenticator("localhost", "root", "root1234", "registro", "3360");

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar las credenciales del usuario
    if ($authenticator->authenticate($_POST['username'], $_POST['password'])) {
        if ($_SESSION['username'] === 'admin') {
            header("Location: add_character_form.php");
        } else {
            header("Location: comparar.php");
        }
    } else {
        // Credenciales incorrectas
        $error_message = "Credenciales incorrectas. Inténtalo de nuevo.";
    }
}

// Cerrar la conexión a la base de datos
$authenticator->closeConnection();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="stylelogin.css">
</head>
<body>

<img class="titulo" src="./gif/login.gif">
<?php
// Mostrar mensaje de error, si existe
if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}
?>

<form action="login.php" method="post">
    <label for="username">Usuario:</label><br>
    <input type="text" id="username" name="username" required><br>
    <label for="password">Contraseña:</label><br>
    <input type="password" id="password" name="password" required><br><br>
    <input type="submit" value="Iniciar Sesión">
</form>
<a class="registro" href="register.php"><button>Regístrate</button></a>


<footer >
    <p>PROYECTO COMPARADOR <br><br> Guillermo Pando, Diego Maqueda, Óscar Hernández, Jorge García</p>

    <div class="logo">
    <a href="https://www.pinterest.es/">
    <img src="./gif/pinterest.png" ></a>
    <a href="https://www.crunchyroll.com/es-es/premium?utm_source=google&utm_medium=paid_cr&utm_campaign=CR-Paid_Google_Web_Conversion_Europe_(non-UK-IE)_ES_ALL_Trademark_SVOD&utm_term=crunchyroll&referrer=google_paid_cr_CR-Paid_Google_Web_Conversion_Europe_(non-UK-IE)_ES_ALL_Trademark_SVOD&gad_source=1&gclid=EAIaIQobChMIjqyvpuWMhgMVuqhoCR1_SwiPEAAYASAAEgIorfD_BwE">
    <img src="./gif/crunchyroll.png"></a>
    </div>
</a>

</footer>

</body>
</html>