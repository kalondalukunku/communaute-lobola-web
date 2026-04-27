<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <div class="container mx-auto py-[60px] px-4 md:px-0">
        <div class="max-w-3xl mx-auto">
            <div class="status-container fade-in bg-white rounded-[2.5rem] shadow-xl shadow-primary/5 border border-gray-100 text-center p-10 md:p-8">
                
                <!-- Icône de validation en attente -->
                <div class="mb-6 flex justify-center">
                    <div class="relative">
                        <!-- Cercle rotatif discret -->
                        <div class="w-16 h-16 border-4 border-primary/10 border-t-primary rounded-full animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-primary text-md"></i>
                        </div>
                    </div>
                </div>

                <?php if(isset($membre->preuve_paiement) && $membre->preuve_paiement && isset($paiement->payment_status) === ARRAY_PAYMENT_STATUS[0] && $membre->doc_approuved == 1): ?>
                    <h1 class="font-serif text-2xl text-primary font-bold mb-3">Votre paiement est en attente</h1>
                    <p class="text-gray-500 mb-8 text-sm leading-relaxed">
                        Nous avons bien reçu votre demande d'engagement et votre preuve de paiement à la communauté Lobola. Nos administrateurs procéderont à la vérification finale de votre engagement pour activer votre accès aux enseignements de <strong>BOLOKELE</strong>.
                    </p>
                <?php else: ?>
                    <h1 class="font-serif text-2xl text-primary font-bold mb-3">Effectuer le paiement</h1>
                    <p class="text-gray-500 mb-8 text-sm leading-relaxed">
                        Pour finaliser votre demande d'engagement à la communauté Lobola, veuillez effectuer le paiement de votre engagement et envoyer la capture de votre transaction. Une fois le paiement reçu, nos administrateurs procéderont à la vérification finale de votre engagement pour activer votre accès aux enseignements de <strong>BOLOKELE</strong>.
                    </p>
                <?php endif; ?>

                <?php if(!isset($membre->preuve_paiement) && !$membre->preuve_paiement && $membre->doc_approuved == 1): ?>
                    <!-- Résumé de la transaction -->
                    <div class="text-left mb-8">
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                            <h2 class="text-[10px] text-gray-400 font-extrabold uppercase tracking-[0.2em] mb-4 text-center">Numéro de transaction mobile money (RDC)</h2>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
                                    <span class="text-xs text-gray-500">Mpesa :</span>
                                    <span class="text-xs md:text-sm font-bold text-primary">
                                        +243 819 889 889 (BASELE)
                                    </span>
                                </div>
                                <!-- <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
                                    <span class="text-xs text-gray-500">Orange Money :</span>
                                    <span class="text-sm font-bold text-primary">
                                        +243 842 345 678
                                    </span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
                                    <span class="text-xs text-gray-500">Airtel Money :</span>
                                    <span class="text-sm font-bold text-primary">
                                        +243 992 345 678
                                    </span>
                                </div> -->
                                <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
                                    <span class="text-xs text-gray-500">Western Union :</span>
                                    <span class="text-xs md:text-sm font-bold text-primary">
                                        +33 6 44 16 74 99,
                                        MABE DEFFO <br>
                                        gdossou@hotmail.fr,
                                        France
                                    </span>
                                </div>
                                
                                <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
                                    <span class="text-xs text-gray-500">Montant versé</span>
                                    <span class="text-sm font-bold text-primary">
                                        <?= Utils::getMonthsNumber($membre->modalite_engagement) * $membre->montant .' '. $membre->devise ?>
                                    </span>
                                </div>

                                <!-- <div class="flex justify-between items-center py-2">
                                    <span class="text-xs text-gray-500">ID Transaction</span>
                                    <span class="font-mono text-[10px] text-gray-400 font-bold uppercase tracking-tighter">
                                        TXN-<?= strtoupper(substr(md5(time()), 0, 8)) ?>
                                    </span>
                                </div> -->
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Pièces Jointes -->
                <form method="post" enctype="multipart/form-data" class="mb-8 border-2 border-dashed border-gray-100 rounded-3xl bg-gray-50/30">
                    <div class="p-8 flex flex-col md:flex-row items-center gap-8">
                        <div class="flex-1 text-center md:text-left">
                            <h4 class="font-bold text-gray-800 text-sm mb-1">Preuve de Paiement</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Veuillez uploader une photo de votre récépisse de paiement.</p>
                        </div>
                        <div class="w-full md:w-auto">
                            <div class="relative group">
                                <input type="file" name="photo_file" id="photo-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" required>
                                <div id="photo-preview-container" class="w-32 h-32 md:w-40 md:h-40 bg-white border border-gray-200 rounded-2xl shadow-sm group-hover:border-primary transition-all flex flex-col items-center justify-center overflow-hidden">
                                    <?php if(!isset($membre->preuve_paiement) && !$membre->preuve_paiement && $membre->doc_approuved == 1): ?>
                                        <div id="preview-placeholder" class="text-center p-4">
                                            <i class="fas fa-camera text-primary text-2xl mb-2"></i>
                                            <p class="text-[9px] uppercase font-bold text-gray-400">Ajouter la photo</p>
                                        </div>        
                                    <?php endif; ?>
                                    <img id="image-display" src="<?= isset($membre->preuve_paiement) ? '../../'. $membre->preuve_paiement : '' ?>" class="<?= isset($membre->preuve_paiement) ? '' : 'hidden' ?> w-full h-full object-co && isset($paiement->payment_status) === ARRAY_PAYMENT_STATUS[0]ver">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(!isset($membre->preuve_paiement) && !$membre->preuve_paiement && $membre->doc_approuved == 1): ?>
                        <button type="submit" name="cllil_membre_upload_payment_proof" class="mb-8 w-[80%] bg-primary text-white py-4 px-8 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 active:scale-95">
                            <i class="fas fa-upload mr-2"></i> Soumettre la preuve de paiement
                        </button>
                    <?php endif; ?>
                </form>

                <?php if(isset($membre->preuve_paiement) && $membre->preuve_paiement && $membre->doc_approuved == 1): ?>
                    <!-- Note d'information -->
                    <div class="bg-primary/5 p-4 rounded-2xl mb-8 border border-primary/10">
                        <p class="text-[11px] text-primary leading-relaxed flex items-start text-left">
                            <i class="fas fa-info-circle mt-0.5 mr-3 opacity-70"></i>
                            <span>
                                La validation prend généralement <strong>3 à 12 heures</strong>. Vous recevrez une notification par email dès que votre espace membre sera déverrouillé.
                            </span>
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Boutons d'action -->
                <div class="flex flex-col gap-3">
                    <?php if(isset($membre->preuve_paiement) && $membre->preuve_paiement && $membre->doc_approuved == 1): ?>
                        <button onclick="window.location.reload()" class="w-full bg-primary text-white py-4 px-8 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 active:scale-95">
                            <i class="fas fa-sync-alt mr-2"></i> Actualiser mon statut
                        </button>
                    <?php endif; ?>
                    <a href="../profile/<?= $membre->member_id ?>" class="text-[11px] text-gray-600 font-bold hover:text-primary transition-colors uppercase tracking-widest mt-3">
                        Retour à mon profil
                    </a>
                </div>
            </div>

            <div class="mt-10 text-center">
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.1em]">
                    Besoin d'aide ? <a href="mailto:<?= ADMIN_EMAIL ?>" class="text-primary font-bold underline decoration-primary/30">Contactez le support</a>
                </p>
            </div>
        </div>
    </div>
    
    <script src="<?= ASSETS ?>js/modules/main2.js"></script>
    <script src="<?= ASSETS ?>js/app.js"></script>