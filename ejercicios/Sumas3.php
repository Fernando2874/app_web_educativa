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
    $nivel_dificultad = isset($_GET['nivel']) ? (int)$_GET['nivel'] : 3;
    $mensaje = "";
    $respuesta_usuario = isset($_POST['respuesta_usuario']) ? (int)$_POST['respuesta_usuario']: null; //se obtiene la respuesta del usuario
    $respuesta_correcta_anterior = isset($_POST['resultado_correcto_oculto']) ? (int)$_POST['resultado_correcto_oculto'] : null;

    if ($respuesta_usuario !== null && $respuesta_correcta_anterior !== null){
        if ($respuesta_usuario === $respuesta_correcta_anterior ){
            $mensaje = "<p>El resultado es correcto ✅</p>";
        }else{
            $mensaje = "<p>El resultado es incorrecto ❌. El resultado correcto era: $respuesta_correcta_anterior</p>";
        }
    }
    $sql = "SELECT id_suma, id_nivel, sumando_1, sumando_2, resultado_correcto 
        FROM problemassuma 
        WHERE id_nivel = ? 
        ORDER BY RAND() 
        LIMIT 1";
    $stmt = $conexion->prepare($sql); //previene inyecciones SQL
    $stmt->bind_param("i",$nivel_dificultad);
    $stmt-> execute();
    $resultado = $stmt->get_result();

    $problema = null;
    $sumando_1 = 0;
    $sumando_2 = 0;
    $resultado_correcto_actual = 0;
    if ($resultado->num_rows > 0) {
        $problema = $resultado->fetch_assoc();
        $sumando_1 = $problema['sumando_1'];
        $sumando_2 = $problema['sumando_2'];
        $resultado_correcto_actual = $problema['resultado_correcto']; //nuevo problema
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
            <h1>Problema de Suma Nivel <?php echo htmlspecialchars($nivel_dificultad); ?> ➕ <?php
                    echo $mensaje;
                ?></h1>
        </header>

        <section class="info-leccion">
            <h2>Resuelve:</h2>
            <div class="ejemplo">
                <p style="font-size: 3em; text-align: center;">
                    María tenía <?php echo htmlspecialchars($sumando_1)?> galletas 🍪 en una bandeja. Su hermano
                    Juan,
                    horneó
                    <?php echo htmlspecialchars($sumando_2)?> galletas más y
                    las
                    agregó a
                    la bandeja </p>
                <p style="font-size: 3em; text-align: center;">
                    Cuántas galletas tiene María en total ahora?
                </p><label>
                </label>
                <input type="text" value="<?php echo $sumando_1; ?>" />

                <label>Segundo Sumando</label>
                <input type="text" value="<?php echo $sumando_2; ?>" />

                <form method="POST">
                    <input type="hidden" name="resultado_correcto_oculto"
                        value="<?php echo htmlspecialchars($resultado_correcto_actual); ?>" />
                    <label>Tu Respuesta</label>
                    <input type="text" name="respuesta_usuario" />
                    <button type="submit"> Verificar </button>
                </form>
                <a href="Sumas.html" style="text-decoration: none; margin-top: 50px; display: block;">Volver a la
                    Lección
                </a>
            </div>
        </section>
    </div>
</body>

</html>