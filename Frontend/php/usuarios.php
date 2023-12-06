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
            <a href="nodos.php">
                <button>Nodos</button>
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
            <h1>Consultar Usuarios</h1>
            <center>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Password</th>
                            <th scope="col">tipo</th>
                            <th scope="col">Editar</th>
                            <th scope="col">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $servurl = "http://localhost:1880/usuario";
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
                            $nombre = $dec->nombre;
                            $usuario = $dec->user;
                            $password = $dec->password;
                            $tipo = $dec->tipo;
                        ?>

                            <tr>
                                <form action="modificarUsuario.php" method=post>
                                    <td><input type="text" name="nombre" value="<?php echo $nombre; ?>"></td>
                                    <td><?php echo $usuario; ?><input type="hidden" name="usuario" value="<?php echo $usuario; ?>"></td>
                                    <td><input type="text" name="password" value="<?php echo $password; ?>"></td>
                                    <td><input type="text" name="tipo" value="<?php echo $tipo; ?>"></td>
                                    <td><input type="submit" value="MODIFICAR"></td>
                                </form>
                                <form action="eliminarUsuario.php" method=post>
                                    <td>
                                        <input type="hidden" name="usuario" value="<?php echo $usuario; ?>">
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
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Password</th>
                            <th scope="col">tipo</th>
                            <th scope="col">Crear</th>

                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <form action="CrearUser.php" method=post>
                                <td><input type="text" name="nombre"></td>
                                <td><input type="text" name="usuario"></td>
                                <td><input type="text" name="password"></td>
                                <td><input type="text" name="tipo"></td>
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