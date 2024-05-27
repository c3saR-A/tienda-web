<?php
require "../config/database.php";
require "../config/config.php";
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
    <title>Web Shop</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <script src="https://kit.fontawesome.com/f7e8f4349b.js" crossorigin="anonymous"></script>
</head>
<body>

<header class="Main-header">
    <nav class="nav">
        <section class="Nombre">Radio Fish</section>
        <i class="fa-solid fa-bars fa-xl" style="color: #eeeeee;"></i>
        <a href="../HTML/pagina2.php">
    <i class="fa-solid fa-cart-shopping fa-xl" style="color: #eeeeee;"></i> 
</a>
<section class="Número"><span id="num_carrito"><?php echo isset($_SESSION['carrito']['productos']) ? count($_SESSION['carrito']['productos']) : 0; ?></span></section>

    </nav>

</header>

<main class="Main-content">
    <section id="Carrusel">
        <div class="Conten-carrusel" id="Conten-1">
            <article class="Item-carrusel" id="item-1">
                <img src="../IMG-ICON/ProArt Studiobook Pro 16 OLED.png" alt="ProArt Studiobook Pro 16 OLED" class="Imagen-carrusel">
                <img src="../IMG-ICON/ASUS Vivobook S 14 Flip OLED.png" alt="ASUS Vivobook S 14 Flip OLED" class="Imagen-carrusel">
            </article>
            <article class="Flechas-carrusel">
                <a href="#Conten-3">
                    <i class="fa-solid fa-angle-left fa-xl" style="color: #FD7014;"></i>
                </a>
                <a href="#Conten-2">
                    <i class="fa-solid fa-angle-right fa-xl" style="color: #FD7014;"></i>
                </a>
            </article>
        </div>
        <div class="Conten-carrusel" id="Conten-2">
            <article class="Item-carrusel" id="item-2">
                <img src="../IMG-ICON/ASUS Chromebook Vibe CX55 Flip.png" alt="ASUS Chromebook Vibe CX55 Flip" class="Imagen-carrusel">
                <img src="../IMG-ICON/Zenbook Flip 15 OLED.png" alt="Zenbook Flip 15 OLED" class="Imagen-carrusel">
            </article>
            <article class="Flechas-carrusel">
                <a href="#Conten-1">
                    <i class="fa-solid fa-angle-left fa-xl" style="color: #FD7014;"></i>
                </a>
                <a href="#Conten-3">
                    <i class="fa-solid fa-angle-right fa-xl" style="color: #FD7014;"></i>
                </a>
            </article>
        </div>
        <div class="Conten-carrusel" id="Conten-3">
            <article class="Item-carrusel" id="item-3">
                <img src="../IMG-ICON/ROG Strix G16 G614.png" alt="ROG Strix G16 G614" class="Imagen-carrusel">
                <img src="../IMG-ICON/ROG Strix SCAR 18 G834.png" alt="ROG Strix SCAR 18 G834" class="Imagen-carrusel">
            </article>
            <article class="Flechas-carrusel">
                <a href="#Conten-2">
                    <i class="fa-solid fa-angle-left fa-xl" style="color: #FD7014;"></i>
                </a>
                <a href="#Conten-1">
                    <i class="fa-solid fa-angle-right fa-xl" style="color: #FD7014;"></i>
                </a>
            </article>
        </div>
    </section>
    <section class="Productos-sección">
        <div class="Productos">
            <?php foreach ($resultado as $row) { ?>
                <article class="Producto">
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
        </div>
        <div class="Botones-producto">
        <?php foreach ($resultado as $row) { ?>
    <button class="Botones" type="button" onclick="addproducto(<?php echo $row['id'] ?>)">Agregar compra</button>
<?php } ?>
    </section>
</main>
<script>
    function addproducto(id) {
        let url = 'clases/carrito.php';
        let formData = new FormData();
        formData.append('id', id);

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        })
        .then(response => response.json())
        .then(data => {
            if (data.ok) {
                var elemento = document.getElementById("num_carrito");
                elemento.innerHTML = data.numero;
            }
        })
        .catch(error => {
            console.error('Error al agregar producto al carrito:', error);
        });
    }
</script>





<footer class="Footer">
    Derechos de autor &copy; 2024 Tienda Online.
</footer>

</body>
</html>