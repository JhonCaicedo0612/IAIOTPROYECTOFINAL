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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bienvenida</title>
    <link rel="stylesheet" href="../CSS/estilospagin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        echo 'USUARIO ' . $_SESSION['usuario'];
                        ?>
            </button>
        </div>

        <div class="direccionador">

            <canvas id="grafica"></canvas>
            <script>
                const urlSearchParams = new URLSearchParams(window.location.search);
                const tipo = urlSearchParams.get("tipo");
                const nodo = urlSearchParams.get("nodo");
                console.log("El tipo es:", tipo);
                switch (tipo) {
                    case "acx":
                        titulo = "Aceleración en X";
                        break;
                    case "acy":
                        titulo = "Aceleración en Y";
                        break;
                    case "acz":
                        titulo = "Aceleración en Z";
                        break;
                    case "rotx":
                        titulo = "Rotación en X";
                        break;
                    case "roty":
                        titulo = "Rotación en Y";
                        break;
                    case "rotz":
                        titulo = "Rotación en Z";
                        break;
                    case "temp":
                        titulo = "Temperatura";
                        break;
                }
                const url = `http://localhost:1880/datos-idnodo?idnodo=${nodo}`;
                fetch(url).then(response => response.json()).then(data => {
                    const $grafica = document.querySelector("#grafica");
                    const etiquetas = [];
                    const datos = {
                        label: titulo,
                        data: [],
                        backgroundColor: "",
                        borderColor: "blue",
                        borderWidth: 1
                    };
                    for (let i = 0; i < data.length; i++) {
                        etiquetas.push(data[i].fecha);
                        switch (tipo) {
                            case "acx":
                                datos.data.push(data[i].ac_x);
                                break;
                            case "acy":
                                datos.data.push(data[i].ac_y);
                                break;
                            case "acz":
                                datos.data.push(data[i].ac_z);
                                break;
                            case "rotx":
                                datos.data.push(data[i].rot_x);
                                break;
                            case "roty":
                                datos.data.push(data[i].rot_y);
                                break;
                            case "rotz":
                                datos.data.push(data[i].rot_z);
                                break;
                            case "temp":
                                datos.data.push(data[i].temperatura);
                                break;
                        }
                    }
                    new Chart($grafica, {
                        type: 'line', // Tipo de gráfica 
                        data: {
                            labels: etiquetas,
                            datasets: [datos]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }],
                            },
                        }
                    });
                }).catch(error => console.error(error));
            </script>






        </div>
    </div>

</body>

</html>