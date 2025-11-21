        <!-- Navbar Premium -->
        <nav class="navbar navbar-expand-lg modern-navbar fixed-top">
            <div class="container">

                <!-- Logo -->
                <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
                    <i class="bi bi-cursor-fill me-2 fs-4 text-info"></i>
                    Ankhing <span class="text-info">Bukus</span>
                </a>

                <!-- Toggler -->
                <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menu -->
                <div class="collapse navbar-collapse" id="navMenu">
                    <ul class="navbar-nav ms-auto gap-3 align-items-lg-center">

                        <?php if($_SESSION[SITE_NAME_SESSION_USER]['role'] === ARRAY_ROLE_USER[0]): ?>
                            <li class="nav-item">
                                <a class="nav-link modern-link <?= Helper::setActive('') ?><?= Helper::setActive('/index') ?>" href="/">
                                    Global
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link modern-link <?= Helper::setActive('rapport') ?>" href="/rapport">
                                    Rapport
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link modern-link <?= Helper::setActive('settings') ?>" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer !important;">
                                    Paramètres
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-double-down" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1.646 6.646a.5.5 0 0 1 .708 0L8 12.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
                                        <path fill-rule="evenodd" d="M1.646 2.646a.5.5 0 0 1 .708 0L8 8.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
                                    </svg>
                                </a>
                                
                                <ul class="dropdown-menu dropdown-menu-end modern-dropdown shadow-sm"
                                    aria-labelledby="settingsDropdown">

                                    <!-- <li>
                                        <a class="dropdown-item" href="/" href="#">
                                            <i class="bi bi-person me-2"></i>
                                            Profil
                                        </a>
                                    </li> -->
                                    <li>
                                        <a class="dropdown-item" href="/user" >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-lines-fill me-2 my-auto" viewBox="0 0 16 16">
                                                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                                            </svg>
                                            Liste des utilisateurs
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/user/editpswd" >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen me-2 my-auto" viewBox="0 0 16 16">
                                                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                                            </svg>
                                            Modifier le mot de passe
                                        </a>
                                    </li>
                                    <!-- <li>
                                        <a class="dropdown-item" href="/" href="#">
                                            <i class="bi bi-shield-check me-2"></i>
                                            Sécurité
                                        </a>
                                    </li> -->
                                    <!-- <li>
                                        <a class="dropdown-item" href="/" href="#">
                                            <i class="bi bi-bell me-2"></i>
                                            Notifications
                                        </a>
                                    </li> -->

                                    <li><hr class="dropdown-divider"></li>

                                    <li class="px-3">
                                        <a class="dropdown-item  href="/"text-center text-danger border border-danger rounded-pill" href="/logout">
                                            Déconnexion
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                        <?php elseif($_SESSION[SITE_NAME_SESSION_USER]['role'] === ARRAY_ROLE_USER[2]): ?>
                            <li class="nav-item">
                                <a class="nav-link modern-link <?= Helper::setActive('courier/create') ?>" href="/courier/create">
                                    Ajouter un courier
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="btn btn-danger rounded-pill px-4 shadow-sm hover-grow" href="/logout">
                                    Déconnexion
                                </a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>
            </div>
        </nav>