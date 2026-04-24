    <footer class="<?= Helper::getUrlPart()[0] === 'bolokele' ? 'bg-[#130121]' : 'bg-paper' ?> text-primary py-12 mt-20">
        <div class="container mx-auto px-6 text-center text-xs">
            <p>&copy; <?= date('Y') ?> Le Sanctuaire. Le respect de la vie privée est au cœur de notre engagement.</p>
        </div>
    </footer>
    
    <!-- Alpine.js Core -->
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- App js -->
    <script src="<?= ASSETS ?>js/app.js?v=<?= APP_VERSION ?>"></script>
</body>
</html>