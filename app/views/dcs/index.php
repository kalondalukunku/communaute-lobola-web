<?php 
  include APP_PATH . 'views/layouts/header.php'; 
?>

<section class="flex flex-col mt-[3.5rem]">
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php';
    ?>
    
    <!-- Zone de Contenu Principale (Plein Écran) -->
    <div class="flex-1 p-4 sm:p-8">
        
        <!-- Header de la Page -->
        <header class="mb-2 flex flex-col md:flex-row justify-between items-start md:items-center">
            <h1 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">
                <i class="fas fa-folder-open text-[var(--color-primary)] mr-3"></i>
                Gestion des Documents
            </h1>
            <!-- Bouton d'Action Primaire (Orange) -->
            <a href="#" id="addDocumentBtn" class="text-xs flex items-center space-x-2 bg-[var(--color-primary)] text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-orange-800 transition duration-150 transform hover:scale-[1.01]">
                <i class="fas fa-upload"></i>
                <span>Ajouter un Document</span>
            </a>
        </header>
        
        <!-- Cadre de Recherche et Filtres -->
        <div class="card-container p-6 mb-6">
            <h2 class="text-xl font-semibold text-[var(--color-secondary)] mb-4">Recherche et Filtres</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Recherche Principale -->
                <div class="md:col-span-2 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="globalSearch" placeholder="Rechercher par nom de fichier, employé, ou mot-clé..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:border-[var(--color-primary)] focus:ring-1 focus:ring-[var(--color-primary)] transition duration-150">
                </div>
                
                <!-- Filtre Type -->
                <div>
                    <select id="filterType" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:border-[var(--color-primary)] focus:ring-1 focus:ring-[var(--color-primary)] transition duration-150">
                        <option value="">-- Tous les Types --</option>
                        <option value="contrat">Contrat de Travail</option>
                        <option value="identite">Pièce d'Identité</option>
                        <option value="diplome">Diplôme / Certificat</option>
                        <option value="rh">Document RH</option>
                    </select>
                </div>
                
                <!-- Filtre Statut -->
                <div>
                    <select id="filterStatus" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:border-[var(--color-primary)] focus:ring-1 focus:ring-[var(--color-primary)] transition duration-150">
                        <option value="">-- Tous les Statuts --</option>
                        <option value="valide">Valide</option>
                        <option value="expire">Expiré / À renouveler</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Tableau des Documents (Liste principale) -->
        <div class="card-container p-4 overflow-x-auto">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 px-2">Liste des Fichiers (Total: 532)</h2>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 hidden lg:table-header-group">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nom du Document
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Employé Associé
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Expiration
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    
                    <!-- Ligne 1: Document Actif (Contrat) -->
                    <tr class="document-row lg:table-row hover:bg-gray-50 transition duration-150">
                        <td data-label="Nom du Document" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[var(--color-secondary)] hover:underline">
                            <i class="fas fa-file-contract mr-2 text-[var(--color-primary)]"></i> Contrat_CDI_JDupont_2018.pdf
                        </td>
                        <td data-label="Employé Associé" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Jean Dupont (#RH4578)
                        </td>
                        <td data-label="Type" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Contrat de Travail
                        </td>
                        <td data-label="Expiration" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            N/A (CDI)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 ml-2">Valide</span>
                        </td>
                        <td data-label="Actions" class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                            <button title="Visualiser" class="action-button"><i class="fas fa-eye text-lg"></i></button>
                            <button title="Télécharger" class="action-button"><i class="fas fa-download text-lg"></i></button>
                            <button title="Supprimer" class="action-button text-red-500 hover:text-red-700"><i class="fas fa-trash-alt text-lg"></i></button>
                        </td>
                    </tr>
                    
                    <!-- Ligne 2: Document Expiré (Identité) -->
                    <tr class="document-row lg:table-row hover:bg-gray-50 transition duration-150">
                        <td data-label="Nom du Document" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[var(--color-secondary)] hover:underline">
                            <i class="fas fa-passport mr-2 text-red-500"></i> CNI_Alice_Durand_Expiree.jpg
                        </td>
                        <td data-label="Employé Associé" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Alice Durand (#RH1012)
                        </td>
                        <td data-label="Type" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Pièce d'Identité
                        </td>
                        <td data-label="Expiration" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold text-red-600">
                            2024-03-15 (Expiré)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 ml-2">Expiré</span>
                        </td>
                        <td data-label="Actions" class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                            <button title="Visualiser" class="action-button"><i class="fas fa-eye text-lg"></i></button>
                            <button title="Télécharger" class="action-button"><i class="fas fa-download text-lg"></i></button>
                            <button title="Supprimer" class="action-button text-red-500 hover:text-red-700"><i class="fas fa-trash-alt text-lg"></i></button>
                        </td>
                    </tr>

                    <!-- Ligne 3: Document Actif (Diplôme) -->
                    <tr class="document-row lg:table-row hover:bg-gray-50 transition duration-150">
                        <td data-label="Nom du Document" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[var(--color-secondary)] hover:underline">
                            <i class="fas fa-graduation-cap mr-2 text-[var(--color-primary)]"></i> Master_Finance_PDupond.pdf
                        </td>
                        <td data-label="Employé Associé" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Pierre Dupond (#FIN0500)
                        </td>
                        <td data-label="Type" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Diplôme / Certificat
                        </td>
                        <td data-label="Expiration" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            N/A
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 ml-2">Valide</span>
                        </td>
                        <td data-label="Actions" class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                            <button title="Visualiser" class="action-button"><i class="fas fa-eye text-lg"></i></button>
                            <button title="Télécharger" class="action-button"><i class="fas fa-download text-lg"></i></button>
                            <button title="Supprimer" class="action-button text-red-500 hover:text-red-700"><i class="fas fa-trash-alt text-lg"></i></button>
                        </td>
                    </tr>
                    
                    <!-- Ligne 4: Document Bientôt Expiré (Habilitation) -->
                    <tr class="document-row lg:table-row hover:bg-gray-50 transition duration-150">
                        <td data-label="Nom du Document" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[var(--color-secondary)] hover:underline">
                            <i class="fas fa-id-card-alt mr-2 text-yellow-500"></i> Habilitation_Securite_JMartin.pdf
                        </td>
                        <td data-label="Employé Associé" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Julie Martin (#IT7001)
                        </td>
                        <td data-label="Type" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Document RH
                        </td>
                        <td data-label="Expiration" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold text-yellow-600">
                            2025-01-30
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 ml-2">À Renouveler</span>
                        </td>
                        <td data-label="Actions" class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                            <button title="Visualiser" class="action-button"><i class="fas fa-eye text-lg"></i></button>
                            <button title="Télécharger" class="action-button"><i class="fas fa-download text-lg"></i></button>
                            <button title="Supprimer" class="action-button text-red-500 hover:text-red-700"><i class="fas fa-trash-alt text-lg"></i></button>
                        </td>
                    </tr>

                    <!-- Pagination (Placeholder) -->
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 bg-gray-50">
                            <div class="flex justify-between items-center">
                                <span class="text-xs sm:text-sm">Affichage de 1 à 10 sur 532 résultats</span>
                                <div class="space-x-1">
                                    <button class="text-gray-400 p-2 rounded-lg border hover:bg-white transition duration-150" disabled><i class="fas fa-chevron-left"></i></button>
                                    <span class="px-3 py-1 bg-[var(--color-primary)] text-white rounded-lg text-sm font-medium">1</span>
                                    <button class="text-gray-600 p-2 rounded-lg border hover:bg-white transition duration-150"><i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Footer -->
        <footer class="mt-12 text-center text-xs text-gray-500 border-t border-gray-200 pt-4">
            &copy; 2025 Organisation Publique. Version Intranet 2.2.
        </footer>
    </div>
    
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>