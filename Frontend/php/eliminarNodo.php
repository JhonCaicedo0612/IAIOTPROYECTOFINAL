<?php

$nodo = $_POST["idnodo"];


// URL de la solicitud POST
$url = "http://localhost:1880/eliminarNodo";

// Datos que se enviarán en la solicitud POST
$data = array(
    'idnodo' => $nodo,
);


$json_data = json_encode($data);
//echo $json_data;

// Inicializar cURL
$ch = curl_init();

// Configurar opciones de cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecutar la solicitud POST
$response = curl_exec($ch);

// Manejar la respuesta
if ($response === false) {
    header("Location:index.html");
}
// Cerrar la conexión cURL
curl_close($ch);
header("Location:nodos.php");
