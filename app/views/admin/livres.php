<?php 
    $title = "Admin - Livres";
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 

    $searchIndex = [];
    foreach ($Livres as $index => $book) {
        $searchIndex[] = [
            'index' => $index,
            'titre' => mb_strtolower($book->titre ?? '', 'UTF-8'),
            'auteur' => mb_strtolower($book->auteur ?? '', 'UTF-8'),
            'description' => mb_strtolower($book->description ?? '', 'UTF-8'),
            'categorie' => mb_strtolower($book->categorie ?? '', 'UTF-8')
        ];
    }
?>

    <main class="min-h-screen py-12 px-4 sm:px-6 lg:px-8" 
        x-data="{
            search: '',
            page: 1,
            perPage: 5,
            booksIndex: <?= htmlspecialchars(json_encode($searchIndex), ENT_QUOTES, 'UTF-8') ?>,
            
            // Retourne les clés/index des livres correspondants à la recherche
            get filteredIndices() {
                const term = this.search.trim().toLowerCase();
                if (term === '') {
                    return this.booksIndex.map(b => b.index);
                }
                return this.booksIndex
                    .filter(b => 
                        b.titre.includes(term) || 
                        b.auteur.includes(term) || 
                        b.description.includes(term) || 
                        b.categorie.includes(term)
                    )
                    .map(b => b.index);
            },

            // Calcule dynamiquement le nombre total de pages réelles
            get totalPages() {
                return Math.ceil(this.filteredIndices.length / this.perPage) || 1;
            },

            // Retourne la tranche des index à afficher sur la page actuelle
            get paginatedIndices() {
                const start = (this.page - 1) * this.perPage;
                const end = start + this.perPage;
                return this.filteredIndices.slice(start, end);
            },

            // Détermine si une ligne PHP spécifique doit être visible ou non
            isRowVisible(index) {
                return this.paginatedIndices.includes(index);
            },

            resetPage() {
                this.page = 1;
            }
        }">

        <div class="max-w-7xl mx-auto space-y-8">
            
            <!-- EN-TÊTE DE LA PAGE -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 pb-6 border-b border-white/5">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#cfbb30]"></span>
                        <h1 class="text-xs font-black text-white uppercase tracking-[0.3em] opacity-80">Bibliothèque</h1>
                    </div>
                    <h2 class="text-3xl font-extrabold text-white tracking-tight">
                        Gestion des Livres 
                        <span class="text-sm font-normal text-gray-500 block sm:inline sm:ml-2">(<?= count($Livres) ?> au total en base)</span>
                    </h2>
                    <p class="text-sm text-gray-400 mt-1">Gérez le catalogue des ouvrages et documents disponibles au sein de la communauté.</p>
                </div>
                
                <!-- Bouton Ajouter un livre -->
                <a href="ajouter_livre.php" 
                class="group relative inline-flex items-center justify-center px-6 py-3.5 font-bold text-black transition-all duration-300 bg-[#cfbb30] rounded-full hover:shadow-[0_0_30px_rgba(207,187,48,0.4)] hover:-translate-y-1">
                    <span class="relative z-10 flex items-center gap-2 uppercase text-xs tracking-wider">
                        <i class="fas fa-plus text-[11px] transition-transform group-hover:rotate-90 duration-300"></i> 
                        Ajouter un livre
                    </span>
                </a>
            </div>

            <!-- MODULE DE RECHERCHE -->
            <div class="backdrop-blur-xl rounded-[2rem] p-6 bg-white/[0.02] color-border shadow-xl">
                <div class="relative max-w-lg">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-500 text-sm"></i>
                    </div>
                    <input 
                        type="text" 
                        placeholder="Rechercher par titre, auteur, catégorie..." 
                        x-model="search"
                        @input="resetPage()"
                        class="flex w-full pl-11 pr-4 py-3 bg-white/5 color-border rounded-2xl text-sm text-primary placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#cfbb30]/50 focus:border-[#cfbb30]/50 transition-all duration-300"
                    />
                </div>
            </div>

            <!-- TABLEAU DES LIVRES -->
            <div class="backdrop-blur-xl rounded-[2.5rem] bg-white/[0.02] color-border shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/[0.01]">
                                <th class="py-5 px-6 text-xs font-black text-white uppercase tracking-wider opacity-60">Livre</th>
                                <th class="py-5 px-6 text-xs font-black text-white uppercase tracking-wider opacity-60">Auteur</th>
                                <th class="py-5 px-6 text-xs font-black text-white uppercase tracking-wider opacity-60">Catégorie</th>
                                <th class="py-5 px-6 text-xs font-black text-white uppercase tracking-wider opacity-60 text-center">Date d'Ajout</th>
                                <th class="py-5 px-6 text-xs font-black text-white uppercase tracking-wider opacity-60 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary">
                            
                            <!-- Boucle PHP Foreach d'origine pour afficher ABSOLUMENT tous les livres physiquement dans le DOM -->
                            <?php foreach ($Livres as $index => $book): ?>
                                <?php 
                                    // Calcul ultra-robuste et sécurisé de l'URL de la couverture en PHP
                                    $coverUrl = '';
                                    if (!empty($book->url_couverture)) {
                                        $url = $book->url_couverture;
                                        // Si c'est déjà une adresse externe absolue ou relative parent
                                        if (strpos($url, 'http') === 0 || strpos($url, '../') === 0) {
                                            $coverUrl = $url;
                                        } else {
                                            // Enlever le premier slash superflu pour éviter les double-slashes
                                            if (strpos($url, '/') === 0) {
                                                $url = substr($url, 1);
                                            }
                                            $coverUrl = '../' . $url;
                                        }
                                    }
                                ?>
                                <tr class="hover:bg-white/[0.02] transition-colors group"
                                    x-show="isRowVisible(<?= $index ?>)"
                                    style="display: none;">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-4">
                                            <!-- Vignette ou icône de livre -->
                                            <div class="w-11 h-15 rounded-lg bg-white/5 color-border flex items-center justify-center overflow-hidden flex-shrink-0 group-hover:border-[#cfbb30]/30 transition-colors">
                                                <?php if (!empty($coverUrl)): ?>
                                                    <img src="<?= htmlspecialchars($coverUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Couverture" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <i class="fas fa-book text-gray-500 text-lg"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-white group-hover:text-[#cfbb30] transition-colors"><?= htmlspecialchars($book->titre ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                                                <p class="text-xs text-gray-500 mt-1 line-clamp-1"><?= htmlspecialchars($book->description ?? 'Aucune description disponible', ENT_QUOTES, 'UTF-8') ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-300 font-medium"><?= htmlspecialchars($book->auteur ?? 'Inconnu', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#cfbb30]/10 border border-[#cfbb30]/20 text-[#cfbb30]"><?= htmlspecialchars($book->categorie ?? 'Général', ENT_QUOTES, 'UTF-8') ?></span>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-400 text-center">
                                        <?= !empty($book->created_at) ? date('d/m/Y', strtotime($book->created_at)) : '-' ?>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <!-- Modifier -->
                                            <a href="modifier_livre.php?id=<?= $book->livre_id ?>" 
                                            class="p-2 rounded-xl color-border bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all duration-300"
                                            title="Modifier l'ouvrage">
                                                <i class="fas fa-pen text-xs"></i>
                                            </a>
                                            <!-- Supprimer -->
                                            <a href="supprimer_livre.php?id=<?= $book->livre_id ?>" 
                                            class="p-2 rounded-xl color-border bg-white/5 text-red-400 hover:text-white hover:bg-red-500/80 hover:border-red-500/40 transition-all duration-300"
                                            title="Supprimer l'ouvrage">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <!-- Message si aucun livre trouvé -->
                            <tr x-show="filteredIndices.length === 0" style="display: none;">
                                <td colspan="5" class="py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <i class="fas fa-folder-open text-3xl opacity-20"></i>
                                        <p class="text-sm">Aucun livre ne correspond à votre recherche.</p>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div x-show="totalPages > 1" 
                    style="display: none;" 
                    class="flex flex-col sm:flex-row items-center justify-between gap-4 p-6 border-t border-white/5 bg-white/[0.01]">
                    
                    <span class="text-xs text-gray-400">
                        Affichage de <span class="text-white font-bold" x-text="filteredIndices.length > 0 ? (page - 1) * perPage + 1 : 0"></span> 
                        à <span class="text-white font-bold" x-text="Math.min(page * perPage, filteredIndices.length)"></span> 
                        sur <span class="text-white font-bold" x-text="filteredIndices.length"></span> livre(s)
                    </span>

                    <div class="flex items-center gap-3">
                        <button 
                            @click="page > 1 ? page-- : null" 
                            :disabled="page === 1"
                            class="px-4 py-2 text-xs font-semibold rounded-xl color-border bg-white/5 text-white hover:bg-white/10 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                            <i class="fas fa-chevron-left mr-1.5 text-[9px]"></i> Précédent
                        </button>
                        
                        <span class="text-xs text-gray-400">
                            Page <span x-text="page" class="text-white font-bold"></span> sur <span x-text="totalPages" class="text-white font-bold"></span>
                        </span>
                        
                        <button 
                            @click="page < totalPages ? page++ : null" 
                            :disabled="page === totalPages"
                            class="px-4 py-2 text-xs font-semibold rounded-xl color-border bg-white/5 text-white hover:bg-white/10 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                            Suivant <i class="fas fa-chevron-right ml-1.5 text-[9px]"></i>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </main>

</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>