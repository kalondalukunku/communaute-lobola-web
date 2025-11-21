<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>

<section class="section-top">
    <div class="container my-5">
            
        <?php include APP_PATH . 'templates/alertView.php'; ?>

        <!-- Header -->
        <div class="page-header">
            <div class="my-auto">
                <nav class="breadcrumb-custom mb-2">
                    <a href="/">Accueil</a> 
                    / <span class="muted">Courier de : <?= $courier->provenance ?></span>
                </nav>
                <div class="d-flex align-items-center gap-3">
                    <h1 class="page-title mb-0">Courier de : <span style="color:var(--bs-body-color); font-weight:600;"><?= $courier->provenance ?></span></h1>
                    <!-- <div class="small muted">4j 23h 56m 29s</div> -->
                </div>
            </div>
            
            <?php if($courier->status === ARRAY_STATUS[0] || $courier->status === ARRAY_STATUS[2]): ?>
                <div class="d-flex align-items-center my-auto">
                    <a href="../edit/<?= $courier->courier_id ?>" class="btn-ghost">
                        <i class="bi bi-pencil"></i> 
                        Modifier
                    </a>
                </div>
            <?php endif; ?>
        </div>

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
                            <?= $courier->status ?>
                        </div>
                        <div class="small muted date_limite ms-auto" data-datetime="<?= $courier->status === ARRAY_STATUS[0] ? $courier->date_limite : null ?>">
                            <?= $Couriers->showMoratoire($courier->date_limite, $courier->status) ?>
                        </div>
                    </div>

                    <!-- two column info list -->
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="meta-kv">Provenance: <span class="meta-val"><?= $courier->provenance ?></span></div>
                            <div class="meta-kv mt-2">Catégorie: <span class="meta-val"><?= $courier->category ?></span></div>
                            <?php if($courier->type !== null): ?>
                                <div class="meta-kv mt-2">Type: <span class="meta-val"><?= $courier->type ?></span></div>
                            <?php endif; ?>
                            <div class="meta-kv mt-2">Priorité: <span class="meta-val"><?= $courier->priority ?></span></div>
                            <div class="meta-kv mt-2">Référencement: <span class="meta-val"><?= $courier->ref_num ?></span></div>
                            <?php if($courier->moratoire !== null): ?>
                                <div class="meta-kv mt-2">Moratoire: <span class="meta-val"><?= $courier->moratoire ?> h</span></div>
                            <?php endif; ?>
                            <?php if($courier->status === ARRAY_STATUS[3] || $courier->status === ARRAY_STATUS[4]): ?>
                                <div class="meta-kv mt-2">Dossier classé: <span class="meta-val"><?= $courier->dossier_classee ?> h</span></div>
                            <?php endif; ?>
                            <div class="meta-kv mt-2">Dernière modification: <span class="meta-val time_ago" data-time-ago="<?= $courier->updated_at ?>"></span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="meta-kv">Objet: <span class="meta-val"><?= $courier->objet ?></span></div>
                            <?php if($courier->destination !== null): ?>
                                <div class="meta-kv mt-2">Destination: <span class="meta-val"><?= $courier->destination ?></span></div>
                            <?php endif; ?>
                            <?php if($courier->transmission !== null): ?>
                                <div class="meta-kv mt-2">Transmission: <span class="meta-val"><?= $courier->transmission ?></span></div>
                            <?php endif; ?>
                            <?php if($courier->transmission === ARRAY_TRANSMISSION[1]): ?>
                                <div class="meta-kv mt-2">date de départ: <span class="meta-val"><?= Helper::formatDate($courier->date_depart) ?></span></div>
                                <div class="meta-kv mt-2">date de retour: <span class="meta-val"><?= Helper::formatDate($courier->date_retour) ?></span></div>
                            <?php endif; ?>
                            <?php if($courier->status === ARRAY_STATUS[3] || $courier->status === ARRAY_STATUS[4]): ?>
                                <div class="meta-kv mt-2">Date classement: <span class="meta-val"><?= Helper::formatDate2($courier->date_classement) ?></span></div>
                            <?php endif; ?>
                            <?php if(count($allRedirections) !== 0): ?>
                                <div class="meta-kv mt-2">Actuellement chez: <span class="meta-val"><?= end($allRedirections)->nom_personne_redirigee ?></span></div>
                            <?php endif; ?>
                            <div class="meta-kv mt-2">Enregistré par: <span class="meta-val"><?= $courier->saved_by ?></span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <form method="post">
                    <button class="primary-btn" name="bukus_download_file" type="submit">
                        <i class="bi bi-download me-1"></i> 
                        Télécharger le document
                    </button>
                </form>

                <?php if(
                    $courier->status === ARRAY_STATUS[0] 
                    || $courier->status === ARRAY_STATUS[2] 
                    && $courier->transmission === ARRAY_TRANSMISSION[1]
                ): ?>
                    <?php if($courier->rapport_join === "1"): ?>
                        <form method="post">
                            <button name="bukus_download_rapport_join" type="submit" class="btn-ghost">
                                <i class="bi bi-journal-text me-1"></i>
                                Télécharger le rapport de service
                            </button>
                        </form>

                    <?php else: ?>
                        <a href="../join_rapport/<?= $courier->courier_id ?>" class="btn-ghost">
                            <i class="bi bi-journal-text me-1"></i>
                            Joindre le rapport
                        </a>
                    <?php endif; ?>
                
                <?php endif; ?>
            </div>
        </div>

        <?php if(
                    $courier->category === ARRAY_CATEGORIES[0]  
                    || $courier->category === ARRAY_CATEGORIES[1] 
                    && $courier->type === ARRAY_TYPE[0] 
                ): 
        ?>
        <!-- content row -->
        <div class="row mt-4">
            <div class="col-lg-9">
                <div class="d-flex justify-content-between py-3">
                    <div class="section-title my-auto">Liste des personnes envoyées</div>

                    <?php if($courier->status === ARRAY_STATUS[0]): ?>
                        <a href="../send_to/<?= $courier->courier_id ?>" class="primary-btn my-auto py-2" style="font-size: 12px;">
                            Envoyez vers
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-send-plus my-auto" viewBox="0 0 16 16">
                                <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.75.75 0 0 0-.124 1.329l4.995 3.178 1.531 2.406a.5.5 0 0 0 .844-.536L6.637 10.07l7.494-7.494-1.895 4.738a.5.5 0 1 0 .928.372zm-2.54 1.183L5.93 9.363 1.591 6.602z"/>
                                <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
                

                <div class="table-wrap mb-4">
                    
                    <?php if(count($allRedirections) > 0): ?>
                        <div class="table-responsive">
                            <table class="table align-middle text-white">
                            <thead>
                                <tr>
                                <th>Nom</th>
                                <th>TD</th>
                                <th>Durée</th>
                                <th>Fichier</th>
                                <th>Modifié par</th>
                                <th>Status</th>
                                <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example rows -->
                                    <?php foreach($allRedirections as $redict): ?>
                                        <tr>
                                            <td><?= $redict->nom_personne_redirigee ?></td>
                                            <td><?= Helper::formatText($redict->travail_demande) ?></td>
                                            <td class="date_limite" data-datetime="<?= ($redict->status === ARRAY_STATUS[0]) ? $redict->date_limite : '' ?>">
                                                <?php if(strtotime($redict->date_limite) > time() && $redict->status === ARRAY_STATUS[0]) : ?>
                                                    <?= $redict->moratoire .'h' ?>
                                                <?php elseif($redict->status === ARRAY_STATUS[2]): ?>
                                                    <?= $redict->moratoire .'h - '. ARRAY_STATUS[2] ?>
                                                <?php elseif($redict->status === ARRAY_STATUS[1]): ?>
                                                    <?= $redict->moratoire .'h - '. ARRAY_STATUS[1] ?>
                                                <?php else: ?>
                                                        <?= 'Temps épuisé' ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="small muted">
                                                <?php if($redict->status === ARRAY_STATUS[2]) : ?>
                                                    <form action="" method="post" class="mb-0">
                                                        <button class="primary-btn py-1 px-3" name="download_file_transfert_to<?= $redict->id ?>" style="font-size: 12px;">
                                                            Télécharger
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download mb-1" viewBox="0 0 16 16">
                                                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                <?php else: ?>    
                                                    <span class="fs-6">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $redict->edited_by ?></td>
                                            <td><span class="badge bg-secondary"><?= $redict->status ?></span></td>
                                            <td>
                                                <a href="../details/<?= $courier->courier_id ?>&id=<?= $redict->id ?>" class="btn btn-sm action-icon" title="Infos">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                                                    </svg>
                                                </a>

                                                <?php if($redict->status === ARRAY_STATUS[0]) : ?>
                                                    <a href="../send_to/<?= $courier->courier_id ?>&id=<?= $redict->id ?>" class="btn btn-sm action-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-send-plus" viewBox="0 0 16 16">
                                                            <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.75.75 0 0 0-.124 1.329l4.995 3.178 1.531 2.406a.5.5 0 0 0 .844-.536L6.637 10.07l7.494-7.494-1.895 4.738a.5.5 0 1 0 .928.372zm-2.54 1.183L5.93 9.363 1.591 6.602z"/>
                                                            <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5"/>
                                                        </svg>
                                                    </a>
                                                    <a href="../classify/<?= $courier->courier_id ?>&id=<?= $redict->id ?>" class="btn btn-sm action-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-clipboard2-check" viewBox="0 0 16 16">
                                                            <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z"/>
                                                            <path d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z"/>
                                                            <path d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z"/>
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                            </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-muted small mb-3">Ce document n'a pas encore été redirigé pour un traitement. Envoyez ce courrier pour un traitement.</div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- right column: floating style summary for wider screens -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="summary-panel">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:44px;height:44px;border-radius:10px;background:rgba(79,163,255,0.06);display:grid;place-items:center;color:var(--accent);font-weight:700">PDF</div>
                    <div>
                    <div style="font-weight:700">Courier de : <?= $courier->provenance ?></div>
                    <div class="small muted">• Status : <?= $courier->status ?></div>
                    </div>
                </div>

                <hr style="border-color:rgba(255,255,255,0.04); margin:6px 0">

                <div class="small muted">Taille</div>
                <div style="font-weight:700"><?= Utils::getFileSizeReadable($courier->fichier_enc) ?></div>

                <div class="small muted">Dernière action</div>
                <div><?= end($allLoggers)->action ?></div>

                <div class="d-grid mt-2">
                    <form method="post">
                        <button class="primary-btn w-100 py-2" name="bukus_download_file" type="submit" style="font-size: 12px;">
                            Télécharger
                        </button>
                    </form>
                    <!-- <button class="btn-ghost mt-2 py-2" style="font-size: 12px;">Supprimer du cache</button> -->
                </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <?php if($courier->status !== ARRAY_STATUS[3] && $courier->status !== ARRAY_STATUS[4]): ?>
                <!-- explanatory note -->
                <div class="text-center muted small mb-3">
                    Avant de définir ce document comme traiter ou classer, veuillez vérifier que toites les informations saisies sont exactes et complètes. Assurez-vous
                    que tout soit bien conformes aux exigences du service. Cette étape est cruciale pour garantir un traitement efficace et une
                    traçabilité optimale.
                </div>
            <?php endif; ?>

            <!-- bottom actions -->
            <div class="row">
                <?php if($courier->status !== ARRAY_STATUS[2] && $courier->status !== ARRAY_STATUS[3] && $courier->status !== ARRAY_STATUS[4]): ?>
                    <form class="col mb-0" method="post">
                        <button class="w-100 mx-1 btn-ghost rounded-pill" type="submit" name="bukus_traiter_courier">
                            Traiter
                        </button>
                    </form>
                <?php endif; ?>
                <?php if($courier->status !== ARRAY_STATUS[3] && $courier->status !== ARRAY_STATUS[4]): ?>
                    <button class="col mx-1 secondary-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                        Classer sans suite
                    </button>
                    <button class="col mx-1 primary-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Classer ce document
                    </button>
                <?php endif; ?>
            </div>

            <?php if($courier->status === ARRAY_STATUS[4] || $courier->status === ARRAY_STATUS[3]): ?>
                <div class="text-center muted small mb-3">
                    Avant de définir ce document comme traiter ou classer, veuillez vérifier que toites les informations saisies sont exactes et complètes. Assurez-vous
                    que tout soit bien conformes aux exigences du service. Cette étape est cruciale pour garantir un traitement efficace et une
                    traçabilité optimale.
                </div>

                <form action="" method="post" class="text-center mb-5">
                    <button class="primary-btn" name="bukus_download_rapport">
                        Télécharger le rapport
                    </button>
                </form>
            <?php endif; ?>
        </div>
        
        <?php endif; ?>

    </div>
</section>



<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Classer ce document</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post"> 
                    <div class="row px-3">
                        
                        <div class="g-2">
                            <div class="form-floating mb-2"> 
                                <input type="text" name="dossier_classee" class="form-control" id="floatingInput" value="<?=  $data['dossier_classee'] ?? '' ?>" placeholder="name@example.com" required> 
                                <label for="floatingInput">Dossier classé</label> 
                            </div>
                            
                            <div class="form-floating mb-2"> 
                                <input type="datetime-local" name="date_classement" class="form-control" id="floatingInput" value="<?=  $data['date_classement'] ?? '' ?>" placeholder="name@example.com" required> 
                                <label for="floatingInput">Date du classement</label> 
                            </div>
                        </div>  
                        
                        <button class="primary-btn rounded-pill py-2" name="bukus_classer_document" type="submit">Classer</button>
                    </div> 
                    
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdrop2Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdrop2Label">Classer ce document sans suite</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post"> 
                    <div class="row px-3">
                        
                        <div class="g-2">
                            <div class="form-floating mb-2"> 
                                <input type="text" name="dossier_classee" class="form-control" id="floatingInput" value="<?=  $data['dossier_classee'] ?? '' ?>" placeholder="name@example.com" required> 
                                <label for="floatingInput">Dossier classé</label> 
                            </div>
                            
                            <div class="form-floating mb-2"> 
                                <input type="datetime-local" name="date_classement" class="form-control" id="floatingInput" value="<?=  $data['date_classement'] ?? '' ?>" placeholder="name@example.com" required> 
                                <label for="floatingInput">Date du classement</label> 
                            </div>
                        </div>  
                        
                        <button class="secondary-btn border-info text-info rounded-pill py-2" name="bukus_classer_document_sans_suite" type="submit">Classer sans suite</button>
                    </div> 
                    
                </form>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>