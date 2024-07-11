<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace">Volver al Perfil</a>

    <form action="/cambiar-password" method="POST" class="formulario">

        <div class="campo">
            <label for="nombre">Password Actual</label>
            <input
                type="password"
                id="password_actual"
                value=""
                name="password_actual""
                placeholder="Tu Password Actual"
            >
        </div>

        <div class="campo">
            <label for="nombre">Password Nuevo</label>
            <input
                type="password"
                id="password"
                value=""
                name="password_nuevo"
                placeholder="Tu Nuevo Password"
            >
        </div>

        <input type="submit" class="boton" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>