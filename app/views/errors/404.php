<?php 
  include APP_PATH . 'views/layouts/header.php';  
?>

<section class="d-flex align-items-center vh-100">
    <div class="container">
        <div class="row">
            <div class="text-center">
                <h2 class="text-info fw-bold">Page introuvable</h2>
                <img src="<?= ASSETS ?>svg/404.svg" width="35%" alt="" srcset="">
            </div>

            <a href="/" class="w-50 btn btn-info border-dark mx-auto rounded-pill">Retourner à l'acceuil</a>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>