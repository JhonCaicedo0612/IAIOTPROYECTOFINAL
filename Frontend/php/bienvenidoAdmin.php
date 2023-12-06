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

    if($_SESSION['tipo'] == 2){
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
    <title>bienvenida</title>
    <link rel="stylesheet" href="../assests/CSS/estilospagina.css">
</head>
<body>
    <div class="contenedor">
        
        <div class="barra">
            <a href="../php/cerrar.php">
                <button>logout</button>
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
            <a href="ultimosdatosAdmin.php">
                <button>Ultimos datos</button>
            </a>

            <button> <?php
            echo 'USUARIO '.$_SESSION['usuario'];
            ?>
            </button>   
        </div>

        <div class="direccionador">
            <h1>Bienvenido  <?php echo $_SESSION['usuario']; ?></h1>
            <p>Desde esta app puedes crear, modificar y eliminar tanto usuarios como nodos.
            </p>
        </div>
    </div>
    
</body>
</html>
