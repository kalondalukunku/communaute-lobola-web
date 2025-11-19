<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>

<section class="section-top">
    <div class="container py-4">

        <!-- BREADCRUMB -->
        <div class="page-header">
            <div class="my-auto">
                <nav class="breadcrumb-custom mb-2">
                    <a href="/">Accueil</a>
                    / <a href="../show/<?= $courier->courier_id ?>">Courier de : <?= $courier->provenance ?></a> 
                    / <span class="text-secondary">Courier envoyé à <?= $courierRedirect->nom_personne_redirigee ?></span>
                </nav>
                <div class="d-flex align-items-center gap-3">
                    <h1 class="page-title mb-0">Courier de : <span style="color:var(--bs-body-color); font-weight:600;"><?= $courier->provenance ?></span></h1>
                    <!-- <div class="small muted">4j 23h 56m 29s</div> -->
                </div>
            </div>
        </div>

        <!-- TITLE -->
        <!-- Main card -->
        <div class="doc-card mb-4">
            <div class="doc-grid">
                <div class="doc-icon" aria-hidden>
                    <!-- PDF icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-file-earmark-pdf mx-auto" viewBox="0 0 16 16">
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                        <path d="M4.603 14.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.7 11.7 0 0 0-1.997.406 11.3 11.3 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.245.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 7.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/>
                    </svg>
                </div>

                <div class="doc-meta">
                    <div class="meta-row mb-2">
                        <div class="status-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-sliders2-vertical mt-1" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M0 10.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1H3V1.5a.5.5 0 0 0-1 0V10H.5a.5.5 0 0 0-.5.5M2.5 12a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 1 0v-2a.5.5 0 0 0-.5-.5m3-6.5A.5.5 0 0 0 6 6h1.5v8.5a.5.5 0 0 0 1 0V6H10a.5.5 0 0 0 0-1H6a.5.5 0 0 0-.5.5M8 1a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 1 0v-2A.5.5 0 0 0 8 1m3 9.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1H14V1.5a.5.5 0 0 0-1 0V10h-1.5a.5.5 0 0 0-.5.5m2.5 1.5a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 1 0v-2a.5.5 0 0 0-.5-.5"/>
                            </svg>
                            <?= $courierRedirect->status ?>
                        </div>
                        <div class="small muted date_limite ms-auto" data-datetime="<?= $courierRedirect->status === ARRAY_STATUS[0] ? $courierRedirect->date_limite : null ?>">
                            <?= $Couriers->showMoratoire($courierRedirect->date_limite, $courierRedirect->status) ?>
                        </div>
                    </div>

                    <!-- two column info list -->
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="meta-kv">Nom: <span class="meta-val"><?= $courierRedirect->nom_personne_redirigee ?></span></div>
                            <div class="meta-kv mt-2">Moratoire: <span class="meta-val"><?= $courierRedirect->moratoire ?>h</span></div>
                            <div class="meta-kv mt-2">Statut: <span class="meta-val"><?= $courierRedirect->status ?></span></div>
                            <div class="meta-kv mt-2">Travail demandé: <span class="meta-val"><?= $courierRedirect->travail_demande ?></span></div>
                            <?php if($interval !== null): ?>
                                <div class="meta-kv mt-2">Durée traitement: <span class="meta-val"><?= $interval ?></span></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <div class="meta-kv mt-2">Date limite: <span class="meta-val"><?= Helper::formatDate($courierRedirect->date_limite) ?></span></div>
                            <?php if($courierRedirect->status === ARRAY_STATUS[0]): ?>
                                <div class="meta-kv mt-2">Temps restant: 
                                    <span class="meta-val date_limite" data-datetime="<?= $courierRedirect->date_limite ?>">
                                        <?= $courierRedirect->moratoire ?>h
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if($courierRedirect->status === ARRAY_STATUS[2]): ?>
                                <div class="meta-kv mt-2">Dossier classé: <span class="meta-val"><?= $courierRedirect->dossier_classee ?></span></div>
                                <div class="meta-kv mt-2">Date classement: <span class="meta-val"><?= Helper::formatDate2($courierRedirect->date_classement) ?></span></div>
                            <?php endif; ?>
                            <div class="meta-kv mt-2">Modifié par: <span class="meta-val"><?= $courierRedirect->edited_by ?></span></div>                        
                        </div>
                    </div>
                </div>
            </div>

            <?php if($courierRedirect->status === ARRAY_STATUS[2]): ?>
                <form action="" method="post" class="text-center mt-3">
                    <button class="primary-btn" name="bukus_download_file_transfert_to<?= $courier_send_id ?>">
                        Télécharger le document
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download mb-1" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                        </svg>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        </div>

        <div class="footer-space"></div>

    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>