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

$idnodo = $_POST["idnodo"];
$nombre = $_POST["nombre"];
$ubicacion = $_POST["ubicacion"];
$user = $_POST["user"];
$estado = $_POST["estado"];

$servurl = "http://localhost:1880/consultarNodo";
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
$cont = 0;
for ($i = 0; $i < $long; $i++) {
    $dec = $resp[$i];
    $IDNODO1 = $dec->idnodo;
    echo $IDNODO1;
    if ($IDNODO1 == $idnodo) {
        $cont += 1;
    }
}




if ($cont >= 1) {
    echo '
        <script>
            alert("El Id del nodoesta disponible");
            window.location = "usuarios.php";
        </script>
    ';
    die();
} else {
    // URL de la solicitud POST
    $url = 'http://127.0.0.1:1880/crearNodo';


    // Datos que se enviarán en la solicitud POST
    $data = array(
        'idnodo' => $idnodo,
        'nombre' => $nombre,
        'ubicacion' => $ubicacion,
        'user' => $user,
        'estado' => $estado,
    );
    $json_data = json_encode($data);

    // Inicializar cURL
    $ch = curl_init();

    // Configurar opciones de cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud POST
    $response = curl_exec($ch);









    // Manejar la respuesta
    if ($response === false) {
        header("Location:../index.php");
    }
    // Cerrar la conexión cURL
    curl_close($ch);
    header("Location:nodos.php");
}
