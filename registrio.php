<?php
include 'conexion.php';

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $nombre = $_POST['usuario'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $password_cifrada = password_hash($password, PASSWORD_DEFAULT); //contraseña cifrada

    // Preparar la consulta SQL
    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss",$nombre,$email,$password_cifrada);
    
    if($stmt->execute()) { //mensaje de registro exitoso o erroneo
        echo "<h3> Registro exitoso</h3>";
        echo "<a href='login.html'>Iniciar sesión</a>";
    }else{
        echo "<h3> Error: " . $stmt->error . "<h3>";
    }
    
    $stmt->close();
    $conexion->close();

    
}
?>