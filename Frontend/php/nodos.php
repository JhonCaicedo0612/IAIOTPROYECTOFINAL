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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <a href="datosAdmin.php">
                <button>Consultas</button>
            </a>
            <a href="ultimosdatosAdmin.php">
                <button>Ultimos datos</button>
            </a>
            <button> <?php
                        echo 'USUARIO ' . $_SESSION['usuario'];
                        ?>
            </button>
        </div>
        <div class="consulta">
            <br>
            <br>
            <h1>Consultar Nodos</h1>
            <br>
            <br>
            <center>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">idnodo</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">ubicacion</th>
                            <th scope="col">User</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Modificar</th>
                            <th scope="col">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $servurl = "http://127.0.0.1:5000/consultarNodo";
                        $curl = curl_init($servurl);

                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($curl);

                        if ($response === false) {
                            curl_close($curl);
                            die("Error en la conexion");
                        }

                        curl_close($curl);
                        $resp = json_decode($response);
                        $long = count($resp);
                        for ($i = 0; $i < $long; $i++) {
                            $dec = $resp[$i];
                            $idnodo = $dec->idnodo;
                            $nombre = $dec->nombre;
                            $ubicacion = $dec->ubicacion;
                            $user = $dec->user;
                            $estado = $dec->estado;
                        ?>

                            <tr>
                                <form action="modificarNodo.php" method=post>
                                    <td><?php echo $idnodo; ?><input type="hidden" name="idnodo" value="<?php echo $idnodo; ?>"></td>
                                    <td><input type="text" name="nombre" value="<?php echo $nombre; ?>"></td>
                                    <td><input type="text" name="ubicacion" value="<?php echo $ubicacion; ?>"></td>
                                    <td><input type="text" name="user" value="<?php echo $user; ?>"></td>
                                    <td><input type="text" name="estado" value="<?php echo $estado; ?>"></td>
                                    <td><input type="submit" value="MODIFICAR"></td>
                                </form>
                                <form action="eliminarNodo.php" method=post>
                                    <td>
                                        <input type="hidden" name="idnodo" value="<?php echo $idnodo; ?>">
                                        <input type="submit" value="ELIMINAR">
                                </form>
                                </td>

                            </tr>


                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </center>


            <center>
                <br>
                <br>
                <br>
                <h1>Crear Usuarios</h1>
                <br>
                <br>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">idnodo</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">ubicacion</th>
                            <th scope="col">User</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Crear</th>

                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <form action="Crearnodo.php" method=post>
                                <td><input type="text" name="idnodo"></td>
                                <td><input type="text" name="nombre"></td>
                                <td><input type="text" name="ubicacion"></td>
                                <td><input type="text" name="user"></td>
                                <td><input type="text" name="estado"></td>
                                <td><input type="submit" value="CREAR"></td>
                            </form>
                        </tr>



                    </tbody>
                </table>
            </center>
            <br>
            <br>



        </div>
    </div>
</body>
</body>

</html>