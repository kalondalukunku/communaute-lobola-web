    <footer class="<?= Helper::getUrlPart()[0] === 'bolokele' ? 'bg-[#130121]' : 'bg-paper' ?> text-primary py-12 mt-20">
        <div class="container mx-auto px-6 text-center text-xs">
            <p>&copy; <?= date('Y') ?> Le Sanctuaire. Le respect de la vie privée est au cœur de notre engagement.</p>
        </div>
    </footer>
    
    <!-- Alpine.js Core -->
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminTable', (rows, perPage = 10, activeStatus = null) => ({
                search: '',
                page: 1,
                perPage,
                rows,
                activeStatus,
                visibleIndices: {},

                init() {
                    this.updateVisibleIndices();
                    this.$watch('page', () => this.updateVisibleIndices());
                },

                get filteredRows() {
                    const term = this.search.trim().toLowerCase();
                    return this.rows.filter(row =>
                        (!this.activeStatus || row.status === this.activeStatus) &&
                        (!term || row.search.includes(term))
                    );
                },

                get statusCounts() {
                    return this.rows.reduce((counts, row) => {
                        counts[row.status] = (counts[row.status] || 0) + 1;
                        return counts;
                    }, {});
                },

                get totalPages() {
                    return Math.max(1, Math.ceil(this.filteredRows.length / this.perPage));
                },

                updateVisibleIndices() {
                    const start = (this.page - 1) * this.perPage;
                    this.visibleIndices = {};
                    this.filteredRows.slice(start, start + this.perPage).forEach(row => {
                        this.visibleIndices[row.index] = true;
                    });
                },

                isRowVisible(index) {
                    return Boolean(this.visibleIndices[index]);
                },

                resetPage() {
                    this.page = 1;
                    this.updateVisibleIndices();
                },

                setStatus(status) {
                    this.activeStatus = status;
                    this.resetPage();
                }
            }));
        });
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- App js -->
    <script src="<?= ASSETS ?>js/app.js?v=<?= APP_VERSION ?>"></script>
</body>
</html>