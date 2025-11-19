
  <!-- ***** Header Area Start ***** -->
  <header class="header-area header-sticky background-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="index.html" class="logo">
                        <h1><?= SITE_NAME ?></h1>
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Serach Start ***** -->
                    <!-- <div class="search-input">
                      <form id="search" action="#">
                        <input type="text" placeholder="Type Something" id='searchText' name="searchKeyword" onkeypress="handle" />
                        <i class="fa fa-search"></i>
                      </form>
                    </div> -->
                    <!-- ***** Serach Start ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                      <li class="scroll-to-section"><a href="admin/dashboard" class="<?= Helper::setActive('admin') ?>">Tableau de bord</a></li>
                      <li class="scroll-to-section"><a href="events" class="<?= Helper::setActive('events') ?>">Evénements</a></li>
                      <li class="scroll-to-section"><a href="events" class="<?= Helper::setActive('courses') ?>">Enseignements</a></li>
                      <!-- <li class="scroll-to-section"><a href="#courses">Blog de réflexions</a></li> -->
                      <!-- <li class="scroll-to-section"><a href="#events">Agénda</a></li> -->
                      <!-- <li class="scroll-to-section"><a href="#contact">Réjoindre !</a></li> -->
                      <li class="scroll-to-section">
                        <a href="events/create" class="button-secondary text-center p-3" style="line-height: 10px !important;">Déconnexion</a>
                      </li>
                      
                  </ul>   
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
  </header>