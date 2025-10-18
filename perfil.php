<?php
session_start(); // Inicia la sesión

// Verificar si hay sesión activa
if(!isset($_SESSION['usuario_nombre']) || !isset($_SESSION['usuario_email'])){
    echo "No hay sesión iniciada.";
    exit;
}

// Guardar el nombre en una variable para mostrar en el HTML
$nombre_usuario = $_SESSION['usuario_nombre'];
$email_usuario = $_SESSION['usuario_email'] ?? '';
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perfil</title>
    <link rel="stylesheet" href="estilo.css" />
</head>

<body>
    <div class="layout">
        <div class="sidebar" id="sidebar">
            <button class="toggle-btn" id="toggle-btn">☰</button>
            <ul>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="inicio.html">Inicio</a></li>
            </ul>
        </div>

        <div class="profile-pic">
            <img src="assets/perfil.png" alt="Foto de perfil" />
        </div>

        <div class="perfil">
            <h2><?php echo htmlspecialchars($nombre_usuario); ?></h2>
            <h2><?php echo htmlspecialchars($email_usuario); ?></h2>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>