
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Visualisation du PDF</title>
    <link rel="stylesheet" href="<?= ASSETS ?>css/components/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        .toolbar {
            padding: 10px;
            text-align: left;
        }
        iframe {
            width: 100%;
            height: calc(100vh - 100px);
            border: none;
        }
    </style>
</head>
<body>
    <div class="text-center">
        <a href="/" class="col-4 mb-1 mb-lg-0 text-decoration-none"> 
    		<img src="<?= ASSETS ?>images/logo.png" width="35" alt="">
    	</a>
    </div>
    
						
    <div class="toolbar text-center">
        <button class="btn btn-info border-dark w-50 mx-auto rounded-pill my-3" onclick="window.history.back()">← Retour</button>
    </div>
    <!-- en ligne -->
    <!-- <iframe src="<?= $pathFilePdf; ?>"></iframe>  -->
    <!-- local -->
    <iframe src="<?= '/bukus/'. str_replace(BASE_PATH,'',$pathFilePdf); ?>"></iframe> 
    <!-- <iframe src="<?= '/bukus/assets/pdfJs/web/viewer.html?file=../'. str_replace(BASE_PATH,'',$pathFilePdf) ?>"></iframe>  -->
</body>
</html>