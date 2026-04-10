// --- MODELO: Gestión de Datos (SQL vía API y LocalStorage) ---
const Modelo = {
    // 1. Guardar mensaje de contacto en SQL real (vía Fetch a api.php)
    guardarMensajeContacto: async function(nombre, correo, mensaje) {
        try {
            const respuesta = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'guardar_contacto', nombre: nombre, correo: correo, mensaje: mensaje })
            });
            const datos = await respuesta.json();
            return datos.status === "success";
        } catch (error) {
            console.error("Error al enviar contacto:", error);
            return false;
        }
    },

    // 2. Procesar Login de forma segura
    procesarLoginSQL: async function(usuario, password) {
        try {
            const respuesta = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'login', usuario: usuario, password: password })
            });
            return await respuesta.json(); // Devuelve {status: "success", usuario: "...", rol: "..."}
        } catch (error) {
            console.error("Error en Login BD:", error);
            return { status: "error" };
        }
    },

    // 3. Cerrar Sesión en el servidor
    cerrarSesionServidor: async function() {
        try {
            await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'logout' })
            });
        } catch (error) {
            console.error("Error al cerrar sesión:", error);
        }
    },

    // 4. Carrito de Compras (Gestión en LocalStorage del navegador)
    obtenerCarrito: function() { 
        return JSON.parse(localStorage.getItem('bd_carrito')) || []; 
    },
    
    guardarCarrito: function(carrito) { 
        localStorage.setItem('bd_carrito', JSON.stringify(carrito)); 
    },
    
    vaciarCarrito: function() { 
        localStorage.removeItem('bd_carrito'); 
    }, // <--- ¡Esta es la coma que faltaba!

    // 5. Obtener Productos desde la Base de Datos
    obtenerProductos: async function() {
        try {
            const respuesta = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'obtener_productos' })
            });
            const datos = await respuesta.json();
            return datos.status === "success" ? datos.data : [];
        } catch (error) {
            console.error("Error al obtener productos:", error);
            return [];
        }
    },

    // 6. Enviar Pedido a SQL
    guardarPedido: async function(carrito, total) {
        try {
            const respuesta = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'crear_pedido', carrito: carrito, total: total })
            });
            return await respuesta.json();
        } catch (error) {
            console.error("Error al guardar pedido:", error);
            return { status: "error", message: "Error de conexión." };
        }
    }
};