<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
?>
</head>
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php'; 
    ?>

    <main class="flex-grow container mx-auto px-4 sm:px-6 py-5 sm:py-1 max-w-7xl"
        x-data="library({
            initialBooks: <?= htmlspecialchars(json_encode(array_map(function($Livre) {
                return [
                    'id' => $Livre->livre_id,
                    'titre' => $Livre->titre,
                    'auteur' => $Livre->auteur,
                    'image' => $Livre->url_couverture ?? '',
                    'categorie' => $Livre->categorie, // ou un autre attribut de votre objet
                    'description' => $Livre->description ?? 'Découvrez cette magnifique ressource spirituelle dans notre catalogue...'
                ];
            }, $Livres)), ENT_QUOTES, 'UTF-8') ?>
        })">

        <main class="flex-grow container mx-auto px-4 sm:px-6 py-6 max-w-7xl">
            
            <!-- Hero Header Section -->
            <div class="text-center max-w-2xl mx-auto mb-6">
                <span class="text-[10px] font-bold text-primary tracking-widest bg-brand-100 px-3 py-1 rounded-full">Collection Impériale</span>
                <h1 class="text-2xl sm:text-4xl font-serif font-bold text-primary mt-4 mb-4 leading-tight">
                    Trouvez votre prochaine <span class="italic text-primary font-normal">source de sagesse</span>
                </h1>
                <p class="text-sm text-white leading-relaxed font-light">
                    Explorez une sélection soigneusement préservée d'œuvres spirituelles, de philosophies anciennes et contemporaines pour nourrir votre esprit.
                </p>
            </div>

            <div class="bg-secondary rounded-3xl color-border p-5 sm:p-6 shadow-sm mb-12 space-y-5">
                <div class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center justify-between">
                    
                    <!-- Barre de Recherche Stylisée -->
                    <div class="relative flex-grow max-w-23xl">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            x-model="searchQuery"
                            placeholder="Rechercher par titre, auteur ou thématique..." 
                            class="w-full bg-paper pl-11 pr-10 py-3.5 color-border rounded-2xl text-sm text-primary placeholder-primary focus:ring-4 focus:ring-primary outline-none transition-all duration-300"
                        />
                        <!-- Bouton Clear -->
                        <button 
                            x-show="searchQuery.length > 0" 
                            @click="searchQuery = ''"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-primary hover:text-stone-600 transition-colors"
                            style="display: none;"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Sélecteur de Tri & Catégorie Mobile/Desktop -->
                    <div class="hidden md:flex flex-wrap items-center gap-3">
                        <!-- Filtre par catégorie -->
                        <select 
                            x-model="selectedCategory"
                            class="px-4 py-3 bg-paper color-border focus:border-primary rounded-2xl text-primary text-sm focus:ring-4 focus:ring-primary/10 outline-none transition-all duration-300 text-stone-700 font-medium"
                        >
                            <option value="">Toutes les Catégories</option>
                            <template x-for="cat in categories" :key="cat">
                                <option :value="cat" x-text="cat"></option>
                            </template>
                        </select>

                        <!-- Nombre d'items par page -->
                        <select 
                            x-model.number="itemsPerPage"
                            class="px-4 py-3 bg-paper color-border focus:border-primary rounded-2xl text-primary text-sm focus:ring-4 focus:ring-primary/10 outline-none transition-all duration-300 text-stone-700 font-medium"
                        >
                            <option value="4">4 par page</option>
                            <option value="8">8 par page</option>
                            <option value="12">12 par page</option>
                        </select>
                    </div>
                </div>

                <!-- Tags / Filtres rapides en un clic -->
                <div class="flex flex-wrap gap-2 items-center pt-2 border-t border-brand-100">
                    <button 
                        @click="selectedCategory = ''"
                        :class="selectedCategory === '' ? 'bg-primary shadow-md shadow-primary/10' : 'bg-brand-50 border border-primary text-primary hover:bg-brand-100'"
                        class="px-3.5 py-1.5 border border-primary rounded-full text-xs font-medium transition-all duration-200"
                    >
                        Tout voir
                    </button>
                    <template x-for="cat in categories" :key="cat">
                        <button 
                            @click="selectedCategory = cat"
                            :class="selectedCategory === cat ? 'bg-primary shadow-md shadow-primary/10' : 'bg-brand-50 border border-primary text-primary hover:bg-brand-100'"
                            class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-all duration-200"
                            x-text="cat"
                        ></button>
                    </template>
                </div>
            </div>

            <!-- Si aucun livre ne correspond -->
            <div 
                x-show="filteredBooks.length === 0" 
                class="text-center py-16 bg-secondary rounded-3xl color-border border-dashed"
                style="display: none;"
            >
                <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-primary">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <h3 class="font-serif text-xl font-bold text-primary mb-1">Aucun ouvrage trouvé</h3>
                <p class="text-sm text-white max-w-sm mx-auto">Nous n'avons pas trouvé de résultat pour votre recherche. Essayez avec d'autres mots-clés ou modifiez vos filtres.</p>
                <button @click="resetFilters()" class="mt-4 inline-flex items-center gap-2 text-xs font-semibold text-primary hover:text-brand-800 transition-colors">
                    Réinitialiser les filtres
                </button>
            </div>

            <!-- Grille des livres (8 colonnes sur très grand écran pour un look de vraie bibliothèque dense et haut de gamme, adaptable sur mobile) -->
            <div 
                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6 sm:gap-8"
                x-show="filteredBooks.length > 0"
            >
                <template x-for="book in paginatedBooks" :key="book.id">
                    <div 
                        class="book-card group flex flex-col justify-between h-full bg-transparent transition-all duration-300 ease-out"
                        x-data="{ isHovered: false }"
                        @mouseenter="isHovered = true"
                        @mouseleave="isHovered = false"
                    >
                        <!-- Partie couverture : format livre élégant -->
                        <div class="relative w-full aspect-[3/4.2] rounded-2xl overflow-hidden book-shadow bg-stone-100 border border-brand-200/50 transition-all duration-300 group-hover:-translate-y-2 group-hover:scale-[1.02]">
                            
                            <!-- Simulation de tranche de livre gauche pour réalisme tactile (Spine effect) -->
                            <div class="absolute inset-y-0 left-0 w-3 bg-gradient-to-r from-black/25 via-black/5 to-transparent z-10"></div>
                            
                            <!-- Couverture -->
                            <template x-if="book.image">
                                <img :src="book.image" 
                                     :alt="'Couverture de ' + book.titre"
                                     class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                                     loading="lazy">
                            </template>
                            
                            <!-- Placeholder si pas d'image (esthétique luxe, style édition de prestige blanche) -->
                            <template x-if="!book.image">
                                <div class="w-full h-full flex flex-col items-center justify-between p-4 bg-gradient-to-b from-secondary to-secondary text-brand-900 border-l-4 border-primary">
                                    <div class="w-full text-center pt-4">
                                        <p class="text-[9px] uppercase tracking-wider font-semibold text-primary" x-text="book.categorie"></p>
                                    </div>
                                    <div class="text-center px-1">
                                        <h3 class="font-serif font-bold text-xs leading-tight text-brand-800" x-text="book.titre"></h3>
                                        <p class="text-[9px] text-primary italic mt-1" x-text="book.auteur"></p>
                                    </div>
                                    <div class="pb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="w-6 h-6 text-brand-400 opacity-60">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                        </svg>
                                    </div>
                                </div>
                            </template>

                            <!-- Hover Overlay Premium avec description et bouton rapide -->
                            <div class="absolute inset-0 bg-secondary flex flex-col justify-between p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
                                <div class="space-y-2">
                                    <span class="inline-block text-[9px] font-bold tracking-widest text-brand-300 uppercase bg-brand-800/80 px-2 py-0.5 rounded" x-text="book.categorie"></span>
                                    <p class="text-[11px] text-stone-200 line-clamp-5 leading-relaxed" x-text="book.description"></p>
                                </div>
                                <div class="space-y-2">
                                    <!-- Bouton Détails (Modal / Animation) -->
                                    <a :href="'livres/show/' + book.id"
                                        class="w-full py-2 bg-primary hover:bg-brand-400 text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1.5"
                                    >
                                        <span>Consulter</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informations du Livre dessous la couverture -->
                        <div class="mt-4 px-1 flex flex-col justify-between flex-grow">
                            <div>
                                <!-- Catégorie discrète -->
                                <p class="text-[9px] uppercase tracking-wider text-primary font-semibold mb-1" x-text="book.categorie"></p>
                                
                                <!-- Titre -->
                                <!-- Remplacez par votre lien de routage : href="livres/show/<?= $Livre->livre_id ?>" -->
                                <a :href="'livres/show/' + book.id" class="block group/link">
                                    <h3 
                                        class="text-xs sm:text-sm font-bold text-white line-clamp-1 group-hover/link:text-primary transition-colors duration-200" 
                                        :title="book.titre" 
                                        x-text="book.titre"
                                    ></h3>
                                </a>
                                
                                <!-- Auteur -->
                                <p class="text-[11px] text-gray-400 mt-0.5" x-text="book.auteur"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Système de Pagination Réactif -->
            <div 
                x-show="totalPages > 1"
                class="mt-16 border-t border-brand-200 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4"
                style="display: none;"
            >
                <div class="text-xs text-white font-medium">
                    Affichage de <span class="text-stone-900 font-bold" x-text="startIndex + 1"></span> à 
                    <span class="text-stone-900 font-bold" x-text="Math.min(endIndex, filteredBooks.length)"></span> sur 
                    <span class="text-stone-900 font-bold" x-text="filteredBooks.length"></span> ouvrages
                </div>

                <div class="inline-flex items-center gap-1.5 bg-white p-1.5 rounded-2xl border border-brand-200">
                    <!-- Bouton Précédent -->
                    <button 
                        @click="prevPage()" 
                        :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'text-stone-300 cursor-not-allowed' : 'text-stone-600 hover:bg-brand-50 hover:text-brand-700'"
                        class="p-2.5 rounded-xl transition-all"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>

                    <!-- Numéros de pages dynamiques -->
                    <template x-for="page in totalPages" :key="page">
                        <button 
                            @click="setPage(page)" 
                            :class="currentPage === page ? 'bg-primary text-white font-bold shadow-md shadow-primary/10' : 'text-stone-600 hover:bg-brand-50'"
                            class="w-10 h-10 rounded-xl text-xs font-semibold transition-all"
                            x-text="page"
                        ></button>
                    </template>

                    <!-- Bouton Suivant -->
                    <button 
                        @click="nextPage()" 
                        :disabled="currentPage === totalPages"
                        :class="currentPage === totalPages ? 'text-stone-300 cursor-not-allowed' : 'text-stone-600 hover:bg-brand-50 hover:text-brand-700'"
                        class="p-2.5 rounded-xl transition-all"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </main>

        <!-- Modal de Consultation Rapide des livres -->
        <div 
            x-show="isModalOpen" 
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay de fond flouté -->
                <div class="fixed inset-0 transition-opacity bg-stone-900/40" @click="closeModal()"></div>

                <!-- Centrer le modal de manière élégante -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <!-- Contenu du Modal -->
                <div 
                    class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-brand-200"
                    x-show="isModalOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <!-- Header / Close button -->
                    <div class="absolute top-4 right-4 z-10">
                        <button @click="closeModal()" class="p-2 text-primary hover:text-stone-700 hover:bg-stone-100 rounded-full transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex flex-col md:flex-row">
                        <!-- Visuel Couverture Gauche -->
                        <div class="w-full md:w-2/5 bg-gradient-to-br from-brand-100 to-brand-50 p-8 flex items-center justify-center">
                            <div class="w-40 aspect-[3/4.2] rounded-xl overflow-hidden book-shadow bg-white relative">
                                <div class="absolute inset-y-0 left-0 w-2.5 bg-gradient-to-r from-black/20 via-black/5 to-transparent z-10"></div>
                                <template x-if="selectedBook.image">
                                    <img :src="selectedBook.image" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!selectedBook.image">
                                    <div class="w-full h-full flex flex-col items-center justify-center text-center p-3 text-brand-900 bg-brand-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="w-8 h-8 mb-2 text-brand-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                        </svg>
                                        <span class="text-[9px] font-semibold uppercase tracking-wider text-primary">Pas de couverture</span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Détails Texte Droite -->
                        <div class="w-full md:w-3/5 p-6 sm:p-8 flex flex-col justify-between">
                            <div>
                                <span class="inline-block text-[10px] font-bold tracking-widest text-primary bg-brand-100 px-2.5 py-1 rounded-full uppercase mb-3" x-text="selectedBook.categorie"></span>
                                <h3 class="font-serif text-2xl font-bold text-brand-900 mb-1 leading-tight" x-text="selectedBook.titre"></h3>
                                <p class="text-sm text-white italic mb-4" x-text="'par ' + selectedBook.auteur"></p>
                                
                                <h4 class="text-xs font-bold text-primary uppercase tracking-wider mb-2">Synopsis</h4>
                                <p class="text-sm text-stone-600 leading-relaxed font-light" x-text="selectedBook.description"></p>
                            </div>

                            <div class="mt-6 pt-6 border-t border-brand-100 flex items-center justify-end gap-3">
                                <button @click="closeModal()" class="px-4 py-2 text-xs font-semibold text-stone-600 hover:text-stone-900 transition-colors">
                                    Fermer
                                </button>
                                <a :href="'livres/show/' + selectedBook.id" class="px-5 py-2.5 bg-primary hover:bg-primary text-white text-xs font-semibold rounded-xl shadow-md shadow-primary/10 transition-colors flex items-center gap-1.5">
                                    <span>Accéder au livre</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    
    <script>
        function library({ initialBooks = [] }) {
            return {
                // Données brutes des livres
                books: initialBooks,
                
                // États du moteur de recherche et filtrage
                searchQuery: '',
                selectedCategory: '',
                
                // États de la pagination
                currentPage: 1,
                itemsPerPage: 6,

                // État de la vue modale d'aperçu rapide
                isModalOpen: false,
                selectedBook: {},

                // Calcul dynamique des catégories présentes dans les livres
                get categories() {
                    return [...new Set(this.books.map(b => b.categorie))].filter(Boolean);
                },

                // Filtre les livres dynamiquement selon la recherche et la catégorie
                get filteredBooks() {
                    const query = this.searchQuery.toLowerCase().trim();
                    const filtered = this.books.filter(book => {
                        const matchQuery = !query || 
                            book.titre.toLowerCase().includes(query) || 
                            book.auteur.toLowerCase().includes(query) ||
                            (book.description && book.description.toLowerCase().includes(query));
                        
                        const matchCat = !this.selectedCategory || book.categorie === this.selectedCategory;
                        
                        return matchQuery && matchCat;
                    });

                    // Réinitialise à la page 1 si les résultats changent de manière à invalider la pagination actuelle
                    return filtered;
                },

                // Récupère uniquement le lot de livres de la page active (Pagination)
                get paginatedBooks() {
                    // Si on change de recherche, on s'assure de ne pas être bloqué sur une page trop éloignée
                    if (this.currentPage > this.totalPages) {
                        this.currentPage = Math.max(1, this.totalPages);
                    }
                    return this.filteredBooks.slice(this.startIndex, this.endIndex);
                },

                // Calculs d'index de pagination
                get startIndex() {
                    return (this.currentPage - 1) * this.itemsPerPage;
                },
                get endIndex() {
                    return this.currentPage * this.itemsPerPage;
                },
                get totalPages() {
                    return Math.ceil(this.filteredBooks.length / this.itemsPerPage) || 1;
                },

                // Méthodes de contrôle de page
                setPage(page) {
                    this.currentPage = page;
                },
                prevPage() {
                    if (this.currentPage > 1) this.currentPage--;
                },
                nextPage() {
                    if (this.currentPage < this.totalPages) this.currentPage++;
                },

                // Réinitialise les filtres
                resetFilters() {
                    this.searchQuery = '';
                    this.selectedCategory = '';
                    this.currentPage = 1;
                },

                // Gestion de la modale
                openDetails(book) {
                    this.selectedBook = book;
                    this.isModalOpen = true;
                },
                closeModal() {
                    this.isModalOpen = false;
                }
            };
        }
    </script>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>