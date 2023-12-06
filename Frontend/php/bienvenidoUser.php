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
    <title>bienvenida</title>
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
            <p>Desde esta app puedes consular el estado de todos tus nodos,
                para poder realizar esto debes seleccionar entre tus nodos y
                obtendras los ultimos datos de ese nodo
            </p>
        </div>
    </div>

</body>

</html>