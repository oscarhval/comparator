<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

class Database {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $port;
    private $conn;

    // Constructor
    public function __construct($servername, $username, $password, $dbname, $port) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->port = $port;
    }

    // Método para conectar a la base de datos
    public function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname, $this->port);
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    // Método para desconectar de la base de datos
    public function disconnect() {
        $this->conn->close();
    }

    // Método para obtener todos los personajes de la base de datos para la lista desplegable
    public function obtenerTodosLosPersonajes() {
        $sql = "SELECT idpersonaje, nombre FROM characters";
        $result = $this->conn->query($sql);
        return $result;
    }

    // Método para obtener detalles de un personaje por su ID
    public function obtenerDetallesPersonajePorId($idpersonaje) {
        $sql = "SELECT * FROM characters WHERE idpersonaje = $idpersonaje";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}

// Crear una instancia de la clase Database
$database = new Database("localhost", "root", "root1234", "comparador_personajes_anime", "3360");

// Conectar a la base de datos
$database->connect();

// Obtener todos los personajes de la base de datos para la lista desplegable
$result_todos_los_personajes = $database->obtenerTodosLosPersonajes();

// Inicializar las variables $personaje1 y $personaje2
$personaje1 = null;
$personaje2 = null;

// Verificar si el formulario ha sido enviado y asignar los valores de los personajes seleccionados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $idpersonaje1 = $_POST['personaje1'];
    $idpersonaje2 = $_POST['personaje2'];

    // Obtener detalles del personaje 1
    $personaje1 = $database->obtenerDetallesPersonajePorId($idpersonaje1);

    // Obtener detalles del personaje 2
    $personaje2 = $database->obtenerDetallesPersonajePorId($idpersonaje2);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Comparador de Personajes</title>
    <link rel="stylesheet" href="stylescomparador.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>
<body>
    
<img class="titulo" src="./gif/comparador.gif">
<!-- Formulario con lista desplegable -->
<form method="post">
    <div id="personaje-container">
        <div id="personaje1">
            <h3>✦ Selecciona un personaje <br> para la izquierda:</h3>
            <br>
            <h2>Personaje 1:</h2>
            <label for="personaje1">    
            <select name="personaje1">
            </label>
                <?php
                if ($result_todos_los_personajes->num_rows > 0) {
                    while ($row = $result_todos_los_personajes->fetch_assoc()) {
                        $selected = ($personaje1 && $personaje1['idpersonaje'] == $row['idpersonaje']) ? "selected" : "";
                        echo "<option value='" . $row['idpersonaje'] . "' $selected>" . $row['nombre'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay personajes disponibles</option>";
                }
                ?>
            </select>
        </div>

        <div id="personaje2">
            <h3>✧ Selecciona un personaje <br> para la derecha:</h3>
            <br>
            <h2>Personaje 2:</h2>
            <label for="personaje2">    
            <select name="personaje2">
            </label>
                <?php
                // Rebobinar el puntero del resultado para recorrerlo nuevamente
                $result_todos_los_personajes->data_seek(0);
                if ($result_todos_los_personajes->num_rows > 0) {
                    while ($row = $result_todos_los_personajes->fetch_assoc()) {
                        $selected = ($personaje2 && $personaje2['idpersonaje'] == $row['idpersonaje']) ? "selected" : "";
                        echo "<option value='" . $row['idpersonaje'] . "' $selected>" . $row['nombre'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay personajes disponibles</option>";
                }
                ?>
            </select>
        </div>
    </div>
    
    <div id="btnComparar" style="width: 100%; text-align: center;">
        <input type="submit" name="submit" value="Comparar">
    </div>
    </form>
    <a id="volver" href="index.php"><button>Volver</button></a>

<div class="contenedor-tabla">
<!-- Mostrar detalles de los personajes seleccionados -->
<?php if ($personaje1 && $personaje2) : ?>
    <div class="tabla">
    <table>

    <div class="datos">
   
        <tr>
            <td>Detalles del Personaje 1</td><td>Detalles del Personaje 2</td>
            
        </tr>
        <tr>
            <td>Nombre: <?php echo $personaje1['nombre']; ?></td><td>Nombre: <?php echo $personaje2['nombre']; ?></td>

            
        <tr>
            <td>Anime: <?php echo $personaje1['anime']; ?></td><td>Anime: <?php echo $personaje2['anime']; ?></td>

          
        <tr>
            <td>Poder: <?php echo $personaje1['poder']; ?></td><td>Poder: <?php echo $personaje2['poder']; ?></td>

            
        </tr>
        <tr>
            <td>Habilidad: <?php echo $personaje1['habilidad']; ?></td><td>Habilidad: <?php echo $personaje2['habilidad']; ?></td>

            
        <tr>
            <td>Nivel de poder: <?php echo $personaje1['nivel_de_poder']; ?></td><td>Nivel de poder: <?php echo $personaje2['nivel_de_poder']; ?></td>

           
        </tr>
   
        <tr>
            <td colspan="2">
                <img src="<?php echo $personaje1['imagen']; ?>" alt="Imagen del personaje 1">                 <img src="<?php echo $personaje2['imagen']; ?>" alt="Imagen del personaje 2">

            </td>
        </tr>

        </div>
        <tr >
            <td class="grafica" colspan="2">
                <canvas id="comparacion-poder-chart" width="400" height="200"></canvas>
            </td>
        </tr>
        <tr>
            <td  class="grafica" colspan="2">
                <canvas id="comparacion-nivel-poder-chart" width="400" height="200"></canvas>
            </td>
        </tr>
    </table>

    
</div>
    
    <script>
        var ctxPoder = document.getElementById('comparacion-poder-chart').getContext('2d');

        var poderPersonaje1 = <?php echo $personaje1['poder']; ?>;
        var poderPersonaje2 = <?php echo $personaje2['poder']; ?>;

        var chartDataPoder = {
            labels: ['Poder'],
            datasets: [{
                label: '<?php echo $personaje1['nombre']; ?>',
                data: [poderPersonaje1],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }, {
                label: '<?php echo $personaje2['nombre']; ?>',
                data: [poderPersonaje2],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        var chartOptionsPoder = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        };

        var chartPoder = new Chart(ctxPoder, {
            type: 'bar',
            data: chartDataPoder,
            options: chartOptionsPoder
        });

        var ctxNivelPoder = document.getElementById('comparacion-nivel-poder-chart').getContext('2d');

        var nivelPoderPersonaje1 = <?php echo $personaje1['nivel_de_poder']; ?>;
        var nivelPoderPersonaje2 = <?php echo $personaje2['nivel_de_poder']; ?>;

        var chartDataNivelPoder = {
            labels: ['Nivel de Poder'],
            datasets: [{
                label: '<?php echo $personaje1['nombre']; ?>',
                data: [nivelPoderPersonaje1],
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }, {
                label: '<?php echo $personaje2['nombre']; ?>',
                data: [nivelPoderPersonaje2],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        var chartOptionsNivelPoder = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        };

        var chartNivelPoder = new Chart(ctxNivelPoder, {
            type: 'bar',
            data: chartDataNivelPoder,
            options: chartOptionsNivelPoder
        });
    </script>
<?php endif; ?>

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