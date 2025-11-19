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
                                <a class="nav-link modern-link <?= Helper::setActive('settings') ?>" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Paramètres
                                </a>
                                
                                <ul class="dropdown-menu dropdown-menu-end modern-dropdown shadow-sm"
                                    aria-labelledby="settingsDropdown">

                                    <!-- <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bi bi-person me-2"></i>
                                            Profil
                                        </a>
                                    </li> -->
                                    <li>
                                        <a class="dropdown-item" href="/user">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-lines-fill me-2 my-auto" viewBox="0 0 16 16">
                                                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                                            </svg>
                                            Liste des utilisateurs
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/user/editpswd">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen me-2 my-auto" viewBox="0 0 16 16">
                                                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                                            </svg>
                                            Modifier le mot de passe
                                        </a>
                                    </li>
                                    <!-- <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bi bi-shield-check me-2"></i>
                                            Sécurité
                                        </a>
                                    </li> -->
                                    <!-- <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bi bi-bell me-2"></i>
                                            Notifications
                                        </a>
                                    </li> -->

                                    <li><hr class="dropdown-divider"></li>

                                    <li class="px-3">
                                        <a class="dropdown-item text-center text-danger border border-danger rounded-pill" href="/logout">
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

        <!-- <div class="offcanvas offcanvas-start border-end border-1 border-info rounded-end rounded-3" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
            <div class="offcanvas-header">
                <div class="d-flex">
                    <a href="/" class="d-flex align-items-center mb-1 mb-lg-0 text-decoration-none mx-2 my-auto"> 
                        <img class="" src="<?= ASSETS ?>images/logo.png" width="25" alt="">
                    </a>
                    <span class="offcanvas-title text-capitalize fw-bold my-auto" id="offcanvasExampleLabel"><?= SITE_NAME ?></span>
                </div>
                
                <a href="<?= RETOUR_EN_ARRIERE ?>" class="btn btn-sm btn-info border-dark rounded-pill mx-auto my-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-arrow-left-circle mb-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                    </svg>
                    Retour
                </a>
                
                <button type="button" class="btn-close text-info ms-0 ps-0 my-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 pb-3">
                    <?php if($_SESSION[SITE_NAME_SESSION_USER]['role'] === ARRAY_ROLE_USER[0]): ?>
                        <li class="nav-item d-flex <?= Helper::setActive('/') ?><?= Helper::setActive('/index') ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house me-2 my-auto" viewBox="0 0 16 16">
                                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
                            </svg>
                            <a class="nav-link active my-auto" aria-current="page" href="/">Global</a>
                        </li>
                        <li class="nav-item d-flex <?= Helper::setActive('/rapport') ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-earmark-pdf me-2 my-auto" viewBox="0 0 16 16">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                                <path d="M4.603 14.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.7 11.7 0 0 0-1.997.406 11.3 11.3 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.245.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 7.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/>
                            </svg>                      
                            <a class="nav-link my-auto" href="/rapport">Rapport</a>
                        </li>
                        <li class="nav-item d-flex <?= Helper::setActive('/user') ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-lines-fill me-2 my-auto" viewBox="0 0 16 16">
                                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                            </svg>
                            <a class="nav-link my-auto" href="/user">Liste des utilisateurs</a>
                        </li>
                        <li class="nav-item d-flex <?= Helper::setActive('/user/editpswd') ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen me-2 my-auto" viewBox="0 0 16 16">
                                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                            </svg>
                            <a class="nav-link my-auto" href="/user/editpswd">Modifier votre mot de passe</a>
                        </li>
                    <?php elseif($_SESSION[SITE_NAME_SESSION_USER]['role'] === ARRAY_ROLE_USER[2]): ?>
                        <li class="nav-item d-flex <?= Helper::setActive('/courier/create') ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-lines-fill me-2 my-auto" viewBox="0 0 16 16">
                                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                            </svg>
                            <a class="nav-link my-auto" href="/courier/create">Ajouter un courrier</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="mt-5">
                    <a href="/logout" class="btn btn-danger w-100 border-light rounded-pill" class="btn btn-danger">Déconnexion</a>
                </div>
            </div>
        </div> -->
 