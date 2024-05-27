// Importación de librerías
const express = require("express");
const mysql = require("mysql");
const path = require("path");

// Configuración de Express
const app = express();
app.set("view engine", "ejs");
app.set("views", path.join(__dirname, "views")); // Asegúrate de que las vistas están en el directorio correcto

// Middleware para manejar los datos JSON en las solicitudes
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Crear conexión a la base de datos
let conexion = mysql.createConnection({
    host: "localhost",
    database: "tienda_web",
    user: "root",
    password: ""
});

// Conectar a la base de datos
conexion.connect(function(err) {
    if (err) {
        console.error("Error conectando a la base de datos:", err);
        return;
    }
    console.log("Conexión exitosa");
});

// Función para obtener un producto por su ID
function obtenerProductoPorId(productId) {
    return new Promise((resolve, reject) => {
        conexion.query("SELECT * FROM productos WHERE id = ?", [productId], (error, resultados) => {
            if (error) {
                reject(error);
            } else if (resultados.length === 0) {
                reject("Producto no encontrado");
            } else {
                resolve(resultados[0]);
            }
        });
    });
}

// Definir un array para almacenar los productos del carrito
let carrito = [];

// Ruta para agregar un producto al carrito
app.post("/api/agregar-al-carrito/:id", (req, res) => {
    const productId = req.params.id;
    obtenerProductoPorId(productId).then(producto => {
        const index = carrito.findIndex(item => item.id == productId);
        if (index !== -1) {
            carrito[index].cantidad += 1;
        } else {
            producto.cantidad = 1;
            carrito.push(producto);
        }
        res.status(200).send("Producto agregado al carrito exitosamente");
    }).catch(error => {
        console.error('Error al agregar el producto al carrito:', error);
        res.status(500).send("Error al agregar el producto al carrito");
    });
});

// Ruta para actualizar la cantidad de un producto en el carrito
app.post("/api/actualizar-cantidad/:id", (req, res) => {
    const productId = req.params.id;
    const { cantidad } = req.body;
    const index = carrito.findIndex(item => item.id == productId);
    if (index !== -1) {
        carrito[index].cantidad = cantidad;
        if (carrito[index].cantidad <= 0) {
            carrito.splice(index, 1);
        }
        res.status(200).send("Cantidad actualizada exitosamente");
    } else {
        res.status(404).send("Producto no encontrado en el carrito");
    }
});

// Ruta para limpiar el carrito
app.post("/api/limpiar-carrito", (req, res) => {
    // Limpiar el carrito (reiniciar el array)
    carrito = [];
    res.status(200).send("Carrito limpiado exitosamente");
});


// Ruta para obtener los productos del carrito
app.get("/api/carrito", (req, res) => {
    res.json(carrito);
});

// Ruta principal para renderizar la página de inicio
app.get("/", (req, res) => {
    conexion.query("SELECT * FROM productos", (error, resultados) => {
        if (error) {
            console.error("Error al realizar la consulta:", error);
            res.status(500).send("Error al obtener los productos");
            return;
        }
        res.render("index", { productos: resultados });
    });
});

// Ruta para la página del carrito
app.get("/pagina2", (req, res) => {
    res.render("pagina2", { carrito: carrito });
});

// Servir archivos estáticos desde el directorio "public"
app.use(express.static(path.join(__dirname, "public")));

// Configuración del puerto del servidor
app.listen(3000, function() {
    console.log("El servidor está en http://localhost:3000");
});
