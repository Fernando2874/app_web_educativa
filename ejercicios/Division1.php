<?php
    $servidor = "localhost";
    $usuario = "root";
    $password = "";
    $base_datos = "lecciones";

    $conexion = new mysqli($servidor,$usuario,$password,$base_datos);

    if($conexion->connect_error){
        die("error en la conexion: " . $conexion->connect_error);
    }
    
    //se guarda el nivel de dificultad de los ejercicios
    //isset sirve para verificar que una variable este definida
    $nivel_dificultad = isset($_GET['nivel']) ? (int)$_GET['nivel'] : 1;
    $mensaje = "";
    $respuesta_usuario_cociente = isset($_POST['respuesta_usuario_cociente']) ? (int)$_POST['respuesta_usuario_cociente']: null; //se obtiene la respuesta del usuario
    $respuesta_usuario_residuo = isset($_POST['respuesta_usuario_residuo']) ? (int)$_POST['respuesta_usuario_residuo']: null; //se obtiene la respuesta del usuario
    $respuesta_cociente_anterior = isset($_POST['resultado_cociente_oculto']) ? (int)$_POST['resultado_cociente_oculto'] : null; //se obtiene la respuesta del formulario
    $respuesta_residuo_anterior = isset($_POST['resultado_residuo_oculto']) ? (int)$_POST['resultado_residuo_oculto']: null; 
    
    if ($respuesta_usuario_cociente !== null && $respuesta_cociente_anterior !== null){
        if ($respuesta_usuario_residuo !== null && $respuesta_residuo_anterior !== null){
            if ($respuesta_usuario_cociente === $respuesta_cociente_anterior && $respuesta_usuario_residuo === $respuesta_residuo_anterior){
                $mensaje = "<p>El resultado es correcto ✅</p>";
            }else{
                $mensaje = "<p>El resultado es incorrecto ❌. El resultado correcto era: $respuesta_correcta_anterior</p>";
            }
        }
    }
    $sql = "SELECT id_div, id_nivel, dividendo, divisor, resultado_cociente, resultado_residuo 
        FROM problemasdivision
        WHERE id_nivel = ? 
        ORDER BY RAND() 
        LIMIT 1";
    $stmt = $conexion->prepare($sql); //previene inyecciones SQL
    $stmt->bind_param("i",$nivel_dificultad);
    $stmt-> execute();
    $resultado = $stmt->get_result();

    $problema = null;
    $dividendo = 0;
    $divisor = 0;
    $resultado_cociente_actual = 0;
    $resultado_residuo_actual = 0;
    if ($resultado->num_rows > 0) {
        $problema = $resultado->fetch_assoc();
        $dividendo = $problema['dividendo'];
        $divisor = $problema['divisor'];
        $resultado_cociente_actual = $problema['resultado_cociente']; //nuevo problema
        $resultado_residuo_actual = $problema['resultado_residuo']; 
    }
// Si no se encontró un problema y no se acaba de verificar una respuesta (para evitar sobrescribir el mensaje Correcto/Incorrecto)
    if ($problema === null && $respuesta_usuario === null) {
        $mensaje = "<p>⚠️ No se encontraron problemas para el Nivel " . htmlspecialchars($nivel_dificultad) . "</p>";
    }
    $stmt->close();
    $conexion->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../estilo.css" />
</head>

<body>
    <div class=" main-content">
        <header class="header">
            <h1>Problema de Multiplicacion Nivel <?php echo htmlspecialchars($nivel_dificultad); ?> ✖️ <?php
                    echo $mensaje;
                ?></h1>
        </header>

        <section class="info-leccion">
            <h2>Resuelve:</h2>
            <div class="ejemplo">
                <p style="font-size: 3em; text-align: center;">
                    Manolo tiene <?php echo htmlspecialchars($dividendo)?> mufins y quiere repartirlos entre sus
                    <?php echo htmlspecialchars($divisor)?> hijos.</p>
                <p style="font-size: 3em; text-align: center;">
                    ¿Cuántos mufins le toca a cada hijo?
                </p><label>
                    <label>Dividendo</label>
                    <input type="text" value="<?php echo $dividendo; ?>" />

                    <label>Divisor</label>
                    <input type="text" value=" <?php echo $divisor; ?>" />

                    <form method="POST">
                        <input type="hidden" name="resultado_cociente_oculto"
                            value="<?php echo htmlspecialchars($resultado_cociente_actual); ?>" />
                        <input type="hidden" name="resultado_residuo_oculto"
                            value="<?php echo htmlspecialchars($resultado_residuo_actual); ?>" />
                        <label>Tu Respuesta</label>
                        <label>Resultado Cociente</label>
                        <input type="text" name="respuesta_usuario_cociente" />
                        <label>Resultado Residuo</label>
                        <input type="text" name="respuesta_usuario_residuo" />
                        <button type="submit"> Verificar </button>
                    </form>
                    <a href="Division2.php" style="text-decoration: none; margin-top: 50px; display: block;">Dificultad
                        Media
                    </a>
            </div>
        </section>
    </div>
</body>

</html>