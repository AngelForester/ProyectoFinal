// --- SISTEMA DE IDIOMAS (js/idiomas.js) ---
const traducciones = {
    es: {
        nav_inicio: "Inicio", nav_nosotros: "Nosotros", nav_cachorros: "Cachorros", nav_contacto: "Contacto", nav_login: "Ingresar", nav_logout: "Cerrar sesión",
        hero_titulo: "Excelencia y Majestuosidad Canina",
        hero_desc: "Bienvenidos a Hosanna. Somos un criadero especializado dedicado exclusivamente a la preservación, salud y exposición de la majestuosa raza Lebrel Afgano.",
        novedades_tit: "Últimas Novedades",
        noticia_1_tit: "Campeonato Nacional 2024", noticia_1_desc: "Nuestro ejemplar estelar 'Golden King' ha obtenido el prestigioso galardón de 'Mejor de la Raza' en la reciente exposición nacional.",
        noticia_2_tit: "Nueva Camada Planificada", noticia_2_desc: "Nos complace anunciar que la lista de espera para la camada de invierno ya está oficialmente abierta. Cupos limitados.",
        noticia_3_tit: "🏆 Calidad Certificada", noticia_3_desc: "Todos nuestros cachorros se entregan con pedigree internacional, microchip y esquema de vacunación completo.",
        nosotros_tit: "Sobre Nosotros",
        mision_tit: "Nuestra Misión", mision_desc: "Preservar la elegancia y salud genética de la raza Lebrel Afgano a nivel mundial, garantizando ejemplares con temperamento equilibrado.",
        vision_tit: "Nuestra Visión", vision_desc: "Ser el criadero de referencia en Norteamérica para el año 2030, destacando por nuestro trato ético y campeonatos internacionales.",
        foda_tit: "Análisis FODA", foda_fortalezas: "Fortalezas", foda_oportunidades: "Oportunidades", foda_debilidades: "Debilidades", foda_amenazas: "Amenazas",
        tienda_tit: "Cachorros y Productos de Cuidado", buscar: "Buscar productos o ejemplares...",
        btn_agregar: "Agregar al carrito", carrito_tit: "🛒 Tu Carrito", total: "Total:", btn_finalizar: "Finalizar Compra", carrito_vacio: "El carrito está vacío",
        contacto_tit: "Contacto", contacto_desc: "Envíenos sus dudas sobre disponibilidad de ejemplares o productos.",
        lbl_nombre: "Nombre Completo:", lbl_correo: "Correo Electrónico:", lbl_mensaje: "Mensaje:", btn_enviar: "Enviar Mensaje",
        ubicacion_tit: "Nuestra Ubicación", ubicacion_desc: "Visítenos en nuestras instalaciones principales en Piedras Negras, Coahuila.",
        login_tit: "Iniciar Sesión", lbl_usuario: "Usuario o Correo:", lbl_pass: "Contraseña:", lbl_mfa: "Seguridad (MFA):", btn_ingresar: "Ingresar"
    },
    en: {
        nav_inicio: "Home", nav_nosotros: "About Us", nav_cachorros: "Puppies", nav_contacto: "Contact", nav_login: "Login", nav_logout: "Logout",
        hero_titulo: "Canine Excellence and Majesty",
        hero_desc: "Welcome to Hosanna. We are a specialized breeder dedicated exclusively to the preservation, health, and exhibition of the majestic Afghan Hound breed.",
        novedades_tit: "Latest News",
        noticia_1_tit: "National Championship 2024", noticia_1_desc: "Our stellar specimen 'Golden King' has won the prestigious 'Best of Breed' award at the recent national exhibition.",
        noticia_2_tit: "New Litter Planned", noticia_2_desc: "We are pleased to announce that the waiting list for the winter litter is officially open. Limited spots.",
        noticia_3_tit: "🏆 Certified Quality", noticia_3_desc: "All our puppies are delivered with an international pedigree, microchip, and full vaccination schedule.",
        nosotros_tit: "About Us",
        mision_tit: "Our Mission", mision_desc: "To preserve the elegance and genetic health of the Afghan Hound breed worldwide, ensuring dogs with balanced temperaments.",
        vision_tit: "Our Vision", vision_desc: "To be the leading breeder in North America by 2030, distinguished by our ethical treatment and international championships.",
        foda_tit: "SWOT Analysis", foda_fortalezas: "Strengths", foda_oportunidades: "Opportunities", foda_debilidades: "Weaknesses", foda_amenazas: "Threats",
        tienda_tit: "Puppies and Care Products", buscar: "Search products or dogs...",
        btn_agregar: "Add to cart", carrito_tit: "🛒 Your Cart", total: "Total:", btn_finalizar: "Checkout", carrito_vacio: "Cart is empty",
        contacto_tit: "Contact", contacto_desc: "Send us your questions about dog or product availability.",
        lbl_nombre: "Full Name:", lbl_correo: "Email Address:", lbl_mensaje: "Message:", btn_enviar: "Send Message",
        ubicacion_tit: "Our Location", ubicacion_desc: "Visit us at our main facilities in Piedras Negras, Coahuila.",
        login_tit: "Login", lbl_usuario: "Username or Email:", lbl_pass: "Password:", lbl_mfa: "Security (MFA):", btn_ingresar: "Login"
    }
};

const GestorIdiomas = {
    inicializar: function() {
        const idiomaGuardado = localStorage.getItem('idioma_pref') || 'es';
        this.cambiarIdioma(idiomaGuardado);
        const selector = document.getElementById('selector-idioma');
        if (selector) selector.value = idiomaGuardado;
    },
    cambiarIdioma: function(lang) {
        localStorage.setItem('idioma_pref', lang);
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (traducciones[lang] && traducciones[lang][key]) {
                if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                    el.placeholder = traducciones[lang][key];
                } else {
                    el.innerText = traducciones[lang][key];
                }
            }
        });
    }
};