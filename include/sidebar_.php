<!-- ======= Sidebar ======= -->
<aside style="background-color:rgb(64, 65, 64);" id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-heading"><?php echo $_SESSION['rol_viaticos'] ?></li>

        <!-- <li class="nav-item">
            <?php echo '<a class="nav-link collapsed" href="' . $rutaServer . 'inicio.php">'; ?>
            <i class="bi bi-person"></i>
            <span>Inicio</span>
            </a>
        </li> -->
        <!-- Fin Inicio Page Nav -->

        <?php if ($_SESSION['role'] == "1" || $_SESSION['role'] == "2") { ?>
            
            <li class="nav-item">
                <?php echo '<a class="nav-link collapsed" href="' . $rutaServer . 'administracion/RegistroSolicitud/registroSolicitud.php' . '">'; ?>
                <i class="bi bi-pen"></i>
                <span>Registro</span>
                </a>
            </li><!-- Fin Registrar Solicitudes Page Nav -->
            
            <li class="nav-item">
                <?php echo '<a class="nav-link collapsed" href="' . $rutaServer . 'administracion/Tarjetas/tarjetas.php">'; ?>
                <!-- <a class="nav-link collapsed" href="/administracion/MonitorSolicitudes/monitorSolicitudes.php"> -->
                <i class="bi bi-credit-card"></i>
                <span>Monederos</span>
                </a>
            </li><!-- Fin Monederos Page Nav -->

            <li class="nav-item">
                <?php echo '<a class="nav-link collapsed" href="' . $rutaServer . 'administracion/AplicacionPagos/aplicacionPagos.php">'; ?>
                <i class="bi bi-pen"></i>
                <span>Dispersión</span>
                </a>
            </li><!-- Fin Dispersión Page Nav -->

            <li class="nav-item">
                <?php echo '<a class="nav-link collapsed" href="' . $rutaServer . 'administracion/Monederos/monederos.php">'; ?>
                <!-- <a class="nav-link collapsed" href="Administracion/lineasCredito.php"> -->
                <i class="bi bi-credit-card-2-back"></i>
                <span>Monitor Monederos</span>
                </a>
            </li><!-- End Monitor Monederos Page Nav -->

            <li class="nav-item">
                <?php echo '<a class="nav-link collapsed" href="' . $rutaServer . 'administracion/Consumos/consumos.php">'; ?>
                <!-- <a class="nav-link collapsed" href="Administracion/consumos.php"> -->
                <i class="bi bi-coin"></i>
                <span>Consumos</span>
                </a>
            </li><!-- End Consumos Page Nav -->

        <?php } ?>

        <?php if ($_SESSION['role'] == "3") { ?>
            
            <li class="nav-item">
                <?php echo '<a class="nav-link collapsed" href="' . $rutaServer . 'administracion/Tarjetas/tarjetas.php">'; ?>
                <!-- <a class="nav-link collapsed" href="/administracion/MonitorSolicitudes/monitorSolicitudes.php"> -->
                <i class="bi bi-credit-card"></i>
                <span>Monederos</span>
                </a>
            </li><!-- Fin Monederos Page Nav -->

            <li class="nav-item">
                <?php echo '<a class="nav-link collapsed" href="' . $rutaServer . 'administracion/Monederos/monederos.php">'; ?>
                <!-- <a class="nav-link collapsed" href="Administracion/lineasCredito.php"> -->
                <i class="bi bi-credit-card-2-back"></i>
                <span>Monitor Monederos</span>
                </a>
            </li><!-- End Monitor Monederos Page Nav -->

            <li class="nav-item">
                <?php echo '<a class="nav-link collapsed" href="' . $rutaServer . 'administracion/Consumos/consumos.php">'; ?>
                <!-- <a class="nav-link collapsed" href="Administracion/consumos.php"> -->
                <i class="bi bi-coin"></i>
                <span>Consumos</span>
                </a>
            </li><!-- End Consumos Page Nav -->

        <?php } ?>
    </ul>

</aside><!-- End Sidebar-->