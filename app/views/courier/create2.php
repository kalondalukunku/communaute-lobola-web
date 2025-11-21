<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>

<section class="section-top">
    <div class="container mb-5">
        
        <?php include APP_PATH . 'templates/alertView.php'; ?>

        <div class="row">
            <main class="form-signin m-auto"> 
                <form method="post" enctype="multipart/form-data">

                    <h1 class="h3 mb-4 text-center fw-bold text-info">Complétez le courrier</h1> 
                    <p class="text-center mb-4">
                        Veuillez renseigner les informations complémentaires du courrier pour finaliser son enregistrement
                    </p>

                    <div class="row g-2">
                        <?php if($courier->category === 'sortant'): ?>
                            <div class="col-md form-floating mb-2"> 
                                <input type="number" name="moratoire" class="form-control" id="floatingInput" value="<?= $data['moratoire'] ?? '' ; ?>" placeholder="name@example.com"> 
                                <label for="floatingInput">Moratoire (en heures)</label> 
                            </div> 
                        <?php endif; ?>
                        
                        <?php if($courier->type === 'interne'): ?>
                            <div class="col-md form-floating mb-2"> 
                                <input type="text" name="destination" class="form-control" id="floatingInput7" value="<?= $data['destination'] ?? '' ; ?>" placeholder="name@example.com"> 
                                <label for="floatingInput7">Destination</label> 
                            </div> 
                        <?php endif; ?>

                    </div>

                    <div class="row g-2">
                        <div class="col-md form-floating mb-2"> 
                            <input type="text" name="provenance" class="form-control" id="floatingInput1" value="<?= $data['provenance'] ?? '' ; ?>" placeholder="name@example.com" required> 
                            <label for="floatingInput1">Provenance</label> 
                        </div> 

                        <div class="col-md form-floating mb-3"> 
                            <input type="text" name="objet" class="form-control" id="floatingPassword" value="<?= $data['objet'] ?? '' ; ?>" placeholder="Password" required> 
                            <label for="floatingPassword">Objet</label> 
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md form-floating mb-2"> 
                            <input type="text" name="ref_num" class="form-control" id="floatingInput2" value="<?= $data['ref_num'] ?? '' ; ?>" placeholder="name@example.com" required> 
                            <label for="floatingInput2">Numéro de référence</label> 
                        </div> 
                            
                        <div class="col-md form-floating mb-2">
                            <select class="form-select" id="selectPriority" name="priority" aria-label="Floating label select example">
                                <?php foreach(ARRAY_PRIORITY as $priority): ?>
                                    <option id="<?= $priority ?>ID" value="<?= $priority ?>" <?= $data['priority'] ?? '' ; ?> ><?= $priority ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="selectPriority">Selectionnez la priorité du courrier</label>
                        </div> 
                    </div>

                    <div class="row g-2">

                        <?php if($courier->category === 'entrant' && count($dbRecepNum) === 0) : ?>
                            <div class="col-md form-floating mb-2"> 
                                <input type="number" name="reception_num" class="form-control" id="floatingInput3" value="<?= $data['reception_num'] ?? '' ; ?>" placeholder="name@example.com" required> 
                                <label for="floatingInput3">N° réception</label> 
                            </div> 
                        <?php endif; ?>
                        
                        <?php if($courier->category === 'sortant' && $courier->type === 'interne') : ?>
                            <div class="col-md form-floating mb-2">
                                <select class="form-select" id="selectTransmission" name="transmission" aria-label="Floating label select example">
                                    <?php foreach(ARRAY_TRANSMISSION as $transmission): ?>
                                        <option id="<?= str_replace(' ','_',$transmission) ?>" value="<?= $transmission ?>" <?= $data['transmission'] ?? '' ; ?> ><?= $transmission ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="selectTransmission">Selectionnez la transmission</label>
                            </div> 
                        <?php endif; ?>
                    </div>  
                    
                    <?php if($courier->category === ARRAY_CATEGORIES[1] && $courier->type === ARRAY_TYPE[0]) : ?>
                        <div class="row g-2" id="divInputsDate">
                            <div class="col-md form-floating mb-2"> 
                                <input type="date" name="date_depart" class="form-control" id="floatingInput4" value="<?= $data['date_depart'] ?? '' ; ?>" placeholder="name@example.com" > 
                                <label for="floatingInput4">Date de départ</label> 
                            </div>
                            <div class="col-md form-floating mb-2"> 
                                <input type="date" name="date_retour" class="form-control" id="floatingInput5" value="<?= $data['date_retour'] ?? '' ; ?>" placeholder="name@example.com" > 
                                <label for="floatingInput5">Date de retour</label> 
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row g-2">
                        <div class="col-md form-floating mb-2"> 
                            <input type="datetime-local" name="date_reception" class="form-control" id="floatingInput6" value="<?= $data['date_reception'] ?? '' ; ?>" placeholder="name@example.com" required> 
                            <label for="floatingInput6">Date & heure de réception</label> 
                        </div>
                    </div>
                    
                    <button class="w-100 text-center primary-btn mt-2" name="bukus_create2_courier" type="submit">Terminer</button> 
                </form> 
            </main>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>