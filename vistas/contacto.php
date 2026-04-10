<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; padding: 20px;">
    
    <section>
        <h2 data-i18n="contacto_tit" style="color: var(--primary);">Contacto</h2>
        <p data-i18n="contacto_desc">Envíenos sus dudas sobre disponibilidad de ejemplares o productos.</p>
        
        <form onsubmit="return Controlador.procesarContacto(event)">
            <label data-i18n="lbl_nombre">Nombre Completo:</label>
            <input type="text" id="nombre-contacto" required>
            
            <label data-i18n="lbl_correo">Correo Electrónico:</label>
            <input type="email" id="correo-contacto" required>
            
            <label data-i18n="lbl_mensaje">Mensaje:</label>
            <textarea id="mensaje-contacto" rows="5" required></textarea>
            
            <button type="submit" class="btn" data-i18n="btn_enviar">Enviar Mensaje</button>
            
            <div id="msg-contacto-exito" style="display:none; padding: 10px; margin-top: 15px; border-radius: 5px; font-weight: bold; text-align: center;"></div>
        </form>
    </section>

    <section style="background: var(--white); padding: 20px; border-radius: 10px; box-shadow: var(--shadow);">
        <h3 data-i18n="ubicacion_tit" style="color: var(--primary); margin-top: 0;">Nuestra Ubicación</h3>
        <p data-i18n="ubicacion_desc">Visítenos en nuestras instalaciones principales en Piedras Negras, Coahuila.</p>
        <p style="font-size: 0.9rem; color: #666; font-weight: bold;">📍 Riva Palacio 705, Juárez, 26060 Piedras Negras, Coah.</p>
        
        <iframe 
            src="https://maps.google.com/maps?q=Riva+Palacio+705,+Juárez,+26060+Piedras+Negras,+Coah.&t=&z=16&ie=UTF8&iwloc=&output=embed" 
            width="100%" 
            height="300" 
            style="border:2px solid var(--accent); border-radius: 5px; margin-top: 10px;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </section>
</div>