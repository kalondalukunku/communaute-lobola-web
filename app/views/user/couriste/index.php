<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>

<section class="section-top">
    <div class="container">
        
        <?php include APP_PATH . 'templates/alertView.php'; ?>
        
        <div class="row mb-4">
            <a href="add_courier" class="btn btn-info w-75 mx-auto rounded-pill">Ajouter un courier</a>
        </div>

        <div class="row">
            <div class="row col-11 mx-auto g-1">
                <h5>Courier ajouté</h5>

                <div class="mb-4"> 
                    <div class="card border-info px-2 rounded-4 shadow">
                        <div class="card-header bg-transparent border-bottom-0 pb-0 px-4 d-flex justify-content-between">
                            <div class="my-auto">
                                <img src="<?= BASE_PATH_ICON ?>" width="20" alt="">
                            </div>
                            <div class="date_limite" data-datetime="<?= $courier->status === 'en cours' ? $courier->date_limite : null ?>" class="my-auto text-<?= Helper::courierColors($Couriers->showMoratoire($courier->date_limite, $courier->status)) ?>" style="font-size: 12px;">
                                <?= $Couriers->showMoratoire($courier->date_limite, $courier->status) ?>
                            </div>
                        </div>
                        <div class="card-body pt-2 rounded-bottom-4">
                            <div class="row">
                                <div class="col-3 my-auto text-center px-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-file-earmark-pdf text-secondary mx-auto" viewBox="0 0 16 16">
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                                        <path d="M4.603 14.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.7 11.7 0 0 0-1.997.406 11.3 11.3 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.245.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 7.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/>
                                    </svg>
                                </div>
                                
                                <div class="col-9 my-auto ps-0" style="font-size: 14px;">
                                    Prov. : <span class=""><?= Helper::formatText2($courier->provenance) ?></span><br>
                                    Objet. : <span class=""><?= Helper::formatText2($courier->objet) ?></span><br>
                                    Réf : <span class=""><?= Helper::formatText2($courier->ref_num) ?></span><br>
                                    <?php if($courier->reception_num !== null): ?>
                                        Récep : <span class=""><?= Helper::formatText2($courier->reception_num) ?></span><br>
                                    <?php endif; ?>    
                                    catégorie : <span class=""><?= Helper::formatText2($courier->category) ?></span><br>
                                    <?php if($courier->type !== null): ?>
                                        Type : <span class=""><?= Helper::formatText2($courier->type) ?></span><br>
                                    <?php endif; ?>
                                    Etat : <span class=""><?= Helper::formatText2($courier->status) .'  '.  Helper::stateSpinner($courier) ?></span><br>
                                    <?php if($courier->status === 'traité'): ?>
                                        <div class="mt-2 fw-bold fs-6 text-info">Classement</div>
                                        Dossier : <span class=""><?= Helper::formatText2($courier->dossier_classee) ?></span><br>
                                        Date. : <span class="">Le <?= Helper::formatDate($courier->date_classement) ?></span><br>
                                    <?php endif; ?>
                                    Enregistré par : <span class=""><?= Helper::formatText2($courier->saved_by) ?></span><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>