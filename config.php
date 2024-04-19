<?php
// Configuración de la base de datos
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'usuario');
define('DB_PASSWORD', 'contraseña');
define('DB_NAME', 'nombre_base_de_datos');

// Intentar establecer la conexión a la base de datos
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexión
if($link === false){
    die("Error: No se pudo conectar. " . mysqli_connect_error());
}
?>