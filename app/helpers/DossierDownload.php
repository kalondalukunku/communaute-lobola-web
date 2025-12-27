<?php

class DossierDownload
{

    public static function download_encrypted_folder_as_zip($source_folder, $classModel, $Personnel, $zipName = "archives.zip")
    {
        if (!is_dir($source_folder)) {
            return false;
        }

        // 1. Création d'un fichier ZIP temporaire
        $zipPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid() . '.zip';
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        // 2. Parcourir récursivement le dossier source
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source_folder, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $tempFilesToCleanup = [];

        foreach ($files as $fileinfo) {
            if (!$fileinfo->isDir()) {
                $filePathEnc = $fileinfo->getRealPath();
                
                // Définir un nom de fichier déchiffré temporaire
                $fileNameOnly = $fileinfo->getBasename();
                $tempDecryptedPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'dec_' . uniqid() . '_' . $fileNameOnly;
                $tempDecryptedPath = str_replace('.enc','',$tempDecryptedPath);

                // Appel de votre méthode de déchiffrement
                // Note : On suppose que dechiffreePdf prend (Sortie, Source, Clef)
                $res = $classModel->dechiffreePdf($tempDecryptedPath, $filePathEnc, CLEF_CHIFFRAGE_PDF);

                if ($res === true && file_exists($tempDecryptedPath)) {
                    // Calculer le chemin relatif pour garder la structure des dossiers dans le ZIP
                    $relativePath = substr($filePathEnc, strlen($source_folder) - 11);
                    $relativePath = str_replace('.enc','',$relativePath);
                    $relativePath = str_replace($Personnel->personnel_id, Utils::formatNamePsn($Personnel), $relativePath);
                    
                    // Ajouter le fichier déchiffré au ZIP
                    $zip->addFile($tempDecryptedPath, $relativePath);
                    
                    // Garder en mémoire pour suppression après fermeture du ZIP
                    $tempFilesToCleanup[] = $tempDecryptedPath;
                }
            }
        }

        $zip->close();

        // 3. Envoyer le fichier ZIP au navigateur
        if (file_exists($zipPath)) {
            self::serve_zip_and_cleanup($zipPath, $zipName, $tempFilesToCleanup);
        }
    }

    /**
     * Gère les headers de téléchargement et nettoie les fichiers temporaires
     */
    private static function serve_zip_and_cleanup($zipPath, $zipName, $tempFiles)
    {
        if (ob_get_level()) ob_end_clean();

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipName . '"');
        header('Content-Length: ' . filesize($zipPath));
        header('Pragma: no-cache');
        header('Expires: 0');

        readfile($zipPath);

        // Nettoyage du fichier ZIP temporaire
        if (file_exists($zipPath)) {
            unlink($zipPath);
        }

        // Nettoyage de tous les fichiers PDF déchiffrés temporaires
        foreach ($tempFiles as $tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
        exit;
    }
}