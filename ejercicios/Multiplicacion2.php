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
    $nivel_dificultad = isset($_GET['nivel']) ? (int)$_GET['nivel'] : 2;
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
    $sql = "SELECT id_multiplicacion, id_nivel, primerfactor, segundofactor, resultado_correcto 
        FROM problemasmultiplicacion 
        WHERE id_nivel = ? 
        ORDER BY RAND() 
        LIMIT 1";
    $stmt = $conexion->prepare($sql); //previene inyecciones SQL
    $stmt->bind_param("i",$nivel_dificultad);
    $stmt-> execute();
    $resultado = $stmt->get_result();

    $problema = null;
    $primerfactor = 0;
    $segundofactor = 0;
    $resultado_correcto_actual = 0;
    if ($resultado->num_rows > 0) {
        $problema = $resultado->fetch_assoc();
        $primerfactor = $problema['primerfactor'];
        $segundofactor = $problema['segundofactor'];
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
    <ul class="burbujas">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
    <div class="main-content">
        <header class="header">
            <h1>Problema de Multiplicación Nivel <?php echo htmlspecialchars($nivel_dificultad); ?> ✖️</h1>
        </header>

        <section class="info-leccion">
            <h2>Resuelve:</h2>
            <div class="ejemplo">
                <p style="font-size: 3em; text-align: center;">
                    Un cine tiene <?php echo htmlspecialchars($primerfactor)?> filas de asientos, si cada fila tiene
                    <?php echo htmlspecialchars($segundofactor)?> asientos. </p>
                <p style="font-size: 3em; text-align: center;">
                    ¿cuál es la capacidad total de asientos del cine?
                </p><label>
                    <label>Primer Factor</label>
                    <input type="text" value="<?php echo $primerfactor; ?>" />

                    <label>Segundo Factor</label>
                    <input type="text" value=" <?php echo $segundofactor; ?>" />

                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                        <input type="hidden" name="resultado_correcto_oculto"
                            value="<?php echo htmlspecialchars($resultado_correcto_actual); ?>" />
                        <label>Tu Respuesta</label>
                        <input type="text" name="respuesta_usuario" />
                        <button type="submit"> Verificar </button>
                        <?php echo $mensaje; ?>
                    </form><br><br>
                    <a href="Multiplicacion3.php" class="btn" style="margin-top: 50px;">Dificultad
                        Dificil
                    </a>
                    <a href="Multiplicacion.html" class="btn" style="margin-left: 800px">Volver a la
                        Leccion
                    </a>
            </div>
        </section>
    </div>
</body>

</html>