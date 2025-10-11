<?php
// // Capturar los datos enviados por el formulario 
// $usuario=$_POST['usuario']; 
// $password=$_POST['password'];
// $email=$_POST['email']; 

// // Mostrar los datos recibidos 

// echo "Usuario: " . htmlspecialchars($usuario) . "<br>" ;
// echo "Contraseña: " . htmlspecialchars($password) . "<br>" ;
// echo "email: " . htmlspecialchars($email);

    $servidor = "localhost";
    $usuario = "root";
    $password = "";
    $base_datos = "registro";

    $conexion = new mysqli($servidor,$usuario,$password,$base_datos);

    if($conexion->connect_error){
        die("error en la conexion: " . $conexion->connect_error);
    }else{
        echo "Conexión exitosa a la base de datos '$base_datos'";
    }
?>