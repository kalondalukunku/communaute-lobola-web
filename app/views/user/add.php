<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>

<section class="section-top">
    <div class="container">
        
        <?php include APP_PATH . 'templates/alertView.php'; ?>

        <div class="row">
            <main class="form-signin m-auto"> 
                <form method="post" enctype="multipart/form-data">

                    <h1 class="h3 mb-4 text-center fw-bold text-info">Ajouter un couriste</h1> 
                    <p class="text-center mb-4">
                        Veuillez insérer le nom complet du couriste, son adresse mail et le mot de passe pour lui accorder l'accès limité 
                        dans le logiciel.
                    </p>

                    <div class="row g-2">
                        <div class="col-md form-floating mb-2"> 
                            <input type="text" name="nom" class="form-control" id="floatingInput" value="<?=  Helper::getData($_POST, 'nom') ?>" placeholder="John Doe" required <?= $couristeUsersText ?>> 
                            <label for="floatingInput">Nom complet</label> 
                        </div> 

                        <div class="col-md form-floating mb-3">
                            <select class="form-select" id="selectRole" name="role" aria-label="Floating label select example" required <?= $couristeUsersText ?>>
                                <?php foreach(array_diff(ARRAY_ROLE_USER, ['admin']) as $role): ?>
                                    <option id="<?= $role ?>ID" value="<?= $role ?>" <?= Helper::getSelectedValue('role', $role) ?> ><?= $role ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="selectRole">Selectionnez la catégorie du courier</label>
                        </div>
                    </div> 
                    
                    <div class="row g-2">
                        <div class="col-md form-floating mb-3"> 
                            <input type="email" name="email" class="form-control" id="floatingPassword" value="<?=  Helper::getData($_POST, 'email') ?>" placeholder="Password" required <?= $couristeUsersText ?>> 
                            <label for="floatingPassword">Adresse mail</label> 
                        </div>
                        
                        <div class="col-md form-floating mb-3"> 
                            <input type="password" name="pswd" class="form-control" id="floatingPassword" value="<?=  Helper::getData($_POST, 'pswd') ?>" placeholder="Password" required <?= $couristeUsersText ?>> 
                            <label for="floatingPassword">Mot de passe</label> 
                        </div>
                    </div> 
                    <?php if($nbrCouristeUsers >= NBR_LIMITE_USER_COURISTE): ?>
                        <a href="../user" class="btn btn-dark text-info border-info  w-100 py-2">Voir la liste des utilisateurs</a> 
                    <?php else: ?>
                        <button class="btn btn-info w-100 rounded-pill py-2" name="bukus_user_add" type="submit">Ajouter l'utilisateur</button>
                    <?php endif; ?>
                </form> 
            </main>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>