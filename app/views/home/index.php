<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>


<section>
    <div class="container">
        
        <?php include APP_PATH . 'templates/alertView.php'; ?>

        <div class="row my-5">
            <a href="courier/create" class="btn btn-info w-75 mx-auto rounded-pill mb-4">Ajouter un courier</a>

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
                            <th scope="col">Ajouter par</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($allCouriers as $courier): ?>
                            <tr class="<?= ($courier->priority === ARRAY_PRIORITY[1]) ? 'fw-bold text-info':'' ?>" data-id="<?= $courier->courier_id ?>" style="cursor: pointer;">
                                <td class="click" data-id="<?= $courier->courier_id ?>" <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                     <?= $numero ?>
                                </td>
                                <td class="click" data-id="<?= $courier->courier_id ?>" <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                     <?= $courier->provenance ?>
                                </td>
                                <td class="click" data-id="<?= $courier->courier_id ?>" <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                     <?= $courier->objet ?>
                                </td>
                                <td class="click" data-id="<?= $courier->courier_id ?>" <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                     <?= $courier->ref_num ?>
                                </td>
                                <td class="click" data-id="<?= $courier->courier_id ?>" <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                     <?= ($courier->reception_num !== null) ? $courier->reception_num : '-' ?>
                                </td>
                                <td class="click" data-id="<?= $courier->courier_id ?>" <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                     <?= $courier->category ?>
                                </td>
                                <td class="click" data-id="<?= $courier->courier_id ?>" <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                     <?= $courier->type ?? '-' ?>
                                </td>
                                <td class="click" data-id="<?= $courier->courier_id ?>" <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                     <?= $courier->priority ?>
                                </td>
                                <td class="click" data-id="<?= $courier->courier_id ?>" <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> >
                                     <?= $courier->date_reception !== null ? Helper::formatDate2($courier->date_reception) : '-' ?>
                                </td>
                                <td class="click" data-id="<?= $courier->courier_id ?>" <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?> 
                                     class="text-<?= Helper::courierColors($Courier->showMoratoire($courier->date_limite, $courier->status))
                                     ?>">
                                    <?= $courier->status ?>
                                </td>
                                <td class="click" data-id="<?= $courier->courier_id ?>" <?= ($courier->priority === ARRAY_PRIORITY[1]) ? STYLE_TDS : '' ?>  >
                                     <?= $courier->saved_by ?>
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