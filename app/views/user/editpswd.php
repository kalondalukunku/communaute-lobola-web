<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>

<section class="mb-5">
    <div class="container">
        
        <?php include APP_PATH . 'templates/alertView.php'; ?>

        <div class="row">
            <main class="form-signin m-auto"> 
                <form method="post" enctype="multipart/form-data">

                    <h1 class="h3 mb-4 text-center fw-bold text-info">Modifier votre mot de passe</h1> 
                    <p class="text-center mb-4">
                        Modifiez votre mot de passe, puis enregistrez les changements.
                    </p>

                    <div class="row g-2">
                        <div class="form-floating mb-3"> 
                            <input type="password" name="old_pswd" class="form-control" id="floatingPassword" value="<?=  Helper::getData($_POST, 'old_pswd') ?>" placeholder="Password" required> 
                            <label for="floatingPassword">Ancien mot de passe</label> 
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="form-floating mb-3"> 
                            <input type="password" name="new_pswd" class="form-control" id="floatingPassword" value="<?=  Helper::getData($_POST, 'new_pswd') ?>" placeholder="Password" required> 
                            <label for="floatingPassword">Nouveau mot de passe</label> 
                        </div> 
                            
                        <div class="form-floating mb-3"> 
                            <input type="password" name="confirm_pswd" class="form-control" id="floatingPassword" value="<?=  Helper::getData($_POST, 'confirm_pswd') ?>" placeholder="Password" required> 
                            <label for="floatingPassword">Confirmer le Nouveau mot de passe</label> 
                        </div>
                    </div>                 
                    
                    <div class="d-flex">
                        <a class="btn btn-danger w-50 border-light rounded-pill py-2 px-3 me-1" href="<?= RETOUR_EN_ARRIERE ?>">
                            Annuler
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill mb-1" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                            </svg>
                        </a>
                        <button class="btn btn-info w-50 border-dark rounded-pill py-2 px-3 ms-1" name="bukus_pswd_user_edit" type="submit">
                            Modifier
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen mb-1" viewBox="0 0 16 16">
                                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                            </svg>
                        </button>
                    </div> 
                </form> 
            </main>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>