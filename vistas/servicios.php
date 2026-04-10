<section>
    <h2 data-i18n="tienda_tit" style="text-align: center;">Cachorros y Productos de Cuidado</h2>
    
    <input type="text" id="buscador-sitio" data-i18n="buscar" placeholder="Buscar productos o ejemplares..." style="max-width: 500px; display: block; margin: 0 auto 30px; width: 100%; padding: 12px; border-radius: 5px; border: 1px solid #ccc;">
    
    <div class="grid" id="contenedor-productos">
        <p style="text-align:center; width:100%;">Cargando productos desde la base de datos...</p>
    </div>

    <div class="carrito-panel" style="background: var(--white); border: 2px solid var(--accent); border-radius: 10px; padding: 25px; margin-top: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">
        <h3 data-i18n="carrito_tit">🛒 Tu Carrito</h3>
        <ul id="lista-carrito" style="list-style: none; padding: 0;"></ul>
        <h4 style="text-align: right;"><span data-i18n="total">Total:</span> $<span id="total-carrito">0.00</span></h4>
        <button class="btn" style="background: var(--primary); color: white;" onclick="Controlador.finalizarCompra()" data-i18n="btn_finalizar">Finalizar Compra</button>
    </div>
</section>