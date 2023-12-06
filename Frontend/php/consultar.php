<?php
    session_start();
    if(!isset($_SESSION['usuario'])){
        echo '
            <script>
                alert("Debe iniciar sesion primero");
                window.location = "../login.php";
            </script>
        ';

        session_destroy();
        die();
    }
    if($_SESSION['type'] == 1){
        echo '
            <script>
                alert("No tienes los privilegios para esta pagina");
                window.location = "bienvenidoAdmin.php";
            </script>
        ';
        die();
    } 

    $url = "http://127.0.0.1:5000/ConsultarNodoUser?user=".$_SESSION['usuario'];
    $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $respuesta_api = curl_exec($curl);
        $datos=json_decode($respuesta_api, true );
        $refidnodo = $datos['idnodo'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Página web Seguidor de Actividad Fisica</title>
    <link rel="shortcut icon" href="../assests/img/diseñodelcositodearriba.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&display=swap" rel="stylesheet"> 
</head>

<body>
    <header>
        <nav>
            <a class="nav-link" href="login.php">Pagina Principal </a>
            <a class="nav-link" href="login.php">Consultar Tu ultimo Registro</a>
            <a class="nav-link" href="login.php">Consultar Tus Datos por Fecha</a>
            <a class="nav-link" href="login.php">Cerrar Sesion </a>
        </nav>
        <div class="wave" style="height: 150px; overflow: hidden;"><svg viewBox="0 0 500 150" preserveAspectRatio="none"
                style="height: 100%; width: 100%;">
                <path d="M0.00,49.98 C150.00,150.00 349.20,-50.00 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #fff;"></path>
            </svg></div>
    </header>
    <main>
        <center>
        <section class="contenedor sobre-nosotros">
            <h2 class="titulo">Seguidor de actividad Fisica</h2>
            <div class="contenedor-sobre-nosotros">
                <div class="contenido-textos">
                    <table border="2">
                        <thead>
                            <tr>
                                <th style="padding: 10px;">ID </th>
                                <th style="padding: 10px;">Nodo ID      </th>
                                <th style="padding: 10px;"> Accx  </th>
                                <th style="padding: 10px;">Accy  </th>
                                <th style="padding: 10px;">Accz  </th>
                                <th style="padding: 10px;">RotX  </th>
                                <th style="padding: 10px;">RotY  </th>
                                <th style="padding: 10px;">Rotz  </th>
                                <th style="padding: 10px;">Ejercicio  </th>
                                <th style="padding: 10px;">Fecha  </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                    $conexion = conexion();
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'http://localhost:5000/datosidnodo?idnodo='.$refidnodo); 
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                    curl_setopt($ch, CURLOPT_HEADER, 0); 
                    $respuesta = curl_exec($ch); 
       
                    $resp = json_decode($respuesta);
                  $tam = count($resp);
                  for ($i=0; $i<$tam; $i++){
                  $j = $resp[$i];
                  $id = $j -> id;
                  $idnodo = $j -> idnodo;
                  $accx = $j -> accx;
                  $accy = $j -> accy;
                  $accz = $j -> accz;
                  $rotx = $j -> rotx;
                  $roty = $j -> roty;
                  $rotz = $j -> rotz;
                  $pred = $j -> pred;
                  $fecha = $j -> fecha;



                  echo "<tr>";
                  echo"<td>";
                  echo $id;
                  echo "</td>";
                  echo"<td>";
                  echo $idnodo;
                  echo "</td>";
                  echo"<td>";
                  echo $accx;
                  echo "</td>";
                  echo"<td>";
                  echo $accy;
                  echo "</td>";
                  echo"<td>";
                  echo $accz;
                  echo "</td>";
                  echo"<td>";
                  echo $rotx;
                  echo "</td>";
                  echo"<td>";
                  echo $roty;
                  echo "</td>";
                  echo"<td>";
                  echo $rotz;
                  echo "</td>";
                  echo"<td>";
                  echo $pred;
                  echo "</td>";
                  echo"<td>";
                  echo $fecha;
                  echo "</td>";

                 
                  }
                  ?>
                            <!-- Agrega más filas según sea necesario -->
                        </tbody>
                    </table>
            </div>
        </section>
        </center>
    </main>
    <footer>
        <div class="contenedor-footer">
            
            <div class="content-foo">
                <h4>Grupo de Trabajo</h4>
                <p>
                    Nelson Andres Delgado Machado, Jhon Edinson Caicedo Loaiza, Miguel Angel Acevedo Delgado, Jesus Antonio Valencia Escobar                </p>
            </div>
            
    
    </footer>
</body>

</html>