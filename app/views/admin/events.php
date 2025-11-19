<?php 
                                                                $title = "Gestion des événements";
                                                                include APP_PATH . 'views/layouts/header.php'; 
                                                                include APP_PATH . 'views/layouts/navbar_admin.php'; 
?>

<div class="container-fluid py-4" style="background:#f8fafc; min-height:70vh;">
    <div class="container">
        <div class="row align-items-center mb-3">
            <div class="col-md-8">
                <h1 class="h3 mb-0">Événements</h1>
                <p class="text-muted small">Gérez vos événements, filtrez, éditez et suivez les statistiques.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="events/create" class="btn btn-primary btn-sm me-2">Créer un événement</a>
                <a href="admin" class="btn btn-outline-secondary btn-sm">Retour</a>
            </div>
        </div>

        <!-- Top metrics -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">Total événements</small>
                        <div class="h4 mt-2 mb-0 fw-bold counter" data-target="72">0</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">Événements à venir</small>
                        <div class="h4 mt-2 mb-0 fw-bold counter" data-target="12">0</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">Participants</small>
                        <div class="h4 mt-2 mb-0 fw-bold counter" data-target="894">0</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">Revenus</small>
                        <div class="h4 mt-2 mb-0 fw-bold counter" data-target="5420">0</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters + chart -->
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap align-items-center mb-3">
                            <input type="text" id="searchEvents" class="form-control form-control-sm" placeholder="Rechercher par titre ou organisateur" style="max-width:360px;">
                            <select id="statusFilter" class="form-select form-select-sm" style="max-width:160px;">
                                <option value="">Tous statuts</option>
                                <option value="upcoming">À venir</option>
                                <option value="past">Passés</option>
                                <option value="draft">Brouillon</option>
                            </select>
                            <input type="date" id="fromDate" class="form-control form-control-sm" style="max-width:160px;">
                            <input type="date" id="toDate" class="form-control form-control-sm" style="max-width:160px;">
                            <button class="btn btn-sm btn-outline-primary" id="applyFilters">Filtrer</button>
                        </div>

                        <canvas id="eventsChart" height="140"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Prochains événements</h5>
                        <ul class="list-group list-group-flush mt-3">
                            <li class="list-group-item d-flex justify-content-between align-items-start">Conférence X <span class="badge bg-secondary">2025-11-02</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">Atelier Y <span class="badge bg-secondary">2025-11-10</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">Webinaire Z <span class="badge bg-secondary">2025-11-18</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Liste des événements</h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-striped align-middle" id="eventsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Titre</th>
                                        <th>Organisateur</th>
                                        <th>Date</th>
                                        <th>Lieu</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>72</td>
                                        <td>Conférence X</td>
                                        <td>Jean</td>
                                        <td>2025-11-02</td>
                                        <td>Kinshasa</td>
                                        <td class="text-end"><a href="events/show/72" class="btn btn-sm btn-outline-primary me-1">Voir</a><a href="events/edit/72" class="btn btn-sm btn-primary">Éditer</a></td>
                                    </tr>
                                    <tr>
                                        <td>71</td>
                                        <td>Atelier Y</td>
                                        <td>Alice</td>
                                        <td>2025-11-10</td>
                                        <td>Liège</td>
                                        <td class="text-end"><a href="events/show/71" class="btn btn-sm btn-outline-primary me-1">Voir</a><a href="events/edit/71" class="btn btn-sm btn-primary">Éditer</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// counters
(function(){
    const counters = document.querySelectorAll('.counter[data-target]');
    counters.forEach(el => {
        const target = +el.getAttribute('data-target');
        const duration = 900;
        const startTime = performance.now();
        function step(now){
            const progress = Math.min((now - startTime) / duration, 1);
            el.textContent = Math.floor(progress * target).toLocaleString('fr-FR');
            if(progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    });
})();
</script>

<style>
    :root{ --primary: #4B0082; }
    .btn-primary, .btn-outline-primary{ background:var(--primary); border-color:var(--primary); }
    .btn-outline-primary{ color:#fff; }
    .card{ border-radius:12px; transition: transform .28s ease, box-shadow .28s ease; }
    .card:hover{ transform: translateY(-6px); box-shadow: 0 20px 40px rgba(75,0,130,0.08); }
    .table-hover tbody tr:hover{ background: rgba(75,0,130,0.04); }
</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    (function(){
        const ctx = document.getElementById('eventsChart');
        if(!ctx) return;
        new Chart(ctx, {
            type:'bar',
            data:{ labels:['Jan','Feb','Mar','Apr','May','Jun','Jul'], datasets:[{ label:'Événements', data:[4,6,3,8,7,5,9], backgroundColor:'rgba(75,0,130,0.9)'}] },
            options:{ responsive:true, plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true } } }
        });
    })();

    // small client-side filter (non-persistent)
    document.addEventListener('DOMContentLoaded', function(){
        const search = document.getElementById('searchEvents');
        const apply = document.getElementById('applyFilters');
        apply && apply.addEventListener('click', function(){
            const q = (search && search.value || '').toLowerCase();
            const rows = document.querySelectorAll('#eventsTable tbody tr');
            rows.forEach(r => {
                const text = r.innerText.toLowerCase();
                r.style.display = text.includes(q) ? '' : 'none';
            });
        });
    });
</script>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>