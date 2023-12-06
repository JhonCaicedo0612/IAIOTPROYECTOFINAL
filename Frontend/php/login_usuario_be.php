<?php
session_start();
$usuario = $_POST['user'];
$password = $_POST['password'];

// Crear un array asociativo con los datos del usuario
$datosUsuario = [
    'user' => $usuario,
    'password' => $password
];

// Convertir el array en formato JSON
$datosJSON = json_encode($datosUsuario);

// Configurar la URL de la API
$api_url = 'http://127.0.0.1:1880/validarUser';

// Configurar las opciones de la solicitud cURL
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $datosJSON);

// Realizar la solicitud a la API
$response = curl_exec($ch);

// Comprobar errores
if ($response === false) {
    echo 'Error de cURL: ' . curl_error($ch);
} else {
    // Decodificar la respuesta JSON en un array asociativo
    $data = json_decode($response, true);

    if (!empty($data) && is_array($data)) {
        // Verificar si la respuesta contiene datos válidos y guardarlos en la sesión
        $_SESSION['usuario'] = $data[0]['user'];
        $_SESSION['tipo'] = $data[0]['tipo'];

        // Redirigir al usuario a otra página
        if ($data[0]['tipo'] == 2) {
            header('Location: bienvenidoUser.php');
            exit();
        } else {
            header('Location: bienvenidoAdmin.php');
            exit();
        }
    } else {
        echo '
            <script>
                alert("Datos errados");
                window.location ="../index.php";
            </script>
            ';
        exit;
    }
}

// Cerrar la sesión de cURL
curl_close($ch);
