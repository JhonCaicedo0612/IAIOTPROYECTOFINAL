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
    <link rel="stylesheet" href="../css/estilospagin.css">
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
            <p>¡Saludos, Como administrador de nuestra comunidad de fitness,
             tienes un papel vital en la gestión y promoción de un estilo de vida activo y saludable.
             Desde este panel, tendrás acceso a herramientas y funciones exclusivas para supervisar y mejorar 
             la experiencia de nuestros usuarios.
             Utiliza las opciones de administrador para gestionar perfiles de usuarios, monitorear el rendimiento de la comunidad y garantizar que cada miembro obtenga el máximo beneficio de nuestra plataforma.
             <h2>¡Tu contribución es fundamental para mantener nuestra comunidad en forma y en movimiento!</h2>
            </p>
            <br>
            <br>
            <center>
            <img src="../assests/img/monitoreoentre.jpg" alt="Monitoreo de datos">
            </center>
            <br>
            <br>
        </div>
    </div>
    
</body>
</html>
