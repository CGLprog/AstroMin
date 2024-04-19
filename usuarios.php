<?php
session_start();
 
// Verificar si el usuario ya ha iniciado sesión, en cuyo caso redirigirlo a la página principal
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Incluir el archivo de configuración
require_once "config.php";
 
// Definir variables e inicializar con valores vacíos
$email = $password = "";
$email_err = $password_err = "";
 
// Procesar datos del formulario cuando se envía el formulario
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validar correo electrónico
    if(empty(trim($_POST["email"]))){
        $email_err = "Por favor, ingresa tu correo electrónico.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Validar contraseña
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor, ingresa tu contraseña.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Verificar credenciales
    if(empty($email_err) && empty($password_err)){
        // Preparar una declaración de selección
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Vincular variables a la declaración preparada como parámetros
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Establecer parámetros
            $param_email = $email;
            
            // Intentar ejecutar la declaración preparada
            if(mysqli_stmt_execute($stmt)){
                // Almacenar resultado
                mysqli_stmt_store_result($stmt);
                
                // Verificar si el correo electrónico existe, si es así, verificar la contraseña
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Vincular variables de resultado
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // La contraseña es correcta, así que inicia una nueva sesión
                            session_start();
                            
                            // Almacenar datos en variables de sesión
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;                            
                            
                            // Redirigir al usuario a la página principal
                            header("location: index.php");
                        } else{
                            // Mostrar un mensaje de error si la contraseña no es válida
                            $password_err = "La contraseña que has ingresado no es válida.";
                        }
                    }
                } else{
                    // Mostrar un mensaje de error si el correo electrónico no existe
                    $email_err = "No existe ninguna cuenta con ese correo electrónico.";
                }
            } else{
                echo "Oops! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            }

            // Cerrar declaración
            mysqli_stmt_close($stmt);
        }
    }
    
    // Cerrar conexión
    mysqli_close($link);
}
?>