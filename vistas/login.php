<section style="max-width: 450px; margin: 40px auto; background: var(--white); padding: 30px; border-radius: 10px; box-shadow: var(--shadow);">
    <form id="form-login" onsubmit="return Controlador.procesarLogin(event)">
        <h2 data-i18n="login_tit" style="text-align:center; color: var(--primary);">Iniciar Sesión</h2>
        
        <label data-i18n="lbl_usuario">Usuario o Correo:</label>
        <input type="text" name="usuario" required>
        
        <label data-i18n="lbl_pass">Contraseña:</label>
        <input type="password" name="password" autocomplete="current-password" required>
        
        <label data-i18n="lbl_mfa">Seguridad (MFA):</label>
        <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" style="margin-bottom: 10px;"></div>
        <span id="err-captcha" style="color:red; display:none; margin-bottom: 15px; font-size: 0.9rem;">Confirma que no eres un robot.</span>

        <button type="submit" class="btn" data-i18n="btn_ingresar">Ingresar</button>
        
        <p style="text-align:center; margin-top:15px;">
            <a href="#" onclick="document.getElementById('modal-recuperar').style.display='block'" data-i18n="recuperar_link">¿Olvidaste tu contraseña?</a>
        </p>
    </form>

    <div id="modal-recuperar" style="display:none; margin-top: 20px; padding-top: 20px; border-top: 2px dashed var(--accent);">
        <h4 data-i18n="recuperar_tit">Recuperar Contraseña</h4>
        <p style="font-size: 0.9rem; color: #666;" data-i18n="recuperar_desc">Ingresa tu correo para enviarte un enlace de seguridad.</p>
        <form onsubmit="return Controlador.simularRecuperacion(event)">
            <input type="email" id="correo-recuperacion" required>
            <button type="submit" id="btn-recuperar" class="btn" style="margin-top: 10px;" data-i18n="btn_recuperar">Enviar Enlace</button>
        </form>
        <div id="msg-recuperacion" style="display:none; margin-top: 15px; padding: 10px; background: #E8F5E9; color: #2E7D32; border-radius: 5px; text-align: center; font-weight: bold;"></div>
    </div>
</section>