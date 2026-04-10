<?php
session_start();

// Validar qué página cargar (por defecto 'inicio')
$pagina = isset($_GET['p']) ? $_GET['p'] : 'inicio';
$paginas_validas = ['inicio', 'nosotros', 'servicios', 'contacto', 'login'];

if (!in_array($pagina, $paginas_validas)) {
    $pagina = 'inicio';
}

// Verificar si hay sesión activa
$usuario_logueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
$rol_usuario = isset($_SESSION['rol']) ? $_SESSION['rol'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hosanna - <?php echo ucfirst($pagina); ?></title>
    <link rel="stylesheet" href="css/style.css?v=4">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <header>
        <h1>HOSANNA</h1>
        <nav>
            <a href="index.php?p=inicio" data-i18n="nav_inicio">Inicio</a>
            <a href="index.php?p=nosotros" data-i18n="nav_nosotros">Nosotros</a>
            <a href="index.php?p=servicios" data-i18n="nav_cachorros">Cachorros</a>
            <a href="index.php?p=contacto" data-i18n="nav_contacto">Contacto</a>
            
            <div id="nav-auth">
                <?php if ($usuario_logueado): ?>
                    <span style="color:var(--accent); font-weight:bold; margin-right:10px;">👤 <?php echo htmlspecialchars($usuario_logueado); ?> (<?php echo htmlspecialchars($rol_usuario); ?>)</span>
                    <a href="#" onclick="Controlador.cerrarSesion(event)" style="color: #ff6b6b; font-weight: bold;" data-i18n="nav_logout">Cerrar sesión</a>
                <?php else: ?>
                    <a href="index.php?p=login" data-i18n="nav_login">Ingresar</a>
                <?php endif; ?>
            </div>
            
            <select id="selector-idioma" onchange="GestorIdiomas.cambiarIdioma(this.value)">
                <option value="es">Español</option>
                <option value="en">English</option>
            </select>
            <button onclick="Controlador.toggleAltoContraste()" title="Modo Accesibilidad">🌗</button>
        </nav>
    </header>

    <div class="breadcrumbs-bar" id="breadcrumbs">
        <a href="index.php?p=inicio">Inicio</a> > <span style="text-transform: capitalize;"><?php echo $pagina; ?></span>
    </div>

    <main>
        <?php include "vistas/{$pagina}.php"; ?>
    </main>

    <script src="js/modelo.js?v=6"></script>
    <script src="js/idiomas.js?v=6"></script>
    <script src="js/controlador.js?v=6"></script>
</body>
</html>