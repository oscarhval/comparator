<?php
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

// Función para obtener valores únicos de una columna de la base de datos
function obtener_valores_unicos($conn, $columna) {
    $sql = "SELECT DISTINCT $columna FROM characters";
    $result = $conn->query($sql);
    $valores = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $valores[] = $row[$columna];
        }
    }
    return $valores;
}

// Obtener valores únicos de poder y anime
$valores_poder = obtener_valores_unicos($conn, 'poder');
$valores_anime = obtener_valores_unicos($conn, 'anime');

// Lógica para aplicar los filtros de poder y anime
$where_clause = "";
if (isset($_GET['filtro_poder']) && !empty($_GET['filtro_poder'])) {
    $filtro_poder = $_GET['filtro_poder'];
    $where_clause .= " poder = '$filtro_poder' AND";
}

if (isset($_GET['filtro_anime']) && !empty($_GET['filtro_anime'])) {
    $filtro_anime = $_GET['filtro_anime'];
    $where_clause .= " anime = '$filtro_anime' AND";
}

// Eliminar el último "AND" si existe
if (!empty($where_clause)) {
    $where_clause = rtrim($where_clause, "AND");
}

// Consulta SQL para obtener los personajes según los filtros y ordenarlos
$sql_personajes_filtrados = "SELECT DISTINCT nombre, imagen FROM characters";
if (!empty($where_clause)) {
    $sql_personajes_filtrados .= " WHERE $where_clause";
}
$sql_personajes_filtrados .= " ORDER BY nombre ASC"; // Ordenar personajes por nombre alfabéticamente

$result_personajes_filtrados = $conn->query($sql_personajes_filtrados);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Personajes</title>
    <link rel="stylesheet" href="stylesmain.css"> <!-- Enlace al archivo CSS -->
</head>
<body>
    
<header>
    <img src="./gif/galeria.gif">
    <a href="login.php" class="login-btn">Iniciar Sesión</a>
    <div class="compare-button">
        <a href="login.php"><button>Comparar</button></a>
    </div>
</header>
</header>

<!-- Filtros por poder y anime -->
<div class="filters">
    <form method="get">
        <label for="filtro_poder">Filtrar por poder:</label>
        <select name="filtro_poder" id="filtro_poder">
            <option value="">Todos los poderes</option>
            <?php
            foreach ($valores_poder as $poder) {
                echo "<option value='$poder'>$poder</option>";
            }
            ?>
        </select>

        <label for="filtro_anime">Filtrar por anime:</label>
        <select name="filtro_anime" id="filtro_anime">
            <option value="">Todos los animes</option>
            <?php
            foreach ($valores_anime as $anime) {
                echo "<option value='$anime'>$anime</option>";
            }
            ?>
        </select>

        <input type="submit" value="Filtrar">
    </form>
</div>

<!-- Galería de imágenes de personajes -->
<div class="gallery">
    <?php
    if ($result_personajes_filtrados->num_rows > 0) {
        while ($row = $result_personajes_filtrados->fetch_assoc()) {
            echo "<div class='gallery-item'>";
            echo "<img src='" . $row['imagen'] . "' alt='" . $row['nombre'] . "'>";
            echo "<p>" . $row['nombre'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "No hay personajes disponibles.";
    }
    ?>
</div>

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
