<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>

<section>
    <div class="container">
        
        <?php include APP_PATH . 'templates/alertView.php'; ?>

        <div class="row mt-5">
            <div class="card col-3 col-md-3 px-1 mx-auto border-0">
                <div class="card-body px-1 border border-1 rounded-4 px-2 shadow-sm">
                    <div class="text-center">
                        <div class="mb-3">
                            <img src="<?= ASSETS ?>svg/rapport (3).svg" width="100" alt="">
                        </div>
                        <h6 class="fw-bold">Rapport global</h6>
                        <p class="fst-italic" style="font-size: 14px;">
                            Un aperçu complet de tous les courriers enregistrés, quelle que soit leur nature ou leur état de traitement,
                            pour une vision centralisée de l'activité documentaire.
                        </p>
                    </div>
                    <div class="d-flex mt-4">
                        <?php if($isFileGlobal === true): ?>
                            <form method="post" class="mb-0 me-auto">
                                <button type="submit" name="bukus_generated_rapport_global_download" class="btn btn-sm btn-transparent border-info text-info py-2 px-3 rounded-pill">Télécharger</button>
                            </form>
                            
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Regénérer</button>
                            </form>
                        <?php else: ?>
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Générer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div> 
            <div class="card col-3 col-md-3 px-1 mx-auto border-0">
                <div class="card-body px-1 border border-1 rounded-4 px-2 shadow-sm">
                    <div class="text-center">
                        <div class="mb-3">
                            <img src="<?= ASSETS ?>svg/rapport (4).svg" width="100" alt="">
                        </div>
                        <h6 class="fw-bold">Rapport des suivis de documents</h6>
                        <p class="fst-italic" style="font-size: 14px;">
                            Analyse quantitative des courriers selon leurs statuts, types, catégories, priorités, documents traités, délai moyen, ...
                            pour mieux évaluer les performances internes.
                        </p>
                    </div>
                    <div class="d-flex mt-4">
                        <?php if($isFileGlobalSuivi === true): ?>
                            <form method="post" class="mb-0 me-auto">
                                <button type="submit" name="bukus_generated_rapport_global_suivi_download" class="btn btn-sm btn-transparent border-info text-info py-2 px-3 rounded-pill">Télécharger</button>
                            </form>
                            
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global_suivi" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Regénérer</button>
                            </form>
                        <?php else: ?>
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global_suivi" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Générer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div> 
            <div class="card col-3 col-md-3 px-1 mx-auto border-0">
                <div class="card-body px-1 border border-1 rounded-4 px-2 shadow-sm">
                    <div class="text-center">
                        <div class="mb-3">
                            <img src="<?= ASSETS ?>svg/rapport (1).svg" width="100" alt="">
                        </div>
                        <h6 class="fw-bold">Rapport des courriers entrants</h6>
                        <p class="fst-italic" style="font-size: 14px;">
                            Suivi détaillé des documents reçus par l'organisation, avec information sur leur provenance, leur objet,
                            leur référencement, leur classement, ...
                        </p>
                    </div>
                    <div class="d-flex mt-4">
                        <?php if($isFileGlobalEntrant === true): ?>
                            <form method="post" class="mb-0 me-auto">
                                <button type="submit" name="bukus_generated_rapport_global_entrant_download" class="btn btn-sm btn-transparent border-info text-info py-2 px-3 rounded-pill">Télécharger</button>
                            </form>
                            
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global_entrant" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Regénérer</button>
                            </form>
                        <?php else: ?>
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global_entrant" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Générer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div> 
            <div class="card col-3 col-md-3 px-1 mx-auto border-0">
                <div class="card-body px-1 border border-1 rounded-4 px-2 shadow-sm">
                    <div class="text-center">
                        <div class="mb-3">
                            <img src="<?= ASSETS ?>svg/rapport (1).svg" width="100" alt="">
                        </div>
                        <h6 class="fw-bold">Rapport des courriers sortants</h6>
                        <p class="fst-italic" style="font-size: 14px;">
                            Vue globale des documentscémis par l'organisation vers des tiers, incluant leurs destinations, les actions effectuées
                            et leur état final.
                        </p>
                    </div>
                    <div class="d-flex mt-4">
                        <?php if($isFileGlobalSortant === true): ?>
                            <form method="post" class="mb-0 me-auto">
                                <button type="submit" name="bukus_generated_rapport_global_sortant_download" class="btn btn-sm btn-transparent border-info text-info py-2 px-3 rounded-pill">Télécharger</button>
                            </form>
                            
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global_sortant" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Regénérer</button>
                            </form>
                        <?php else: ?>
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global_sortant" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Générer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>  
        </div>
        <div class="row my-5">
            <div class="card col-3 col-md-3 px-1 mx-auto border-0">
                <div class="card-body px-1 border border-1 rounded-4 px-2 shadow-sm">
                    <div class="text-center">
                        <div class="mb-3">
                            <img src="<?= ASSETS ?>svg/rapport traitement.svg" width="100" alt="">
                        </div>
                        <h6 class="fw-bold">Rapport des courriers en attente de traitement</h6>
                        <p class="fst-italic" style="font-size: 14px;">
                            Liste des courriers en cours de traitement, mettant en évidence les retards potentiels et les actions en attente
                        </p>
                    </div>
                    <div class="d-flex mt-4">
                        <?php if($isFileGlobalDocEnAttente === true): ?>
                            <form method="post" class="mb-0 me-auto">
                                <button type="submit" name="bukus_generated_rapport_global_doc_en_attente_download" class="btn btn-sm btn-transparent border-info text-info py-2 px-3 rounded-pill">Télécharger</button>
                            </form>
                            
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global_doc_en_attente" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Regénérer</button>
                            </form>
                        <?php else: ?>
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global_doc_en_attente" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Générer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div> 
            <div class="card col-3 col-md-3 px-1 mx-auto border-0">
                <div class="card-body px-1 border border-1 rounded-4 px-2 shadow-sm">
                    <div class="text-center">
                        <div class="mb-3">
                            <img src="<?= ASSETS ?>svg/rapport classe.svg" width="100" alt="">
                        </div>
                        <h6 class="fw-bold">Rapport des courriers classés</h6>
                        <p class="fst-italic" style="font-size: 14px;">
                            Rapport des documents administratifs archivés, qu'ils aient été traités ou classés sans suite, pour un suivi structuré.
                        </p>
                    </div>
                    <div class="d-flex mt-4">
                        <?php if($isFileGlobalDocClasse === true): ?>
                            <form method="post" class="mb-0 me-auto">
                                <button type="submit" name="bukus_generated_rapport_global_doc_classe_download" class="btn btn-sm btn-transparent border-info text-info py-2 px-3 rounded-pill">Télécharger</button>
                            </form>
                            
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global_doc_classe" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Regénérer</button>
                            </form>
                        <?php else: ?>
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global_doc_classe" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Générer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div> 
            <div class="card col-3 col-md-3 px-1 mx-auto border-0">
                <div class="card-body px-1 border border-1 rounded-4 px-2 shadow-sm">
                    <div class="text-center">
                        <div class="mb-3">
                            <img src="<?= ASSETS ?>svg/rapport redirections2.svg" width="100" alt="">
                        </div>
                        <h6 class="fw-bold">Rapport des redirections</h6>
                        <p class="fst-italic" style="font-size: 14px;">
                            Suivi détaillé de toutes les redirections effectuées à l'interne, pour une traçabilité complète des actions déléguées.
                        </p>
                    </div>
                    <div class="d-flex mt-4">
                        <?php if($isFileGlobalDocRedirections === true): ?>
                            <form method="post" class="mb-0 me-auto">
                                <button type="submit" name="bukus_generated_rapport_global_doc_redirections_download" class="btn btn-sm btn-transparent border-info text-info py-2 px-3 rounded-pill">Télécharger</button>
                            </form>
                            
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global_doc_redirections" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Regénérer</button>
                            </form>
                        <?php else: ?>
                            <form method="post" class="mb-0 ms-auto">
                                <button type="submit" name="bukus_generated_rapport_global_doc_redirections" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Générer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div> 
            <div class="card col-3 col-md-3 px-1 mx-auto border-0">
                <div class="card-body px-1 border border-1 rounded-4 px-2 shadow-sm">
                    <div class="text-center">
                        <div class="mb-4 mt-3">
                            <img src="<?= ASSETS ?>svg/rapport activité user.svg" width="100" alt="">
                        </div>
                        <h6 class="fw-bold">Rapport d'activité utilisateur - <span class="text-info">A venir</span></h6>
                        <p class="fst-italic" style="font-size: 14px;">
                            Historique complet des actions des utilisateurs pour assurer la transparence, la responsabilité
                            et la sécurité du système.
                        </p>
                    </div>
                    <div class="d-flex mt-4">
                        <?php if($isFileGlobalDocActiviteUser === true): ?>
                            <form method="post" class="mb-0 me-auto">
                                <button disabled type="submit" name="bukus_generated_rapport_global_sortant_download" class="btn btn-sm btn-transparent border-info text-info py-2 px-3 rounded-pill">Télécharger</button>
                            </form>
                            
                            <form method="post" class="mb-0 ms-auto">
                                <button disabled type="submit" name="bukus_generated_rapport_global_sortant" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Regénérer</button>
                            </form>
                        <?php else: ?>
                            <form method="post" class="mb-0 ms-auto">
                                <button disabled type="submit" name="bukus_generated_rapport_global_sortant" class="btn btn-sm btn-info py-2 px-3 rounded-pill">Générer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>