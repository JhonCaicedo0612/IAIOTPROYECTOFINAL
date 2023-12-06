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

if ($_SESSION['tipo'] == 1) {
    echo '
            <script>
                alert("No tienes los privilegios para esta pagina");
                window.location = "bienvenidoAdmin.php";
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
    <title>Cliente</title>
    <link rel="stylesheet" href="../CSS/estilospagin.css">
</head>

<body>
    <div class="contenedor">

        <div class="barra">
            <a href="../php/cerrar.php">
                <button>logout</button>
            </a>
            <a href="consultasUser.php">
                <button>Consultas</button>
            </a>
            <a href="ultimosdatos.php">
                <button>Ultimos datos</button>
            </a>

            <button> <?php
                        echo 'USUARIO ' . $_SESSION['usuario'];
                        ?>
            </button>
        </div>

        <div class="direccionador">
            <h1>Bienvenido <?php echo $_SESSION['usuario']; ?></h1>
            <p>  Este es tu destino principal para el fitness y el bienestar personalizado.
                 Somos más que una plataforma de ejercicios; somos tu compañero de confianza en tu 
                 viaje hacia un estilo de vida activo y saludable; Con nuestra función de monitoreo en tiempo real, 
                 podrás ver tus datos de ejercicio al instante, mantenemos un registro detallado para que 
                 puedas seguir tu evolución día a día.
            </p>
            <br>
            <br>
            <center>
            <img src="../assests/img/monitoreo.jpg" alt="Monitoreo de datos">
            </center>
            <br>
            <br>
        
        </div>
     
    </div>

</body>

</html>