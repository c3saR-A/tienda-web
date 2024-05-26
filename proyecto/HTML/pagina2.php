<?php
require "../config/database.php";
$db = new Database();
$conect = $db->conectar();

$sql = $conect->prepare("SELECT id, nombre, precio FROM productos WHERE activo=20");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

?>
<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radio Fish</title>
    <link rel="stylesheet" href="../CSS/style2.css">
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const decrementButton = document.getElementById('btn-decremento');
            const incrementButton = document.getElementById('btn-incremento');
            const quantityDisplay = document.getElementById('quantity-num');
            const priceDisplay = document.getElementById('price');
            const totalDisplay = document.getElementById('total');
            const cartIconDisplay = document.getElementById('cart-icon');
            const payButton = document.getElementById('pay-btn');
            
            let quantity = parseInt(quantityDisplay.textContent);
            const price = 100; // Precio del producto
            
            decrementButton.addEventListener('click', () => {
                if (quantity > 1) {
                    quantity--;
                    updateDisplays();
                }
            });
            
            incrementButton.addEventListener('click', () => {
                quantity++;
                updateDisplays();
            });

            payButton.addEventListener('click', () => {
                location.reload();
            });
            
            function updateDisplays() {
                quantityDisplay.textContent = quantity;
                totalDisplay.textContent = '$' + (quantity * price).toFixed(2);
                cartIconDisplay.textContent = quantity;
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <header>
            <a href="../HTML/index.php">
                <div class="home"><img src="../IMG-ICON/home.svg" alt="Icono de home" class="Home-icon"></div>
            </a>
            <div class="store-name">Radio Fish</div>
            <div class="cart-info">Cantidad de compras</div>
            <div class="cart-icon" id="cart-icon"><span id="num_carrito"><?php echo isset($_SESSION['carrito']['productos']) ? count($_SESSION['carrito']['productos']) : 0; ?></span></div>
        </header></div><div >
    </div> 
    <main>
    <section class="Productos-sección">
        <div class="Productos">
            <?php foreach ($resultado as $row) { ?>
                <article class="Producto">        <div class="quantity">
            <button class="btn" id="btn-decremento">-</button>
            <div class="quantity-number" id="quantity-num"><span id="num_carrito"><?php echo isset($_SESSION['carrito']['productos']) ? count($_SESSION['carrito']['productos']) : 0; ?></span></div>
            <button class="btn" id="btn-incremento">+</button>
        </div>
                <?php
                    $id = $row["id"];
                    $imagen = "../IMG-ICON/" . $id . "/imagen.png";
                    if (!file_exists($imagen)) {
                        $imagen = "../IMG-ICON/no_photo.png";
                    }
                    ?>
                    <img src="<?php echo $imagen; ?>" class="Imagen-producto">
                    <article class="Info-producto">
                        <h4><?php echo $row["nombre"]; ?></h4>
                        <p>$ <?php echo number_format($row["precio"], 2, ".", ","); ?></p>
                    </article>
                </article>
            <?php } ?>
        <div class="price">
            <div><h4>Precio</h4></div>
            <div><p id="price">$100</p></div>
        </div>
        <div class="total">
            <div><h4>Total</h4></div>
            <div><p id="total">$100</p></div>
        </div>
        <div class="pay-button">
        <button id="pay-btn">Pagar</button> <?php session_destroy(); ?>
        </div>
    </main>
    
<footer class="Footer">
    Derechos de autor &copy; 2024 Tienda Online.
</footer>
</body>
</html>