<?php 
  include APP_PATH . 'views/layouts/header.php'; 
?>

<section class="flex flex-col mt-[3.5rem]">
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
    ?>
    
    <!-- Zone de Contenu Principale (Plein Écran) -->
    <div class="flex-1 p-4 sm:p-8">
        
        <!-- Header de la Page et Bouton Retour -->
        <header class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div class="flex items-center mb-4 md:mb-0">
                <a href="gestion_documents.html" class="text-[var(--color-secondary)] hover:text-[var(--color-primary)] transition duration-150 mr-4">
                    <i class="fas fa-arrow-left text-2xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">
                    Détail : Contrat_CDI_JDupont_2018.pdf
                </h1>
            </div>
            
            <!-- Boutons d'Action Rapide -->
            <div class="flex space-x-3">
                <button onclick="simulateAction('télécharger')" class="flex items-center space-x-2 border border-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-xl hover:bg-gray-100 transition duration-150">
                    <i class="fas fa-download"></i>
                    <span>Télécharger</span>
                </button>
                <button onclick="showDeleteModal()" class="flex items-center space-x-2 bg-red-500 text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-red-600 transition duration-150">
                    <i class="fas fa-trash-alt"></i>
                    <span>Supprimer</span>
                </button>
            </div>
        </header>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Colonne 1/3: Prévisualisation du Document -->
            <div class="lg:col-span-2 card-container p-4 h-[700px] flex flex-col">
                <h2 class="text-xl font-semibold text-[var(--color-secondary)] mb-4 border-b pb-2">Prévisualisation (Simulée)</h2>
                <div class="flex-1 bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center rounded-lg text-gray-500 text-center">
                    <p class="p-4">
                        <i class="fas fa-file-pdf text-6xl text-red-400 mb-3"></i><br>
                        Le contenu du PDF/JPEG serait affiché ici dans un visualiseur intégré (iframe ou objet embarqué) pour consultation immédiate.
                        <br><small>Fichier: Contrat_CDI_JDupont_2018.pdf</small>
                    </p>
                </div>
            </div>
            
            <!-- Colonne 2/3: Détails et Historique -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Carte des Métadonnées -->
                <div class="card-container p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                         <h2 class="text-xl font-semibold text-[var(--color-secondary)]">Métadonnées</h2>
                         <button onclick="showMetadataModal()" class="p-2 text-gray-500 hover:text-[var(--color-primary)] transition duration-150" title="Modifier les Métadonnées">
                            <i class="fas fa-edit text-lg"></i>
                        </button>
                    </div>

                    <p class="text-xs text-gray-500 mb-4">
                        Ces informations (non visibles sur le document) facilitent la recherche, le tri et l'archivage.
                    </p>

                    <dl class="space-y-3">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <dt class="font-medium text-gray-600">Employé</dt>
                            <dd class="text-gray-900 font-semibold">Jean Dupont</dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <dt class="font-medium text-gray-600">Matricule</dt>
                            <dd class="text-gray-900">#RH4578</dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <dt class="font-medium text-gray-600">Type de Document</dt>
                            <dd class="text-gray-900">Contrat de Travail</dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <dt class="font-medium text-gray-600">Tag de Catégorisation</dt>
                            <dd class="text-gray-900">
                                <span class="px-2 py-0.5 text-xs leading-5 font-medium rounded bg-blue-100 text-blue-800">Contrat/CDI</span>
                            </dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <dt class="font-medium text-gray-600">Date d'Expiration</dt>
                            <dd class="text-gray-900">N/A (CDI)</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="font-medium text-gray-600">Statut</dt>
                            <dd class="px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Valide</dd>
                        </div>
                        <!-- Métadonnées techniques qui ne sont pas nécessairement modifiables, mais affichées -->
                         <div class="flex justify-between items-center border-t border-gray-100 pt-2 text-xs">
                            <dt class="font-light text-gray-500">Nom du Fichier (Original)</dt>
                            <dd class="text-gray-500">Contrat_CDI_JDupont_2018.pdf</dd>
                        </div>
                         <div class="flex justify-between items-center text-xs">
                            <dt class="font-light text-gray-500">Taille / Format</dt>
                            <dd class="text-gray-500">450 KB / PDF</dd>
                        </div>
                    </dl>

                </div>
                
                <!-- Carte de l'Historique -->
                <div class="card-container p-6">
                    <h2 class="text-xl font-semibold text-[var(--color-secondary)] mb-4 border-b pb-2">Historique d'Activité</h2>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3">
                            <div class="flex-shrink-0 pt-1 text-gray-400"><i class="fas fa-clock"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Document téléchargé et créé.</p>
                                <p class="text-xs text-gray-500">Par: Admin Dupont - Le 2018-09-01</p>
                            </div>
                        </li>
                        <li class="flex items-start space-x-3">
                            <div class="flex-shrink-0 pt-1 text-gray-400"><i class="fas fa-user-shield"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Accès et consultation.</p>
                                <p class="text-xs text-gray-500">Par: Service RH - Le 2024-12-09 à 16:30</p>
                            </div>
                        </li>
                    </ul>
                </div>
                
            </div>
            
        </div>
        
        <!-- Footer -->
        <footer class="mt-12 text-center text-xs text-gray-500 border-t border-gray-200 pt-4">
            &copy; 2025 Organisation Publique.
        </footer>
    </div>

    <!-- Modale de Modification des Métadonnées (Simulée) -->
    <div id="metadata-modal" class="modal-overlay">
        <div class="modal-content">
            <h3 class="text-2xl font-bold text-[var(--color-secondary)] mb-4 border-b pb-2">Modifier les Métadonnées</h3>
            
            <form class="space-y-4">
                <div>
                    <label for="meta-employe" class="block text-sm font-medium text-gray-700">Employé Associé (Champ modifiable)</label>
                    <input type="text" id="meta-employe" value="Jean Dupont" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>
                 <div>
                    <label for="meta-type" class="block text-sm font-medium text-gray-700">Type de Document</label>
                    <select id="meta-type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option>Contrat de Travail</option>
                        <option>Pièce d'Identité</option>
                        <option>Diplôme / Certificat</option>
                    </select>
                </div>
                <div>
                    <label for="meta-tags" class="block text-sm font-medium text-gray-700">Tags de Catégorisation</label>
                    <input type="text" id="meta-tags" value="Contrat, CDI, RH" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" placeholder="Séparer les tags par des virgules">
                </div>
                 <div>
                    <label for="meta-date-expiration" class="block text-sm font-medium text-gray-700">Date d'Expiration (Format AAAA-MM-JJ)</label>
                    <input type="date" id="meta-date-expiration" value="" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>
            </form>

            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="hideMetadataModal()" type="button" class="py-2 px-4 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-100 transition duration-150">
                    Annuler
                </button>
                <button onclick="saveMetadataChanges()" type="button" class="py-2 px-4 bg-[var(--color-primary)] text-white font-semibold rounded-xl hover:bg-orange-800 transition duration-150">
                    Enregistrer les Changements
                </button>
            </div>
        </div>
    </div>

    <!-- Modale de Suppression (Simulée) -->
    <div id="delete-modal" class="modal-overlay">
        <div class="modal-content">
            <h3 class="text-2xl font-bold text-red-600 mb-4 border-b pb-2">Confirmer la Suppression</h3>
            <p class="text-gray-700 mb-6">
                Êtes-vous certain de vouloir supprimer définitivement le document 
                <span class="font-bold">'Contrat_CDI_JDupont_2018.pdf'</span> ? 
                Cette action est irréversible.
            </p>
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="hideDeleteModal()" type="button" class="py-2 px-4 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-100 transition duration-150">
                    Annuler
                </button>
                <button onclick="confirmDelete()" type="button" class="py-2 px-4 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition duration-150">
                    Supprimer Définitivement
                </button>
            </div>
        </div>
    </div>
    
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>