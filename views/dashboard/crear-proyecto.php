<!-- <div class="dashboard">
    //php include_once __DIR__ . '/../templates/sidebar.php' ?>

    <div class="principal">
        php include_once __DIR__ . '/../templates/barra.php' ?>
        <div class="contenido">
            <h1 class="nombre-pagina"> <php echo $titulo ?> </h1>
        </div>
    </div>
</div> -->

<?php include_once __DIR__ . '/header-dashboard.php';?> 

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php';?>
    <form class="formulario">
        <?php include_once __DIR__ .'/formulario-proyecto.php';    ?>
        <input type="submit" value="Crear Proyecto">
    </form> 
</div>

<?php include_once __DIR__ . '/footer-dashboard.php';?>