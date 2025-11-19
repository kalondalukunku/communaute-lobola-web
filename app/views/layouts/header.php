<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="<?= ASSETS ?>css/components/bootstrap.min.css">
    <link rel="stylesheet" href="<?= ASSETS ?>css/components/datatables.min.css">
    <!-- <link rel="stylesheet" href="<?= ASSETS ?>css/components/ColumnControl.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css"><font></font> -->
    <link href="https://cdn.datatables.net/columncontrol/1.1.1/css/columnControl.dataTables.min.css" rel="stylesheet">

    <!-- Main css -->
    <link rel="stylesheet" href="<?= ASSETS ?>css/main.css">

		<!-- icon du domaine -->
		<link rel="icon" href="<?= ASSETS ?>images/logo.png" type="image/png">

    <!-- Title -->
    <title><?= $title ?? SITE_NAME ?></title>

</head>
<body>


  <!-- ***** Preloader Start ***** -->
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- ***** Preloader End ***** -->
