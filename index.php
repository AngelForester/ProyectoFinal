<?php
session_start();

// 1. SANEAMIENTO (Arregla el Input Validation de $_GET)
$pagina_solicitada = isset($_GET['p']) ? htmlspecialchars(trim($_GET['p']), ENT_QUOTES, 'UTF-8') : 'inicio';

// 2. MAPEO ESTRICTO (Arregla la alerta crítica de File Inclusion LFI)
// Solo estos archivos exactos pueden ser incluidos.
$rutas_seguras = [
    'inicio' => 'vistas/inicio.php',
    'nosotros' => 'vistas/nosotros.php',
    'servicios' => 'vistas/servicios.php',
    'contacto' => 'vistas/contacto.php',
    'login' => 'vistas/login.php'
];

// 3. VALIDACIÓN
if (array_key_exists($pagina_solicitada, $rutas_seguras)) {
    $pagina = $pagina_solicitada;
    $archivo_a_incluir = $rutas_seguras[$pagina_solicitada];
} else {
    $pagina = 'inicio';
    $archivo_a_incluir = 'vistas/inicio.php';
}

// Variables de sesión seguras
$usuario_logueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
$rol_usuario = isset($_SESSION['rol']) ? $_SESSION['rol'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hosanna - <?php echo htmlspecialchars(ucfirst($pagina), ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="css/style.css?v=7">
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
                <?php if ($usuario_logueado !== null): ?>
                    <span style="color:var(--accent); font-weight:bold; margin-right:10px;">👤 <?php echo htmlspecialchars($usuario_logueado, ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($rol_usuario, ENT_QUOTES, 'UTF-8'); ?>)</span>
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
        <a href="index.php?p=inicio">Inicio</a> > <span style="text-transform: capitalize;"><?php echo htmlspecialchars($pagina, ENT_QUOTES, 'UTF-8'); ?></span>
    </div>

    <main>
        <?php include $archivo_a_incluir; ?>
    </main>

    <script src="js/modelo.js?v=7"></script>
    <script src="js/idiomas.js?v=7"></script>
    <script src="js/controlador.js?v=7"></script>
</body>
</html>
