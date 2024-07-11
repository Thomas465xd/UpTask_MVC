<?php include_once __DIR__ . '/header-dashboard.php'; ?>

    <?php if(count($proyectos) === 0) { ?>

        <p class="no-proyectos">No Hay Proyectos Aún 
            <a href="/crear-proyecto">Comienza Creando Uno</a>
        </p>

    <?php } else { ?>

        <ul class="listado-proyectos">
            <?php foreach($proyectos as $proyecto) { ?>
                <li class="proyecto">
                    <a href="/proyecto?id=<?php echo $proyecto->url ?>">
                        <?php echo $proyecto->proyecto; ?>
                    </a>
                    <form action="/proyecto/eliminar" method="POST" class="formulario-eliminar">
                        <input type="hidden" name="id" value="<?php echo $proyecto->id ?>">
                        <button type="submit" class="btn-eliminar">
                            <i class="fa-regular fa-circle-xmark"></i>
                        </button>
                    </form>
                </li>
            <?php } ?>
        </ul>

    <?php } ?>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>

<?php
    $script = "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='build/js/app.js'></script>
    "
?>