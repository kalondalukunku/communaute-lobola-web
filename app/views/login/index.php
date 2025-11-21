<?php 
    $title = "Connexion";
    include APP_PATH . 'views/layouts/header.php'; 
?>


<section class="d-flex align-items-center vh-100">
    <div class="container">

        <?php include APP_PATH . 'templates/alertView.php'; ?>

        <div class="row">
            <main class="form-signin col-10 col-md-6 m-auto"> 
                <form method="post"> 
                    <div class="text-center">
                        <img class="mb-3" src="<?= ASSETS ; ?>images/logo.png" alt="" width="72" height="57"> 
                    </div>
                    <h1 class="section-title-2 text-center mt-2">Connexion</h1> 
                    <div class="form-floating mb-2"> 
                        <input type="text" name="connect" class="form-control" id="floatingInput" value="<?= $data['connect'] ?? '' ; ?>" placeholder="name@example.com" required> 
                        <label for="floatingInput">Nom ou adresse mail</label> 
                    </div> 

                    <div class="form-floating mb-3"> 
                        <input type="password" name="pswd" class="form-control" id="floatingPassword" value="" placeholder="Mot de passe" required> 
                        <label for="floatingPassword">Mot de passe</label> 
                    </div>

                    <button class="w-100 text-center primary-btn" name="bukus_user_login" type="submit">Connexion</button> 
                </form> 
            </main>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>