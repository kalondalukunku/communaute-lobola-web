<?php 
    $title = "Evenements";
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php'; 
?>

    <div class="my-5">CSAK</div>

    <div class="mt-5">
        <div class="container">
            <div class="row">

                <?php include APP_PATH . 'templates/alertView.php'; ?>
                
                <a href="events/create" class="w-50 button-primary text-center mx-auto">Créer un événement</a>
            </div>
        </div>
    </div>

    
    <div class="section events" id="events">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-heading">
                        <h2>Evenements à venir</h2>
                    </div>
                </div>
                
                <?php foreach ($events as $event): ?>
                    <div class="col-lg-12 col-6">
                        <div class="item">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="image">
                                        <img src="<?= str_replace(BASE_PATH, '', $event->image) ?>" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <ul>
                                        <li>
                                            <span class="category mx-auto"><?= $event->objet ?></span>
                                            <h4><?= $event->title ?></h4>
                                        </li>
                                        <li>
                                            <span>Date:</span>
                                            <h6><?= Helper::formatDate($event->date) ?></h6>
                                        </li>
                                        <li>
                                            <span>Durée:</span>
                                            <h6><?= $event->duration ?>H</h6>
                                        </li>
                                        <li>
                                            <span>Price:</span>
                                            <h6>$120</h6>
                                        </li>
                                    </ul>
                                    <a href="#"><i class="fa fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- <div class="col-lg-12 col-6">
                    <div class="item">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="image">
                                <img src="assets/images/event-02.jpg" alt="">
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <ul>
                                    <li>
                                        <span class="category mx-auto">Front End</span>
                                        <h4>New Design Trend</h4>
                                    </li>
                                    <li>
                                        <span>Date:</span>
                                        <h6>24/02/2036</h6>
                                    </li>
                                    <li>
                                        <span>Durée:</span>
                                        <h6>30 H</h6>
                                    </li>
                                    <li>
                                        <span>Price:</span>
                                        <h6>$320</h6>
                                    </li>
                                </ul>
                                <a href="#"><i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-6">
                    <div class="item">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="image">
                                <img src="assets/images/event-03.jpg" alt="">
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <ul>
                                    <li>
                                        <span class="category mx-auto">Full Stack</span>
                                        <h4>Web Programming</h4>
                                    </li>
                                    <li>
                                        <span>Date:</span>
                                        <h6>12/03/2036</h6>
                                    </li>
                                    <li>
                                        <span>Durée:</span>
                                        <h6>48 H</h6>
                                    </li>
                                    <li>
                                        <span>Price:</span>
                                        <h6>$440</h6>
                                    </li>
                                </ul>
                                <a href="#"><i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>