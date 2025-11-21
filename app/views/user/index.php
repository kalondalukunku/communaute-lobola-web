<?php 
  include APP_PATH . 'views/layouts/header.php'; 
  include APP_PATH . 'views/layouts/navbar.php'; 
?>

<section class="section-top">
    <div class="container">
        
        <?php include APP_PATH . 'templates/alertView.php'; ?>
        
        <div class="row my-5">
            <a href="/user/add" class="primary-btn w-75 text-center mx-auto mb-5">Ajouter un utilisateur</a>

            <div class="table-responsive">
                <table class="table table-striped" id="orderTable" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rôle</th>
                            <th scope="col">Date d'ajout</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($allUsers as $user): ?>
                            <tr>
                                <td><?= $numero ?></td>
                                <td><?= $user->nom ?></td>
                                <td><?= $user->email ?></td>
                                <td><?= $user->role ?></td>
                                <td><?= Helper::formatDate($user->created_at) ?></td>
                                <td>
                                    <a href="user/edit/<?= $user->user_id ?>" class="btn btn-sm btn-info rounded-3">Action</a>
                                </td>
                            </tr>
                            <?php $numero++ ?>
                        <?php endforeach; ?>
                    </tbody>
            </div>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>