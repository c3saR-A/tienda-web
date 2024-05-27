<?php
// Iniciar la sesión
session_start();

 
$datos = array(); // Inicializar el array para almacenar los datos de respuesta

if (isset($_POST['id'])) {
    // Obtener el ID del producto desde la solicitud POST
    $id = $_POST['id'];

    // Agregar el producto como un nuevo elemento, independientemente de si ya está en el carrito
    $_SESSION['carrito']['productos'][] = array(
        'id' => $id,
        'cantidad' => 1,
        // Otros detalles del producto como nombre, precio, etc.
    );

    // Obtener el número total de productos en el carrito
    $datos['numero'] = count($_SESSION['carrito']['productos']);
    $datos['ok'] = true; // Indicar que la operación fue exitosa
} else {
    $datos['ok'] = false; // Indicar que la solicitud no incluye un ID de producto
}

// Devolver los datos de respuesta como JSON
echo json_encode($datos);








