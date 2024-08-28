<header style="background-color:rgb(64, 65, 64);" id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="<?php $rutaServerinicio ?>" class="logo d-flex align-items-center">
                <?php echo '<img src="'.$rutaServer.'img/viaticos.png" alt="">'; ?>
                <span style="color:white;" class="d-none d-lg-block">Control de Viáticos</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <i style="color: #d90000;" class="bi bi-person"></i>
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['Nombre_Usuario'] ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile" style="background-color: #404140; border: 2px solid #A19F9F;">
                        <li class="dropdown-header">
                            <h6 style="color: white;"><?php echo $_SESSION['Nombre_Usuario'] ?></h6>
                            <span style="color: white;">DEPENDENCIA <?php echo $_SESSION['Dependencia'] ?></span>
                        </li>
                        <li style="color: white;">
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                                <i style="color:#d90000" class="bi bi-person"></i>
                                <span style="color: white;">Perfil</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <!-- <li>
                            <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                                <i class="bi bi-gear"></i>
                                <span style="color: white;">Ajustes</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li> -->

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                                <i style="color:#d90000" class="bi bi-question-circle"></i>
                                <span style="color: white;">Ayuda</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" id="cerrarSesion">
                                <i style="color:#d90000" class="bi bi-box-arrow-right"></i>
                                <span style="color: white;">Cerrar sesión</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->