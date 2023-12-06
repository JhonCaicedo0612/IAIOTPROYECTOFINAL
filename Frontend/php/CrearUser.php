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


$nombre = $_POST["nombre"];
$usuario = $_POST["usuario"];
$pass = $_POST["password"];
$tipo = $_POST["tipo"];

$servurl = "http://localhost:5000/consultarusers";
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
    $usuario1 = $dec->user;
    echo $usuario1;
    if ($usuario1 == $usuario) {
        $cont += 1;
    }
}




if ($cont >= 1) {
    echo '
        <script>
            alert("El nombre de usuario no esta disponible");
            window.location = "usuarios.php";
        </script>
    ';
    die();
} else {
    // URL de la solicitud POST
    $url = 'http://127.0.0.1:5000/adduser';


    // Datos que se enviarán en la solicitud POST
    $data = array(
        'name' => $nombre,
        'user' => $usuario,
        'password' => $pass,
        'type' => $tipo,
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
    header("Location:usuarios.php");
}
