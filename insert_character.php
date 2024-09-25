<?php
session_start();

// Verificar si el usuario ha iniciado sesión como admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "root1234";
$dbname = "comparador_personajes_anime";
$port = "3360";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recoger los datos del formulario
$nombre = $_POST['nombre'];
$anime = $_POST['anime'];
$poder = $_POST['poder'];
$habilidad = $_POST['habilidad'];
$nivel_de_poder = $_POST['nivel_de_poder'];
$imagen = "";

// Procesar la imagen
if (!empty($_FILES['imagen']['name'])) {
    $imagen_nombre = $_FILES['imagen']['name'];
    $imagen_temp = $_FILES['imagen']['tmp_name'];
    $imagen_destino = "imgs_subida/" . $imagen_nombre;
    move_uploaded_file($imagen_temp, $imagen_destino);
    $imagen = $imagen_destino;
} elseif (!empty($_POST['imagen_link'])) {
    $imagen = $_POST['imagen_link'];
} else {
    // Manejar caso en el que no se proporciona ni archivo ni enlace
    $imagen = ""; // O asignar una imagen predeterminada si lo prefieres
}


// Insertar los datos en la base de datos
$sql_insert_personaje = "INSERT INTO characters (nombre, anime, poder, habilidad, nivel_de_poder, imagen) 
                        VALUES ('$nombre', '$anime', '$poder', '$habilidad', '$nivel_de_poder', '$imagen')";

if ($conn->query($sql_insert_personaje) === TRUE) {
    echo "Personaje añadido correctamente.";
    header("Location: index.php"); // Redireccionar a la galería de imágenes
} else {
    echo "Error al añadir personaje: " . $conn->error;
}

$conn->close();
?>
