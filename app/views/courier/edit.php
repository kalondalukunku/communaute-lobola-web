<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>
<section class="mb-5">
    <div class="container">
        
        <?php include APP_PATH . 'templates/alertView.php'; ?>

        <div class="row">
            <main class="form-signin m-auto"> 
                
                <p class="text-center mb-4">
                    <a class="text-inherit border-bottom border-info" href="/">Acceuil</a> / 
                    <a class="text-inherit border-bottom border-info" href="<?= RETOUR_EN_ARRIERE ?>">Courier de : <?= $courier->provenance ?></a> / 
                    <a class="text-info" href="#">Modifier le courrier</a> 
                </p>
                <h1 class="h3 mb-2 text-center fw-bold text-info">Modifier le document</h1> 

                <form method="post" enctype="multipart/form-data">
                    <p class="text-center mb-4">
                        Modifiez les informations du courier ci-dessous, puis enregistrez les changements.
                    </p>

                    <div class="row g-2">
                        <?php if($courier->category === 'sortant'): ?>
                            <div class="col-md form-floating mb-2"> 
                                <input type="number" name="moratoire" class="form-control" id="floatingInput" value="<?= $data['moratoire'] ?? '' ; ?>"> 
                                <label for="floatingInput">Moratoire (en heures)</label> 
                            </div> 
                        <?php endif; ?>
                        
                        <?php if($courier->type === 'interne'): ?>
                            <div class="col-md form-floating mb-2"> 
                                <input type="text" name="destination" class="form-control" id="floatingInput" value="<?= $data['destination'] ?? '' ; ?>"> 
                                <label for="floatingInput">Destination</label> 
                            </div> 
                        <?php endif; ?>

                    </div>

                    <div class="row g-2">
                        <div class="col-md form-floating mb-2"> 
                            <input type="text" name="provenance" class="form-control" id="floatingInput" value="<?= $data['provenance'] ?? '' ; ?>" required> 
                            <label for="floatingInput">Provenance</label> 
                        </div> 

                        <div class="col-md form-floating mb-3"> 
                            <input type="text" name="objet" class="form-control" id="floatingPassword" value="<?= $data['objet'] ?? '' ; ?>" placeholder="Password" required> 
                            <label for="floatingPassword">Objet</label> 
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md form-floating mb-2"> 
                            <input type="text" name="ref_num" class="form-control" id="floatingInput" value="<?= $data['ref_num'] ?? '' ; ?>" required> 
                            <label for="floatingInput">Numéro de référence</label> 
                        </div> 
                        <?php if($courier->category === 'entrant'): ?>
                            <div class="col-md form-floating mb-2"> 
                                <input type="text" name="reception_num" class="form-control" id="floatingInput" value="<?= $data['reception_num'] ?? '' ; ?>"> 
                                <label for="floatingInput">Numéro de réception</label> 
                            </div> 
                        <?php endif; ?>
                    </div>

                    <div class="row g-1">
                        <div class="col-md form-floating mb-2"> 
                            <textarea name="motif" class="form-control" id="floatingTextarea" value="<?= $data['conmotifnect'] ?? '' ; ?>" required style="height: 120px;" ></textarea> 
                            <label for="floatingTextarea">Motif de modification</label> 
                        </div>
                    </div>                  
                    
                    <button class="btn btn-info w-100 rounded-pill py-2 px-3" name="bukus_edit_courier" type="submit">
                        Modifier
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen mb-1" viewBox="0 0 16 16">
                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                        </svg>
                    </button> 
                </form> 
            </main>
        </div>
    </div>
</section>


<?php include APP_PATH . 'views/layouts/footer.php'; ?>