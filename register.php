<?php
session_start();

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar con la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "root1234";
    $dbname = "registro";
    $port = "3360";

    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Recoger los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar si el usuario ya existe en la base de datos
    $sql_verificar_usuario = "SELECT id FROM usuarios WHERE usuario='$username'";
    $result = $conn->query($sql_verificar_usuario);
    if ($result->num_rows > 0) {
        $error_message = "El usuario ya está registrado. Por favor, elige otro nombre de usuario.";
    } else {
        // Insertar los datos del nuevo usuario en la base de datos
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_insert_usuario = "INSERT INTO usuarios (usuario, contrasena) VALUES ('$username', '$hashed_password')";
        if ($conn->query($sql_insert_usuario) === TRUE) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header("Location: comparar.php");
        } else {
            $error_message = "Error al registrar el usuario: " . $conn->error;
        }
    }

    $conn->close();
}
?>
<?php
// Procesar el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Aquí iría el código para procesar y almacenar los datos del usuario en la base de datos

    // Redirigir a la página de inicio de sesión después de registrarse
    header("Location: login.php");
    exit; // Asegúrate de que el script se detenga después de redirigir
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Regístrate</title>
    <link rel="stylesheet" href="styleregister.css">
</head>
<body>

<img class="titulo" src="./gif/registro.gif">

<?php
// Mostrar mensaje de error, si existe
if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}
?>

<form action="register.php" method="post">
    <label for="username">Usuario:</label><br>
    <input type="text" id="username" name="username" required><br>
    <label for="password">Contraseña:</label><br>
    <input type="password" id="password" name="password" required><br><br>
    <input type="submit" value="Registrarse">
</form>
<a class="volver" href="index.php"><button>Volver</button></a>




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