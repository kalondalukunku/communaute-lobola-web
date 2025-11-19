<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>

<section class="section-top">
    <div class="container">
        
        <?php include APP_PATH . 'templates/alertView.php'; ?>

        <div class="row">
            <main class="form-signin m-auto"> 
                <form method="post">

                    <h1 class="h3 mb-4 text-center fw-bold text-info">Modifier l'utilisateur</h1> 
                    <p class="text-center mb-4">
                        Modifiez les informations de l'utilisateur ci-dessous, puis enregistrez les changements.
                    </p>

                    <div class="row g-2">
                        <div class="col-md form-floating mb-2"> 
                            <input type="text" name="nom" class="form-control" id="floatingInput" value="<?=  Helper::getData($_POST, 'nom',$user->nom) ?>" placeholder="name@example.com" required> 
                            <label for="floatingInput">Nom</label> 
                        </div> 

                        <div class="col-md form-floating mb-3"> 
                            <input type="text" name="email" class="form-control" id="floatingPassword" value="<?=  Helper::getData($_POST, 'email',$user->email) ?>" placeholder="Password" required> 
                            <label for="floatingPassword">Adresse mail</label> 
                        </div>
                    </div>                 
                    
                    <button class="btn btn-info w-100 rounded-pill py-2 px-3" name="bukus_user_edit" type="submit">
                        Modifier
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen mb-1" viewBox="0 0 16 16">
                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                        </svg>
                    </button> 
                </form> 
            </main>
        </div>

        <div class="text-center my-5">Ou</div>
        
        <div class="row">
            <main class="form-signin m-auto"> 
                <form method="post">

                    <h1 class="h3 mb-4 text-center fw-bold text-info">Supprimer l'utilisateur</h1> 
                    <p class="text-center mb-4">
                        Modifiez les informations de l'utilisateur ci-dessous, puis enregistrez les changements.
                    </p>                
                    
                    <button class="btn btn-danger w-100 rounded-pill py-2 px-3" name="bukus_user_delete" type="submit">
                        Supprimer
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                        </svg>
                    </button> 
                </form> 
            </main>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>