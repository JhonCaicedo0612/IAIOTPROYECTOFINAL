<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo '
            <script>
                alert("Debe iniciar sesion primero");
                window.location = "../login.php";
            </script>
        ';

    session_destroy();
    die();
}
if ($_SESSION['tipo'] == 2) {
    echo '
            <script>
                alert("No tienes los privilegios para esta pagina");
                window.location = "bienvenidoUser.php";
            </script>
        ';
    die();
}
$url = "http://127.0.0.1:5000/consultarNodo";
$curl = curl_init($url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$respuesta_api = curl_exec($curl);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Consultas</title>
    <link rel="stylesheet" href="../CSS/estilospagin.css">
</head>

<body>
    <div class="contenedor">
        <div class="barra">
            <a href="../php/cerrar.php">
                <button>logout</button>
            </a>
            <a href="bienvenidoAdmin.php">
                <button>Inicio</button>
            </a>
            <a href="usuarios.php">
                <button>Usuarios</button>
            </a>
            <a href="nodos.php">
                <button>Nodos</button>
            </a>
            <a href="datosAdmin.php">
                <button>Consultas</button>
            </a>
            <button> <?php
                        echo 'USUARIO ' . $_SESSION['usuario'];
                        ?>
            </button>
        </div>
        <div class="consulta">
            <h1>Consulta tus Nodos</h1>
            <form method="post" action="">
                <label for="texto">Nodos</label>
                <select name="Nodos">
                    <option value=""></option>
                    <?php
                    $opciones = json_decode($respuesta_api, true);
                    if (!empty($opciones)) {
                        foreach ($opciones as $opcion) {
                            echo "<option value='" . $opcion['idnodo'] . "'>" . $opcion['nombre'] . "</option>";
                        }
                        echo '</select>';
                    }
                    ?>
                    <input type="submit" name="Consultar" value="Consultar">
            </form>
            <br>
            
            <table border="1">
                <tr>
                    <th>idnodo</th>
                    <th>ac_x</th>
                    <th>ac_y</th>
                    <th>ac_z</th>
                    <th>rot_x</th>
                    <th>rot_y</th>
                    <th>rot_z</th>
                    <th>Ejercicio</th>
                    <th>fecha</th>
                </tr>
                <?php
                if (isset($_POST['Consultar'])) {
                    $texto = $_POST["Nodos"];
                    $usuario = $_SESSION['usuario'];
                    $_SESSION['nodo'] = $texto;

                    // Consulta SQL para obtener los datos
                    if ($texto != "") {
                        $url2 = "http://127.0.0.1:5000/datosidenodoultimo?idnodo=" . $texto;
                        $curl2 = curl_init($url2);
                        curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
                        $respuesta_api2 = curl_exec($curl2);


                        $datos = json_decode($respuesta_api2, true);


                        if (!empty($datos)) {
                            foreach ($datos as $dato) {
                                echo '<tr>';
                                echo '<td>' . $dato['idnodo'] . '</td>';
                                echo '<td>' . $dato['accx'] . '</td>';
                                echo '<td>' . $dato['accy'] . '</td>';
                                echo '<td>' . $dato['accz'] . '</td>';
                                echo '<td>' . $dato['rotx'] . '</td>';
                                echo '<td>' . $dato['roty'] . '</td>';
                                echo '<td>' . $dato['rotz'] . '</td>';
                                echo '<td>' . $dato['pred'] . '</td>';
                                echo '<td>' . $dato['fecha'] . '</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td> Graficas </td>';
                                echo '<td> <a href="grafica.php?tipo=acx&nodo=' . $texto . '"> <button>Ver grafica</button> </td>';
                                echo '<td> <a href="grafica.php?tipo=acy&nodo=' . $texto . '"> <button>Ver grafica</button> </td>';
                                echo '<td> <a href="grafica.php?tipo=acz&nodo=' . $texto . '"> <button>Ver grafica</button> </td>';
                                echo '<td> <a href="grafica.php?tipo=rotx&nodo=' . $texto . '"> <button>Ver grafica</button> </td>';
                                echo '<td> <a href="grafica.php?tipo=roty&nodo=' . $texto . '"> <button>Ver grafica</button> </td>';
                                echo '<td> <a href="grafica.php?tipo=rotz&nodo=' . $texto . '"> <button>Ver grafica</button> </td>';
                                echo '<td>  </td>';
                                echo '<td>  </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo "No se encontraron resultados.";
                        }
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>

</html>