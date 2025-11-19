<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>

<section class="">
    <div class="container">
        
        <?php include APP_PATH . 'templates/alertView.php'; ?>
        
        <div class="row">
            <main class="form-signin m-auto"> 

                    <p class="text-center mb-4">
                        <a class="text-inherit border-bottom border-info" href="/">Acceuil</a> / 
                        <a class="text-inherit border-bottom border-info" href="<?= RETOUR_EN_ARRIERE ?>">Courier de : <?= $courier->provenance ?></a> / 
                        <a class="text-info" href="<?= RETOUR_EN_ARRIERE ?>">Envoyer vers</a> 
                    </p>
                    <h1 class="h3 mb-2 text-center fw-bold text-info">Courier de : <?= $courier->provenance ?></h1> 

                    
                    <form method="post" enctype="multipart/form-data"> 
                        <p>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="currentColor" class="bi bi-asterisk mb-1 text-danger" viewBox="0 0 16 16">
                                    <path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1"/>
                                </svg>
                            </span>
                            Tous les champs sont obligatoires
                        </p>

                        <div class="row g-2">
                            <div class="col-md form-floating mb-2"> 
                                <input type="text" name="nom_personne_redirigee" class="form-control" id="floatingInput" value="<?= $data['nom_personne_redirigee'] ?? '' ?>" placeholder="name@example.com" required> 
                                <label for="floatingInput">Nom du recepteur du courier</label> 
                            </div> 

                            <div class="col-md form-floating mb-2"> 
                                <input type="number" name="moratoire" class="form-control" id="floatingInput" value="<?= $data['moratoire'] ?? '' ?>" placeholder="name@example.com" required> 
                                <label for="floatingInput">Moratoire (en heures)</label> 
                            </div> 
                        </div>

                        <div class="row g-2"> 
                            <?php if(isset($_GET['id'])): ?>
                                <div class="col-md form-floating mb-2"> 
                                    <input type="text" name="motif" class="form-control" id="floatingInput" value="<?= $data['motif'] ?? '' ?>" placeholder="name@example.com"> 
                                    <label for="floatingInput">Motif</label> 
                                </div> 
                            <?php endif; ?>
                            <div class="col-md form-floating mb-2"> 
                                <input type="text" name="travail_demande" class="form-control" id="floatingInput" value="<?= $data['travail_demande'] ?? '' ?>" placeholder="name@example.com" required> 
                                <label for="floatingInput">Travail demandé</label> 
                            </div> 
                        </div>

                        <button class="btn btn-info w-100 rounded-pill py-2" name="bukus_send_courier_to" type="submit">Envoyer</button> 
                    </form> 
                    
            </main>
        </div>

    </div>
</section>



<?php include APP_PATH . 'views/layouts/footer.php'; ?>