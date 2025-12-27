<?php 
        $title = "Tableau de bord";
        include APP_PATH . 'views/layouts/header.php'; 
        include APP_PATH . 'views/layouts/navbar_admin.php'; 
?>

<div class="container-fluid py-4" style="background:#f8fafc; min-height:70vh;">
        <div class="container">
                <div class="row align-items-center mb-3">
                        <div class="col-md-8">
                                <h1 class="h3 mb-0">Tableau de bord</h1>
                                <p class="text-muted small">Aperçu des statistiques et activités récentes</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="admin/add" class="btn btn-primary btn-sm me-2">Nouvel élément</a>
                                <a href="user/logout" class="btn btn-outline-secondary btn-sm">Déconnexion</a>
                        </div>
                </div>

                <!-- Metrics row -->
                <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                                <div class="card shadow-sm h-100">
                                        <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                                <small class="text-muted">Membres</small>
                                                                <div class="h4 mt-2 mb-0 fw-bold counter" data-target="1284">0</div>
                                                        </div>
                                                        <div class="text-success small">+6.2%</div>
                                                </div>
                                        </div>
                                </div>
                        </div>

                        <div class="col-6 col-md-3">
                                <div class="card shadow-sm h-100">
                                        <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                                <small class="text-muted">Evénements</small>
                                                                <div class="h4 mt-2 mb-0 fw-bold counter" data-target="72">0</div>
                                                        </div>
                                                        <div class="text-danger small">-1.4%</div>
                                                </div>
                                        </div>
                                </div>
                        </div>

                        <div class="col-6 col-md-3">
                                <div class="card shadow-sm h-100">
                                        <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                                <small class="text-muted">Produits</small>
                                                                <div class="h4 mt-2 mb-0 fw-bold counter" data-target="342">0</div>
                                                        </div>
                                                        <div class="text-success small">+2.8%</div>
                                                </div>
                                        </div>
                                </div>
                        </div>

                        <div class="col-6 col-md-3">
                                <div class="card shadow-sm h-100">
                                        <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                                <small class="text-muted">Revenus (Mois)</small>
                                                                <div class="h4 mt-2 mb-0 fw-bold counter" data-target="12480">0</div>
                                                        </div>
                                                        <div class="text-success small">+12%</div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>

                                <div class="row g-3">
                                        <div class="col-lg-8">
                                                <div class="card shadow-sm">
                                                        <div class="card-body">
                                                                <div class="row">
                                                                        <div class="col-md-6">
                                                                                <h5 class="card-title">Graphiques</h5>
                                                                                <canvas id="revenueChart" height="180"></canvas>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                                <h5 class="card-title">Inscriptions</h5>
                                                                                <canvas id="signupChart" height="180"></canvas>
                                                                        </div>
                                                                </div>

                                                                <hr class="my-3">

                                                                <h6 class="mb-2">Activités récentes</h6>
                                                                <div class="table-responsive">
                                                                        <table class="table table-hover align-middle">
                                                                                <thead>
                                                                                        <tr class="small text-muted"><th>Activité</th><th>Utilisateur</th><th>Date</th><th class="text-end">Référence</th></tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                        <tr>
                                                                                                <td>Nouvel événement ajouté</td>
                                                                                                <td>Jean</td>
                                                                                                <td class="text-muted small">2 heures</td>
                                                                                                <td class="text-end text-muted">#72</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td>Nouveau membre</td>
                                                                                                <td>Alice</td>
                                                                                                <td class="text-muted small">5 heures</td>
                                                                                                <td class="text-end text-muted">#1284</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td>Commande payée</td>
                                                                                                <td>Kofi</td>
                                                                                                <td class="text-muted small">1 jour</td>
                                                                                                <td class="text-end text-muted">#523</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td>Produit modifié</td>
                                                                                                <td>Admin</td>
                                                                                                <td class="text-muted small">2 jours</td>
                                                                                                <td class="text-end text-muted">#342</td>
                                                                                        </tr>
                                                                                </tbody>
                                                                        </table>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>

                                        <div class="col-lg-4">
                                                <div class="card shadow-sm mb-3">
                                                        <div class="card-body">
                                                                <h5 class="card-title">Tâches rapides</h5>
                                                                <ul class="list-group list-group-flush mt-3">
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">Valider utilisateur <a href="#" class="btn btn-sm btn-outline-primary">Faire</a></li>
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">Publier événement <a href="#" class="btn btn-sm btn-outline-primary">Faire</a></li>
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">Répondre aux messages <a href="#" class="btn btn-sm btn-outline-primary">Faire</a></li>
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">Exporter rapports <a href="#" class="btn btn-sm btn-outline-primary">Faire</a></li>
                                                                </ul>
                                                        </div>
                                                </div>

                                                <div class="card shadow-sm">
                                                        <div class="card-body">
                                                                <h5 class="card-title">Liste des membres</h5>
                                                                <div class="table-responsive mt-3" style="max-height:220px; overflow:auto;">
                                                                        <table class="table table-sm table-hover mb-0">
                                                                                <thead>
                                                                                        <tr class="small text-muted"><th>Nom</th><th>Email</th><th class="text-end">Inscrit</th></tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                        <tr>
                                                                                                <td>Jean Dupont</td>
                                                                                                <td>jean@example.com</td>
                                                                                                <td class="text-end text-muted">2025-10-20</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td>Alice Martin</td>
                                                                                                <td>alice@example.com</td>
                                                                                                <td class="text-end text-muted">2025-10-24</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td>Kofi Mensah</td>
                                                                                                <td>kofi@example.com</td>
                                                                                                <td class="text-end text-muted">2025-10-27</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td>Fatou Diop</td>
                                                                                                <td>fatou@example.com</td>
                                                                                                <td class="text-end text-muted">2025-10-10</td>
                                                                                        </tr>
                                                                                </tbody>
                                                                        </table>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                </div>

                                <!-- Members & Users full-width tables -->
                                <div class="row mt-4">
                                        <div class="col-lg-6">
                                                <div class="card shadow-sm">
                                                        <div class="card-body">
                                                                <h5 class="card-title">Tableau — Membres</h5>
                                                                <div class="table-responsive mt-3">
                                                                        <table class="table table-hover">
                                                                                <thead>
                                                                                        <tr><th>#</th><th>Nom</th><th>Email</th><th>Rôle</th><th class="text-end">Actions</th></tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                        <tr>
                                                                                                <td>1</td>
                                                                                                <td>Jean Dupont</td>
                                                                                                <td>jean@example.com</td>
                                                                                                <td>Member</td>
                                                                                                <td class="text-end"><a href="#" class="btn btn-sm btn-outline-primary">Voir</a></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td>2</td>
                                                                                                <td>Alice Martin</td>
                                                                                                <td>alice@example.com</td>
                                                                                                <td>Member</td>
                                                                                                <td class="text-end"><a href="#" class="btn btn-sm btn-outline-primary">Voir</a></td>
                                                                                        </tr>
                                                                                </tbody>
                                                                        </table>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>

                                        <div class="col-lg-6">
                                                <div class="card shadow-sm">
                                                        <div class="card-body">
                                                                <h5 class="card-title">Tableau — Utilisateurs</h5>
                                                                <div class="table-responsive mt-3">
                                                                        <table class="table table-hover">
                                                                                <thead>
                                                                                        <tr><th>#</th><th>Utilisateur</th><th>Dernière connexion</th><th class="text-end">Statut</th></tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                        <tr>
                                                                                                <td>1</td>
                                                                                                <td>admin</td>
                                                                                                <td class="text-muted">2025-10-28</td>
                                                                                                <td class="text-end"><span class="badge bg-success">Actif</span></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td>2</td>
                                                                                                <td>editor</td>
                                                                                                <td class="text-muted">2025-10-25</td>
                                                                                                <td class="text-end"><span class="badge bg-secondary">Inactif</span></td>
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
        // Simple counter animation using requestAnimationFrame
        (function(){
                const counters = document.querySelectorAll('.counter[data-target]');
                counters.forEach(el => {
                        const target = +el.getAttribute('data-target');
                        const duration = 1000;
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
        .btn-primary, .btn-outline-primary{ background: var(--primary); border-color: var(--primary); }
        .btn-outline-primary{ color:#fff; }
        .card{ border-radius:12px; transition: transform .28s ease, box-shadow .28s ease; }
        .card:hover{ transform: translateY(-6px); box-shadow: 0 20px 40px rgba(75,0,130,0.08); }
        .table-hover tbody tr:hover{ background: rgba(75,0,130,0.04); }
        .badge.bg-success{ background:#16a34a; }
        .badge.bg-secondary{ background:#6b7280; }
</style>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
        // Revenue chart
        (function(){
                const ctx = document.getElementById('revenueChart');
                if(ctx){
                        new Chart(ctx, {
                                type: 'line',
                                data: {
                                        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul'],
                                        datasets:[{ label:'Revenus', data:[4200, 5100, 4800, 6200, 7100, 6800, 7400], borderColor: 'rgba(75,0,130,0.9)', backgroundColor: 'rgba(75,0,130,0.12)', tension:0.35, fill:true }]
                                },
                                options:{ responsive:true, plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:false } } }
                        });
                }
        })();

        // Signups bar chart
        (function(){
                const ctx = document.getElementById('signupChart');
                if(ctx){
                        new Chart(ctx, {
                                type: 'bar',
                                data:{ labels:['S1','S2','S3','S4','S5','S6'], datasets:[{ label:'Inscriptions', data:[12,18,9,24,20,16], backgroundColor:'rgba(75,0,130,0.85)'}] },
                                options:{ responsive:true, plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true } } }
                        });
                }
        })();
</script>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>