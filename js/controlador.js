// --- CONTROLADOR: Lógica de Interfaz y Funciones ---
const Controlador = {
    init: function() {
        // Inicializar Idioma
        if(typeof GestorIdiomas !== 'undefined') GestorIdiomas.inicializar();
        
        // Mantener el Alto Contraste si estaba activo
        if (localStorage.getItem('altoContrastePreferido') === 'true') {
            document.body.classList.add('alto-contraste');
        }

        // --- Mostrar el Toast de Bienvenida si venimos del Login ---
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('login') === 'success') {
            const toast = document.createElement('div');
            toast.innerHTML = `✅ <strong>¡Acceso Concedido!</strong><br>Sesión iniciada correctamente.`;
            toast.style.cssText = "position: fixed; top: 20px; right: 20px; background: #2E7D32; color: white; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); z-index: 10000; animation: aparecer 0.3s ease-out;";
            document.body.appendChild(toast);
            setTimeout(() => { toast.remove(); }, 3500);
            window.history.replaceState({}, document.title, "index.php?p=inicio");
        }

        // Detectar en qué página estamos y activar funciones
        this.renderizarCarrito();
        this.cargarProductosDinamicos(); // Llamamos a los productos de SQL
        
        const buscador = document.getElementById('buscador-sitio');
        if (buscador) buscador.addEventListener('input', (e) => this.buscarContenido(e.target.value));
    },

    // --- CARGAR PRODUCTOS DESDE SQL ---
    cargarProductosDinamicos: async function() {
        const contenedor = document.getElementById('contenedor-productos');
        if (!contenedor) return; // Si no estamos en servicios, no hace nada

        const productos = await Modelo.obtenerProductos();
        contenedor.innerHTML = ''; // Limpiamos el mensaje de "Cargando..."

        if (productos.length === 0) {
            contenedor.innerHTML = '<p style="width:100%; text-align:center;">No hay productos disponibles por el momento.</p>';
            return;
        }

        productos.forEach(prod => {
            contenedor.innerHTML += `
                <div class="card" data-tags="${prod.etiquetas_busqueda}">
                    <h3>${prod.nombre}</h3>
                    <p>$${prod.precio} USD</p>
                    <button class="btn" onclick="Controlador.agregarAlCarrito(${prod.id_producto}, '${prod.nombre}', ${prod.precio})" data-i18n="btn_agregar">Agregar al carrito</button>
                </div>
            `;
        });

        // Retraducir botones si estamos en inglés
        if(typeof GestorIdiomas !== 'undefined') {
            GestorIdiomas.cambiarIdioma(localStorage.getItem('idioma_pref') || 'es');
        }
    },

    // --- ACCESIBILIDAD ---
    toggleAltoContraste: function() {
        document.body.classList.toggle('alto-contraste');
        localStorage.setItem('altoContrastePreferido', document.body.classList.contains('alto-contraste'));
    },

    // --- CARRITO DE COMPRAS ---
    agregarAlCarrito: function(id_producto, nombre, precio) {
        let carrito = Modelo.obtenerCarrito();
        let existe = carrito.find(item => item.id_producto === id_producto);
        
        if(existe) { existe.cantidad = (existe.cantidad || 1) + 1; } 
        else { carrito.push({ id_producto, nombre, precio, cantidad: 1 }); }
        
        Modelo.guardarCarrito(carrito);
        this.renderizarCarrito();
        alert(`✔ Se agregó "${nombre}" al carrito.`);
    },
    
    eliminarDelCarrito: function(index) {
        let carrito = Modelo.obtenerCarrito();
        carrito.splice(index, 1);
        Modelo.guardarCarrito(carrito);
        this.renderizarCarrito();
    },
    
    renderizarCarrito: function() {
        const lista = document.getElementById('lista-carrito');
        const totalSpan = document.getElementById('total-carrito');
        if (!lista || !totalSpan) return;

        let carrito = Modelo.obtenerCarrito();
        lista.innerHTML = '';
        let total = 0;

        if (carrito.length === 0) {
            lista.innerHTML = `<li data-i18n="carrito_vacio" style="color:#888; text-align:center; padding: 10px;">El carrito está vacío</li>`;
            if(typeof GestorIdiomas !== 'undefined') GestorIdiomas.cambiarIdioma(localStorage.getItem('idioma_pref') || 'es');
        } else {
            carrito.forEach((item, index) => {
                const subtotal = item.precio * (item.cantidad || 1);
                total += subtotal;
                lista.innerHTML += `
                    <li class="cart-item">
                        <span style="font-weight:500;">${item.nombre} x ${item.cantidad || 1}</span>
                        <span>$${subtotal.toFixed(2)}</span>
                        <button class="btn-eliminar" onclick="Controlador.eliminarDelCarrito(${index})">✕</button>
                    </li>`;
            });
        }
        totalSpan.innerText = total.toFixed(2);
    },
    
    finalizarCompra: async function() {
        let carrito = Modelo.obtenerCarrito();
        if (carrito.length === 0) return alert("Tu carrito está vacío.");

        const btn = event.target;
        const textoOriginal = btn.innerText;
        btn.innerText = "Procesando...";

        const total = carrito.reduce((suma, item) => suma + (item.precio * (item.cantidad || 1)), 0);

        // Enviar pedido a la base de datos
        const respuesta = await Modelo.guardarPedido(carrito, total);

        if (respuesta.status === "success") {
            alert("✅ ¡Compra exitosa!\n" + respuesta.message);
            Modelo.vaciarCarrito();
            this.renderizarCarrito();
        } else {
            alert("❌ Ocurrió un error:\n" + (respuesta.message || "Debes iniciar sesión para comprar."));
        }
        
        btn.innerText = textoOriginal;
    },

    // --- BÚSQUEDA DINÁMICA ---
    buscarContenido: function(query) {
        query = query.toLowerCase().trim();
        document.querySelectorAll('.grid .card').forEach(card => {
            const titulo = card.querySelector('h3') ? card.querySelector('h3').innerText.toLowerCase() : '';
            const tags = card.getAttribute('data-tags') ? card.getAttribute('data-tags').toLowerCase() : '';
            
            if (query === "" || titulo.includes(query) || tags.includes(query)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    },

    // --- CONTACTO ---
    procesarContacto: async function(e) {
        e.preventDefault();
        const btn = e.target.querySelector('button[type="submit"]');
        const textOriginal = btn.innerText;
        
        const nombre = document.getElementById('nombre-contacto').value;
        const correo = document.getElementById('correo-contacto').value;
        const mensaje = document.getElementById('mensaje-contacto').value;
        const msgExito = document.getElementById('msg-contacto-exito');

        btn.innerText = "Enviando...";
        btn.disabled = true;

        const exito = await Modelo.guardarMensajeContacto(nombre, correo, mensaje);
        
        btn.innerText = textOriginal;
        btn.disabled = false;

        if (exito) {
            msgExito.style.display = 'block';
            msgExito.style.color = '#2E7D32';
            msgExito.style.background = '#E8F5E9';
            msgExito.innerText = "✅ ¡Mensaje guardado correctamente en SQL!";
            e.target.reset();
            setTimeout(() => { msgExito.style.display = 'none'; }, 4000);
        } else {
            alert("Error al guardar en base de datos. Verifique api.php");
        }
    },

    // --- AUTENTICACIÓN ---
    procesarLogin: async function(e) {
        e.preventDefault();
        
        const btn = e.target.querySelector('button[type="submit"]');
        const textOriginal = btn.innerText;
        btn.innerText = "Verificando...";

        const usuario = document.querySelector('input[name="usuario"]').value;
        const password = document.querySelector('input[name="password"]').value;

        const respuesta = await Modelo.procesarLoginSQL(usuario, password);

        if (respuesta.status === "success") {
            btn.innerText = "Redirigiendo...";
            btn.style.backgroundColor = "#2E7D32";
            btn.style.color = "white";

            setTimeout(() => { window.location.href = "index.php?p=inicio&login=success"; }, 1000);
        } else {
            btn.innerText = textOriginal;
            const errorToast = document.createElement('div');
            errorToast.innerHTML = `❌ <strong>Error</strong><br>Credenciales incorrectas.`;
            errorToast.style.cssText = "position: fixed; top: 20px; right: 20px; background: #C62828; color: white; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); z-index: 10000; animation: aparecer 0.3s ease-out;";
            document.body.appendChild(errorToast);
            setTimeout(() => { errorToast.remove(); }, 3000);
        }
        return false;
    },

    cerrarSesion: async function(e) {
        if(e) e.preventDefault();
        await Modelo.cerrarSesionServidor();
        window.location.href = "index.php?p=login";
    },
    // --- SIMULACIÓN DE RECUPERACIÓN DE CONTRASEÑA ---
    simularRecuperacion: function(e) {
        e.preventDefault();
        const correo = document.getElementById('correo-recuperacion').value;
        const btn = document.getElementById('btn-recuperar');
        const textOriginal = btn.innerText;
        btn.innerText = "Enviando...";

        // Simulamos un retraso de red
        setTimeout(() => {
            const msg = document.getElementById('msg-recuperacion');
            msg.style.display = 'block';
            msg.innerHTML = `✅ Enlace de recuperación enviado a:<br>${correo}`;
            btn.innerText = textOriginal;
            document.getElementById('correo-recuperacion').value = '';
        }, 1500);
        return false;
    }
};

document.addEventListener('DOMContentLoaded', () => Controlador.init());