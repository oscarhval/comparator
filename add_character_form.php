<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Personaje</title>
    <link rel="stylesheet" href="styleform.css">
</head>
<body>
<img src="./gif/añadir.gif">
    <form id="addCharacterForm" action="insert_character.php" method="post" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="anime">Anime:</label><br>
        <input type="text" id="anime" name="anime" required><br>
        <label for="poder">Poder:</label><br>
        <input type="text" id="poder" name="poder" required><br>
        <label for="habilidad">Habilidad:</label><br>
        <input type="text" id="habilidad" name="habilidad" required><br>
        <label for="nivel_de_poder">Nivel de Poder:</label><br>
        <input type="text" id="nivel_de_poder" name="nivel_de_poder" required><br>
        <label for="imagen">Imagen (seleccionar archivo o enlace de Pinterest):</label><br>
        <input type="file" id="imagen" name="imagen"><br>
        <input type="text" id="imagen_link" name="imagen_link" placeholder="Enlace de Pinterest"><br><br>
        <input type="submit" value="Agregar Personaje">

        
    </form>
    <a id="volver" href="index.php"><button>Volver</button></a>

    <script>
        document.getElementById('addCharacterForm').addEventListener('submit', function(event) {
            // Obtener valor de los campos de imagen
            var imageFile = document.getElementById('imagen').files[0];
            var imageUrl = document.getElementById('imagen_link').value;

            // Validar si se proporciona al menos una imagen
            if (!imageFile && !imageUrl) {
                alert('Por favor, selecciona una imagen o proporciona un enlace de Pinterest.');
                event.preventDefault(); // Evitar que se envíe el formulario
            }
        });
    </script>

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


