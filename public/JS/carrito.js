document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.Botones-addToCartBtn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            fetch(`/api/agregar-al-carrito/${productId}`, { method: 'POST' })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al agregar el producto al carrito');
                    }
                    return actualizarNumeroCarrito();
                })
                .then(() => {
                    console.log('Producto agregado al carrito exitosamente');
                })
                .catch(error => {
                    console.error('Error al agregar el producto al carrito:', error);
                });
        });
    });

    // Asignar eventos a los botones de actualizar cantidad
    const updateQuantityButtons = document.querySelectorAll('.update-quantity');
    updateQuantityButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const action = this.getAttribute('data-action');
            const quantityElement = this.parentElement.querySelector('.quantity');
            let currentQuantity = parseInt(quantityElement.textContent);

            if (action === 'decrease') {
                currentQuantity -= 1;
            } else if (action === 'increase') {
                currentQuantity += 1;
            }

            fetch(`/api/actualizar-cantidad/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ cantidad: currentQuantity })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al actualizar la cantidad del producto');
                }
                return response.json();
            })
            .then(() => {
                // Actualizar la cantidad en la interfaz
                if (currentQuantity <= 0) {
                    this.closest('.product').remove();
                } else {
                    quantityElement.textContent = currentQuantity;
                }
                return actualizarNumeroCarrito();
            })
            .catch(error => {
                console.error('Error al actualizar la cantidad del producto:', error);
            });
        });
    });

    actualizarNumeroCarrito();
});

// Funciones
function obtenerProductosDelCarrito() {
    return fetch("/api/carrito")
        .then(response => response.json());
}

function actualizarNumeroCarrito() {
    obtenerProductosDelCarrito().then(productos => {
        const cartNumElement = document.getElementById('cart-num');
        const totalCantidad = productos.reduce((acc, item) => acc + item.cantidad, 0);
        cartNumElement.textContent = totalCantidad;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    function actualizarTotal() {
        let total = 0;
        const precios = document.querySelectorAll('.price p');
        precios.forEach(precio => {
            total += parseFloat(precio.textContent.replace('$', ''));
        });
        document.getElementById('total-price').textContent = '$' + total.toFixed(2);
    }

    function actualizarCantidad(productId, cantidad) {
        fetch(`/api/actualizar-cantidad/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ cantidad: cantidad }),
        })
        .then(response => response.json())
        .then(data => {
            document.querySelector(`[data-quantity-id="${productId}"]`).textContent = cantidad;
            document.querySelector(`[data-product-id="${productId}"]`).closest('main').querySelector('.price p').textContent = '$' + (data.precio * cantidad).toFixed(2);
            actualizarTotal();
        })
        .catch(error => console.error('Error:', error));
    }

    document.querySelectorAll('.increase-quantity').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const quantityElement = document.querySelector(`[data-quantity-id="${productId}"]`);
            let cantidad = parseInt(quantityElement.textContent);
            cantidad += 1;
            actualizarCantidad(productId, cantidad);
        });
    });

    document.querySelectorAll('.decrease-quantity').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const quantityElement = document.querySelector(`[data-quantity-id="${productId}"]`);
            let cantidad = parseInt(quantityElement.textContent);
            if (cantidad > 1) {
                cantidad -= 1;
                actualizarCantidad(productId, cantidad);
            } else {
                alert('La cantidad mínima es 1');
            }
        });
    });

    actualizarTotal();
});

function pagar() {
    fetch("/api/limpiar-carrito", {
        method: 'POST'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al limpiar el carrito');
        }
        return response.json();
    })
    .then(() => {
        // Limpiar el carrito localmente
        carrito = [];
        // Actualizar la interfaz
        actualizarNumeroCarrito();
        // Redirigir a la página de inicio u otra página
        window.location.href = ""; // Redirige a la página de inicio
    })
    .catch(error => {
        console.error('Error al limpiar el carrito:', error);
    });
}
