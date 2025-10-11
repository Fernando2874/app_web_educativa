<?php
session_start();//maneja sesiones y permite saber quien esta logueado
include 'conexion.php';

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id,nombre,email,password FROM usuarios WHERE email = ?"; //busqueda
    $stmt = $conexion->prepare($sql); //previene inyecciones SQL
    $stmt->bind_param("s",$email);
    $stmt-> execute();
    $resultado = $stmt->get_result();
    
    if($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        
        // Verificar la contraseña
        if(password_verify($password, $usuario['password'])) {
            header("location: sidebars/inicio.html");
            // Login correcto
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            echo "<h3> Bienvenido, " . htmlspecialchars($usuario['nombre']) . "</h3>";
        } else {
            // Contraseña incorrecta
            echo "<h3>Contraseña incorrecta</h3>";
            echo "<a href='login.html'>Volver</a>";
        }
        } else {
            // Usuario no encontrado
            echo "<h3>No existe ninguna cuenta con ese correo</h3>";
            echo "<a href='signup.html'>Regístrate</a>";
        }
    $stmt->close();
    $conexion->close();
}
?>