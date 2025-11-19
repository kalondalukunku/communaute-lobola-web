<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>

<section class="section-top">
    <div class="container">
        
        <?php include APP_PATH . 'templates/alertView.php'; ?>
        
        <div class="row my-5">
            <div class="table-responsive">
                <table class="table" id="orderTable" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Provenance</th>
                            <th scope="col">Object</th>
                            <th scope="col">Réferencement</th>
                            <th scope="col">Num. réception</th>
                            <th scope="col">Catégorie</th>
                            <th scope="col">Type</th>
                            <th scope="col">Priorité</th>
                            <th scope="col">Date reçu</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($allCouriers as $courier): ?>
                            <tr class="<?= ($courier->priority === ARRAY_PRIORITY[1]) ? 'fw-bold text-info':'' ?>">
                                    <td <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                        <?= $numero ?>
                                    </td>
                                    <td <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                        <?= $courier->provenance ?>
                                    </td>
                                    <td <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                        <?= $courier->objet ?>
                                    </td>
                                    <td <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                        <?= $courier->ref_num ?>
                                    </td>
                                    <td <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                        <?= ($courier->reception_num !== null) ? $courier->reception_num : '-' ?>
                                    </td>
                                    <td <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                        <?= $courier->category ?>
                                    </td>
                                    <td <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                        <?= $courier->type ?? '-' ?>
                                    </td>
                                    <td <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                        <?= $courier->priority ?>
                                    </td>
                                    <td <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                        <?= $courier->date_reception !== null ? Helper::formatDate($courier->date_reception) : '-' ?>
                                    </td>
                                    <td <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?>  class="text-<?= Helper::courierColors($Couriers->showMoratoire($courier->date_limite, $courier->status)) ?>">
                                        <?= $courier->status ?>
                                    </td>
                                    <td <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?>  class="px-1">
                                        <form method="post" class="mb-0 ms-auto">
                                            <input type="hidden" name="link_courier" value="<?= $courier->fichier_enc ?>">
                                            <button type="submit" name="bukus_user_supervisor_read_courier" class="btn btn-transparent btn-sm text-info rounded-pill border-info" style="font-size: small;">
                                                voir document
                                            </button>
                                        </form>
                                    </td> 
                            </tr>

                            <?php $numero++ ?>
                        <?php endforeach; ?>
                    </tbody>
            </div>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>