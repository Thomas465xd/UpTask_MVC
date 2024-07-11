<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/cambiar-password" class="enlace">Cambiar Password</a>

    <form action="/perfil" method="POST" class="formulario">

        <div class="campo">
            <label for="nombre">Nombre</label>
            <input
                type="text"
                id="nombre"
                value="<?php echo s($usuario->nombre); ?>"
                name="nombre"
                placeholder="Tu Nombre"
            >
        </div>

        <div class="campo">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                value="<?php echo s($usuario->email); ?>"
                name="email"
                placeholder="Tu Email"
            >
        </div>

        <input type="submit" class="boton" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>