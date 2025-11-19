<?php 
    $title = "Admin | Connexion";
    include APP_PATH . 'views/layouts/header.php'; 
?>

<div class="contact-us section" id="contact">
    <div class="container">
        <div class="row">

            <?php include APP_PATH . 'templates/alertView.php'; ?>

            <div class="col-lg-6  align-self-center">
                <div class="section-heading">
                    <a href="/">
                        <i class="fa fa-angle-left"></i>
                        Retour
                    </a>
                    <h2>Connexion</h2>
                    <p class="my-3">Thank you for choosing our templates. We provide you best CSS templates at absolutely 100% free of charge. You may support us by sharing our website to your friends.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="contact-us-content">
                    <form id="contact-form" action="" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <fieldset>
                                <input type="text" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Votre E-mail..." >
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                <input type="password" name="password" id="password" placeholder="Mot de passe" autocomplete="off" >
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <button type="submit" name="csak_connexion" id="form-submit" class="orange-button">Se connecter</button>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>