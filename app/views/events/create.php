<?php 
    $title = "Ajouter un événement";
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php'; 
?>

    <div class="mt-3">CSAK</div>

    <div class="contact-us section" id="contact">
        <div class="container">
            <div class="row">

                <?php include APP_PATH . 'templates/alertView.php'; ?>

                <div class="col-lg-10 mx-auto  align-self-center">
                    <div class="section-heading">
                        <h2 class="mb-1">Créer un événement</h2>
                        <p class="mb-5">Thank you for choosing our templates. We provide you best CSS templates at absolutely 100% free of charge. You may support us by sharing our website to your friends.</p>
                    </div>
                    <div class="contact-us-content">
                        <form id="contact-form" action="" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset>
                                        <div class="w-50 preview-upload-image text-center rounded-5 py-3 mx-auto" id="previewUploadImage">
                                            <img src="<?= BASE_URL ; ?>/assets/images/event-01.jpg" alt="" srcset="">
                                        </div> 
                                        <label class="w-100 text-center text-white rounded-pill py-3" for="uploadImage" style="background-color: rgba(249, 235, 255, 0.15); margin-bottom: 30px; font-size: 14px; cursor: pointer;">
                                            Importez une image
                                        </label>
                                        <input type="file" name="image" id="uploadImage" placeholder="Titre" accept="image/*" hidden value="<?= $data['image'] ?? '' ; ?>">
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <input type="text" name="title" id="title" placeholder="Titre" value="<?= $data['title'] ?? '' ; ?>">
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <input type="text" name="objet" id="objet" placeholder="Objet" value="<?= $data['objet'] ?? '' ; ?>" >
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <input type="date" name="date" id="date" placeholder="Date" value="<?= $data['date'] ?? '' ; ?>" >
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <input type="number" name="duration" id="duration" placeholder="Durée" value="<?= $data['duration'] ?? '' ; ?>" >
                                    </fieldset>
                                </div>
                                <div class="col-md-12">
                                    <fieldset>
                                        <input type="text" name="emplacement" id="emplacement" placeholder="Emplacement" value="<?= $data['emplacement'] ?? '' ; ?>" >
                                    </fieldset>
                                </div>
                                <div class="col-md-12">
                                    <fieldset>
                                        <textarea name="description" id="description" placeholder="Description"><?= $data['description'] ?? '' ; ?></textarea>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <button type="submit" name="csak_create_event" id="form-submit" class="orange-button">Créer un événement</button>
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