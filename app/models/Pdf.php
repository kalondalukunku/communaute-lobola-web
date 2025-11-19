<?php

    require LIBS_PATH .'tfpdf/tfpdf.php';

    class PDF extends tFPDF {
        
        function GenerateRapportDoc($courier, $allRedirect, $date_classement, $chemin)
        {
            $now = date('d/m/Y');
            $date_reception_pdf = Helper::formatDate($courier->date_reception);
            if($courier->date_limite !== null) $date_limite_pdf = Helper::formatDate($courier->date_limite); else $date_limite_pdf = "Non définie";
            $debut = new DateTime($date_classement);
            $fin = new DateTime($courier->date_reception);
            $date_traite = $debut->diff($fin)->format("%hh %imin %ss");

            $number = 1;
            
            $diffTime = strtotime($courier->date_classement) - strtotime($courier->date_limite);
            $textTempsTraitementEcoule = "";

            if($diffTime > 0) 
                $textTempsTraitementEcoule = " avec un retard de ". Helper::timeEgo($diffTime);
            
            $listeDirigeantArray = [];
            $tempsTravailDirigeants = 0;

            foreach($allRedirect as $redirect) 
            {
                $listeDirigeantArray[] = $redirect->nom_personne_redirigee;
                $tempsTravailDirigeants += $redirect->moratoire;
            }

            $tempsTravailDirigeants = Helper::timeEgo($tempsTravailDirigeants * 60 * 60);

            if(count($listeDirigeantArray) > 1) 
            {
                $textNombre = "plusieurs dirigeants qui sont";
                $last = array_pop($listeDirigeantArray);
                $listeDirigeant = implode(', ', $listeDirigeantArray) .' et '. $last;
            } 
            else {
                $textNombre = "un dirigeant qui est";
                $listeDirigeant = implode('', $listeDirigeantArray);
            }

            if($courier->category === 'sortant') 
            {
                $textType = "de type $courier->type";
            } 
            else {
                $textType = '';
            }
            
            $this->AddPage();
            $this->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);

            $this->SetFont('DejaVu','',16);
            $this->Ln(8);
            $this->Cell(0, 10, 'RAPPORT DE SUIVI DE COURIER', 1, 1, 'C');

            $this->Ln(8);

            $this->SetFont('DejaVu','',12);
            
            $texte1 = <<<EOT
            Le présent rapport concerne le document enregistré sous le numéro de référencement "$courier->ref_num", qui a pour objet "$courier->objet", réçu le $date_reception_pdf. Ce document a été traité dans le cadre du système interne de gestion des documents de l'entreprise.
            EOT;
            
            $this->MultiCell(0, 7, $texte1);
            $this->Ln(6);

            $this->SetFont('DejaVu','',14);
            $this->Cell(0, 10, 'INFOS GENERALES DU DOCUMENT');
            $this->Ln(9);
            
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, '- Provenance : '. $courier->provenance);
            $this->Ln(8);
            if($courier->destination !== null)
            {
                $this->Cell(0, 10, '- Destination : '. $courier->destination);
                $this->Ln(8);
            }           
            $this->Cell(0, 10, '- Référencement : '. $courier->ref_num);
            $this->Ln(8);
            
            $this->Cell(0, 10, '- Objet : '. $courier->objet);
            $this->Ln(8);
            
            $this->Cell(0, 10, '- Catégorie : '. $courier->category);
            $this->Ln(8);

            if($courier->category === 'sortant')
            {
                $this->Cell(0, 10, '- Type : '. $courier->type);
                $this->Ln(8);
            }
            if($courier->reception_num !== null)
            {
                $this->Cell(0, 10, '- Numéro de réception : '. $courier->reception_num);
                $this->Ln(8);
            }

            if($courier->moratoire !== 0)
            {
                $this->Cell(0, 10, '- Moratoire : '. $courier->moratoire .' heures');
                $this->Ln(8);
            }
            
            $this->Cell(0, 10, '- Etat : '. $courier->status);
            $this->Ln(8);

            $this->Cell(0, 10, '- Date de réception : Le '. Helper::formatDate($courier->date_reception));
            $this->Ln(8);

            $this->Cell(0, 10, '- Enregistré par : '. $courier->saved_by);
            $this->Ln(17);
            
            if(count($allRedirect) > 0)
            {
                $this->SetFont('DejaVu','',14);
                $this->Cell(0, 10, 'HISTORIQUE DES MOUVEMENTS DU DOCUMENT');
                $this->Ln(9);

                foreach($allRedirect as $redirect)
                {
                    $this->SetFont('DejaVu','',14);
                    $this->Cell(0, 10, $number.'. Rédirection vers cadre interne');
                    $this->Ln(9);
                    
                    $this->SetFont('DejaVu','',12);
                    $this->Cell(0, 10, '- Nom du cadre/service : '. $redirect->nom_personne_redirigee);
                    $this->Ln(8);
                    
                    $this->Cell(0, 10, '- Travail demandé : '. $redirect->travail_demande);
                    $this->Ln(8);
                    
                    $this->Cell(0, 10, '- Moratoire : '. $redirect->moratoire .' heures');
                    $this->Ln(8);
                    
                    $this->Cell(0, 10, '- Etat : '. $redirect->status);
                    $this->Ln(8);
                    
                    if($redirect->date_classement !== null)
                    {
                        $this->Cell(0, 10, '- Date de traitement : '. Helper::formatDate($redirect->date_classement));
                    }
                    $this->Ln(12);
                    $number++;
                }
            }

            $this->Ln(5);
            $texte2 = <<<EOT
            Le courier a été classé en tant que document $courier->category $textType, et il a été enregistré par $courier->saved_by. Conformément à la procédure en vigueur, la date limite de traitement a été fixée au $date_limite_pdf. A ce jour, le courier a été traité avec un délai effectif de $date_traite entre la récéption et la finalisation.

            Ce rapport constitue une trace administrative officielle du suivi de ce courier et pourra être utilisé comme justificatif dans les échanges interservices ou avec des partenaires externes.
            Ce rapport permet ainsi d'avoir une vue d'ensemble sur le parcours du courier et facilite l'évaluation de la réactivité et de l'efficacité du traitement administratif.
            EOT;

            $this->MultiCell(0, 7, $texte2);
            $this->Ln(10);
            $this->Cell(0, 10, "Fait à kinshasa, le $now", 0, 1, 'R');
            ob_end_clean();
            // var_dump($chemin);
            if($this->Output('F', $chemin)) return true;
        }
        function GenerateRapportDocGlobalSuivi($Couriers, $Redirections, $chemin, $year)
        {
            $nbrCouriersYear = count($Couriers->getCouriersByYear($year));

            $donnees1 = $Couriers->getDataWithPourcentage('status', $year);
            $donnees2 = $Couriers->getDataWithPourcentage('category', $year);
            $donnees2_1 = $Couriers->getDocTypeStatistique('category', $year);
            $donnees3 = $Couriers->getDataWithPourcentage('type', $year);
            $donnees3_1 = $Couriers->getDocTypeStatistique('type', $year);
            $donnees4 = $Couriers->getDataWithPourcentage('priority', $year);
            $donnees4_1 = $Couriers->getDocTypeStatistique('priority', $year);
            $donnees5 = $Redirections->getDataWithPourcentage2($year);
            $donnees6 = $Redirections->getDocRedirigerStatutFinal();
            $donnees7 = $Couriers->getDelaitraiteVSDelaiimparti();
            $donnees8 = $Couriers->getGlobalCourierMonth();

            $this->AddPage();
            $this->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);

            $pageWidth = $this->GetPageWidth();
            $pageHeight = $this->GetPageHeight();

            $imageWidth = 10;
            $imageHeight = 10;
            $x = $pageWidth - $imageWidth - 10;
            $y = $pageHeight - $imageHeight - 10;

            $this->SetFont('DejaVu','',16);
            $this->Ln(8);
            $this->Cell(0, 10, 'RAPPORT GLOBAL - SUIVI DES DOCUMENTS ADMINISTRATIFS', 1, 1, 'C');
            $this->Ln(8);
            
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, 'Période couverte : '. $year);
            $this->Ln(8);
            
            $this->Cell(0, 10, 'Total des documents enregistrés : '. $nbrCouriersYear);
            $this->Ln(14);

            
            // Tableau : Statut général des documents
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, 'Tableau : Statut général des documents');
            $this->Ln(10);
            $this->SetFont('DejaVu','',10);
            $this->SetFillColor(159,217,229);
            $this->Cell(64, 10, 'Statut',1,0,'C',true);
            $this->Cell(64, 10, 'Nombre des documents',1,0,'C',true);
            $this->Cell(64, 10, 'Pourcentage',1,0,'C',true);
            $this->Ln();

            $nbrDonnees1 = 0;
            foreach($donnees1 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    if($compteur == 2) $nbrDonnees1 += $cell;
                    ($compteur == 3) ? $pourcent = '%' : $pourcent = '';

                    $this->Cell(64,10,$cell . $pourcent,1);
                }
                $this->Ln();
            }
            $this->Cell(64,10,'Total',1);
            $this->Cell(64,10,$nbrDonnees1,1);
            $this->Cell(64,10,'100%',1);
            $this->Ln(20);
            

            // Tableau : Répartition des documents par catégorie
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, 'Tableau : Répartition des documents par "Catégorie"');
            $this->Ln(10);
            $this->SetFont('DejaVu','',10);
            $this->SetFillColor(159,217,229);
            $this->Cell(64, 10, 'Catégorie',1,0,'C',true);
            $this->Cell(64, 10, 'Nombre des documents',1,0,'C',true);
            $this->Cell(64, 10, 'Pourcentage',1,0,'C',true);
            $this->Ln();

            $nbrDonnees2 = 0;
            foreach($donnees2 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    if($compteur == 2) $nbrDonnees2 += $cell;
                    ($compteur == 3) ? $pourcent = '%' : $pourcent = '';

                    $this->Cell(64,10,$cell . $pourcent,1);
                }
                $this->Ln();
            }
            $this->Cell(64,10,'Total',1);
            $this->Cell(64,10,$nbrDonnees2,1);
            $this->Cell(64,10,'100%',1);
            $this->Ln(20);

            // Tableau : Traitemeent des documents selon Catégorie
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, 'Tableau : Traitement des documents selon "Catégorie"');
            $this->Ln(10);
            $this->SetFont('DejaVu','',10);
            $this->SetFillColor(159,217,229);
            $this->Cell(31, 10, 'Catégorie',1,0,'C',true);
            $this->Cell(31, 10, 'Total réçus',1,0,'C',true);
            $this->Cell(31, 10, 'En cours',1,0,'C',true);
            $this->Cell(31, 10, 'Traités',1,0,'C',true);
            $this->Cell(31, 10, 'Taux traitement',1,0,'C',true);
            $this->Cell(37, 10, 'Délai moyen',1,0,'C',true);
            $this->Ln();

            foreach($donnees2_1 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    ($compteur == 5) ? $pourcent = '%' : $pourcent = '';

                    if($compteur == 6)
                        $this->Cell(37,10,$cell ,1);
                    else 
                        $this->Cell(31,10,$cell . $pourcent,1);
                }
                $this->Ln();
            }


            // Tableau : Répartition des documents par type
            $this->AddPage();
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, 'Tableau : Répartition des documents par "Type"');
            $this->Ln(10);
            $this->SetFont('DejaVu','',10);
            $this->SetFillColor(159,217,229);
            $this->Cell(64, 10, 'Type',1,0,'C',true);
            $this->Cell(64, 10, 'Nombre des documents',1,0,'C',true);
            $this->Cell(64, 10, 'Pourcentage',1,0,'C',true);
            $this->Ln();

            $nbrDonnees3 = 0;
            foreach($donnees3 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    if($compteur == 2) $nbrDonnees3 += $cell;
                    ($compteur == 3) ? $pourcent = '%' : $pourcent = '';

                    $this->Cell(64,10,$cell . $pourcent,1);
                }
                $this->Ln();
            }
            $this->Cell(64,10,'Total',1);
            $this->Cell(64,10,$nbrDonnees3,1);
            $this->Cell(64,10,'100%',1);
            $this->Ln(20);

            // Tableau : Traitemeent des documents selon type
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, 'Tableau : Traitement des documents selon "Type"');
            $this->Ln(10);
            $this->SetFont('DejaVu','',10);
            $this->SetFillColor(159,217,229);
            $this->Cell(31, 10, 'Type',1,0,'C',true);
            $this->Cell(31, 10, 'Total réçus',1,0,'C',true);
            $this->Cell(31, 10, 'En cours',1,0,'C',true);
            $this->Cell(31, 10, 'Traités',1,0,'C',true);
            $this->Cell(31, 10, 'Taux traitement',1,0,'C',true);
            $this->Cell(37, 10, 'Délai moyen',1,0,'C',true);
            $this->Ln();

            foreach($donnees3_1 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    ($compteur == 5) ? $pourcent = '%' : $pourcent = '';

                    if($compteur == 6)
                        $this->Cell(37,10,$cell ,1);
                    else 
                        $this->Cell(31,10,$cell . $pourcent,1);
                }
                $this->Ln();
            }
            $this->Ln(11);


            // Tableau : Répartition des documents par priorité
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, 'Tableau : Répartition des documents par "Priorité"');
            $this->Ln(10);
            $this->SetFont('DejaVu','',10);
            $this->SetFillColor(159,217,229);
            $this->Cell(64, 10, 'Type',1,0,'C',true);
            $this->Cell(64, 10, 'Nombre des documents',1,0,'C',true);
            $this->Cell(64, 10, 'Pourcentage',1,0,'C',true);
            $this->Ln();

            $nbrDonnees4 = 0;
            foreach($donnees4 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    if($compteur == 2) $nbrDonnees3 += $cell;
                    ($compteur == 3) ? $pourcent = '%' : $pourcent = '';

                    $this->Cell(64,10,$cell . $pourcent,1);
                }
                $this->Ln();
            }
            $this->Cell(64,10,'Total',1);
            $this->Cell(64,10,$nbrDonnees4,1);
            $this->Cell(64,10,'100%',1);
            $this->Ln(20);

            // Tableau : Répartition des documents par priorité
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, 'Tableau : Traitemeent des documents selon "Priorité"');
            $this->Ln(10);
            $this->SetFont('DejaVu','',10);
            $this->SetFillColor(159,217,229);
            $this->Cell(31, 10, 'Priorité',1,0,'C',true);
            $this->Cell(31, 10, 'Total réçus',1,0,'C',true);
            $this->Cell(31, 10, 'En cours',1,0,'C',true);
            $this->Cell(31, 10, 'Traités',1,0,'C',true);
            $this->Cell(31, 10, 'Taux traitement',1,0,'C',true);
            $this->Cell(37, 10, 'Délai moyen',1,0,'C',true);
            $this->Ln();

            foreach($donnees4_1 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    ($compteur == 5) ? $pourcent = '%' : $pourcent = '';

                    if($compteur == 6)
                        $this->Cell(37,10,$cell ,1);
                    else 
                        $this->Cell(31,10,$cell . $pourcent,1);
                }
                $this->Ln();
            }
            $this->Ln(20);
            
            // Performance par cadre ou service interne
            $this->AddPage();
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, 'Tableau : Performance par cadre ou service interne');
            $this->Ln(10);
            $this->SetFont('DejaVu','',10);
            $this->SetFillColor(159,217,229);
            $this->Cell(38.4, 10, 'Cadre / Service',1,0,'C',true);
            $this->Cell(38.4, 10, 'Documents reçus',1,0,'C',true);
            $this->Cell(38.4, 10, 'Documents traités',1,0,'C',true);
            $this->Cell(38.4, 10, 'Délai moyen',1,0,'C',true);
            $this->Cell(38.4, 10, 'Taux de traitement',1,0,'C',true);
            $this->Ln();

            $nbrDonnees5_1 = 0;
            $nbrDonnees5_2 = 0;
            foreach($donnees5 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    if($compteur == 2) $nbrDonnees5_1 += $cell;
                    if($compteur == 3) $nbrDonnees5_2 += $cell;
                    ($compteur == 5) ? $pourcent = '%' : $pourcent = '';

                    $this->Cell(38.4,10,$cell . $pourcent,1);
                }
                $this->Ln();
            }
            $this->Cell(38.4,10,'Total',1);
            $this->Cell(38.4,10,$nbrDonnees5_1,1);
            $this->Cell(38.4,10,$nbrDonnees5_2,1);
            $this->Ln(20);

            
            // Documents redirigés avec statut final
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, 'Tableau : Documents redirigés avec statut final');
            $this->Ln(10);
            $this->SetFont('DejaVu','',10);
            $this->SetFillColor(159,217,229);
            $this->Cell(38.4, 10, 'Destinataire',1,0,'C',true);
            $this->Cell(38.4, 10, 'Documents reçus',1,0,'C',true);
            $this->Cell(38.4, 10, 'En cours',1,0,'C',true);
            $this->Cell(38.4, 10, 'Non traités',1,0,'C',true);
            $this->Cell(38.4, 10, 'Traités',1,0,'C',true);
            $this->Ln();

            foreach($donnees6 as $row)
            {
                foreach($row as $cell)
                {
                    $this->Cell(38.4,10,$cell,1);
                }
                $this->Ln();
            }
            $this->Ln(10);

            
            // Délai de traitement vs Délai imparti (Respect des délais)
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, 'Tableau : Délai de traitement vs Délai imparti (Respect des délais)');
            $this->Ln(10);
            $this->SetFont('DejaVu','',8);
            $this->SetFillColor(159,217,229);
            $this->Cell(58, 10, 'Référencement',1,0,'C',true);
            $this->Cell(20, 10, 'Date réçue',1,0,'C',true);
            $this->Cell(20, 10, 'Date limite',1,0,'C',true);
            $this->Cell(20, 10, 'Date classé',1,0,'C',true);
            $this->Cell(25, 10, 'Délai traitement',1,0,'C',true);
            $this->Cell(20, 10, 'Moratoire',1,0,'C',true);
            $this->Cell(29, 10, 'Respect des délais',1,0,'C',true);
            $this->Ln();

            foreach($donnees7 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;

                    if($compteur == 1)
                        $this->Cell(58,10,$cell ,1);
                    elseif($compteur == 2)
                        $this->Cell(20,10,$cell ,1);
                    elseif($compteur == 3)
                        $this->Cell(20,10,$cell ,1);
                    elseif($compteur == 4)
                        $this->Cell(20,10,$cell ,1);
                    elseif($compteur == 5)
                        $this->Cell(25,10,$cell ,1);
                    elseif($compteur == 6)
                        $this->Cell(20,10,$cell ,1);
                    elseif($compteur == 7)
                        $this->Cell(29,10,$cell ,1);
                    else
                        $this->Cell(24,10,$cell,1);
                }
                $this->Ln();
            }
            $this->Ln(20);

            
            // Etat général des courriers par mois
            $this->AddPage();
            $this->SetFont('DejaVu','',12);
            $this->Cell(0, 10, 'Tableau : Etat général des courriers par mois');
            $this->Ln(10);
            $this->SetFont('DejaVu','',9);
            $this->SetFillColor(159,217,229);
            $this->Cell(32, 10, 'Mois',1,0,'C',true);
            $this->Cell(32, 10, 'Total',1,0,'C',true);
            $this->Cell(32, 10, 'En attente',1,0,'C',true);
            $this->Cell(32, 10, 'Traités',1,0,'C',true);
            $this->Cell(32, 10, 'Classés sans suite',1,0,'C',true);
            $this->Cell(32, 10, 'Classés',1,0,'C',true);
            $this->Ln();

            foreach($donnees8 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;

                    $this->Cell(32,10,$cell,1);
                }
                $this->Ln();
            }
            $this->Ln(20);


            $this->Ln(12);

            // $this->Image(BASE_PATH_ICON, $x, $y,$imageWidth,$imageHeight);
            $this->Image(ASSETS .'images/logo.png', $x, $y,$imageWidth,$imageHeight);

            if($this->Output('F', $chemin)) return true;
        }

        function GenerateRapportDocGlobalAll($Couriers, $chemin)
        {
            $donnees1 = $Couriers->getCouriersAllSelect();
            $nbrDonnees1 = count($donnees1);

            $Pdf = new PDF('L',  'mm', 'A4');
            $Pdf->AddPage();
            $Pdf->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
            $Pdf->SetFont('DejaVu','',16);
            $Pdf->Ln(8);
            $Pdf->Cell(0, 10, 'RAPPORT GLOBAL DES COURRIERS', 1, 1, 'C');
            $Pdf->Ln(4);
            
            // Tableau : Statut général des documents
            $Pdf->SetFont('DejaVu','',14);
            $Pdf->Cell(0, 10, "Tableau : Statut général des documents ($nbrDonnees1 documents)");
            $Pdf->Ln(12);
            $Pdf->SetFont('DejaVu','',8);
            $Pdf->SetFillColor(159,217,229);
            $Pdf->Cell(30.8, 10, 'Provenance',1,0,'',true);
            $Pdf->Cell(35.8, 10, 'Objet',1,0,'',true);
            $Pdf->Cell(55.8, 10, 'Réferencement',1,0,'',true);
            $Pdf->Cell(20.8, 10, 'N. Réception',1,0,'',true);
            $Pdf->Cell(17.8, 10, 'Catégorie',1,0,'',true);
            $Pdf->Cell(14.8, 10, 'Type',1,0,'',true);
            $Pdf->Cell(13.8, 10, 'Priorité',1,0,'',true);
            $Pdf->Cell(22.8, 10, 'Date réçue',1,0,'',true);
            $Pdf->Cell(30.9, 10, 'Statut',1,0,'',true);
            $Pdf->Cell(33.8, 10, 'Ajouter par',1,0,'',true);
            $Pdf->Ln();

            foreach($donnees1 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    
                    if($compteur == 2)
                        $Pdf->Cell(35.8,10,$cell ,1);
                    elseif($compteur == 3)
                        $Pdf->Cell(55.8,10,$cell ,1);
                    elseif($compteur == 4)
                        $Pdf->Cell(20.8,10,$cell ,1);
                    elseif($compteur == 5)
                        $Pdf->Cell(17.8,10,$cell ,1);
                    elseif($compteur == 6)
                        $Pdf->Cell(14.8,10,$cell ,1);
                    elseif($compteur == 7)
                        $Pdf->Cell(13.8,10,$cell ,1);
                    elseif($compteur == 8)
                        $Pdf->Cell(22.8,10,date('d/m/Y',  strtotime($cell)) ,1);
                    elseif($compteur == 9)
                        $Pdf->Cell(30.9,10,$cell ,1);
                    elseif($compteur == 10)
                        $Pdf->Cell(33.8,10,$cell ,1);
                    else
                        $Pdf->Cell(30.8,10,$cell ,1);
                }
                $Pdf->Ln();
            }
            $Pdf->Cell(30.8,10,'Total général',1);
            $Pdf->Cell(246.3,10,$nbrDonnees1,1);
            $Pdf->Ln(16);


            if($Pdf->Output('F',$chemin)) return true;
        }

        function GenerateRapportDocEntrant($Couriers, $chemin)
        {
            $donnees1 = $Couriers->getAllDataSelectWhere('provenance, objet, ref_num, reception_num, date_reception, status, saved_by','category', ARRAY_CATEGORIES[0]);
            // var_dump($donnees1);die;
            $nbrDonnees1 = count($donnees1);

            $Pdf = new PDF('L',  'mm', 'A4');
            $Pdf->AddPage();
            $Pdf->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
            $Pdf->SetFont('DejaVu','',16);
            $Pdf->Ln(8);
            $Pdf->Cell(0, 10, 'RAPPORT GLOBAL DES COURRIERS '. strtoupper(ARRAY_CATEGORIES[0]).'S', 1, 1, 'C');
            $Pdf->Ln(4);
            
            // Tableau : Statut général des documents
            $Pdf->SetFont('DejaVu','',14);
            $Pdf->Cell(0, 10, "Tableau : Statut général des documents ".ARRAY_CATEGORIES[0]."s ($nbrDonnees1 documents)");
            $Pdf->Ln(12);
            $Pdf->SetFont('DejaVu','',8);
            $Pdf->SetFillColor(159,217,229);
            $Pdf->Cell(53.8, 10, 'Provenance',1,0,'',true);
            $Pdf->Cell(45.8, 10, 'Objet',1,0,'',true);
            $Pdf->Cell(60.8, 10, 'Réferencement',1,0,'',true);
            $Pdf->Cell(23.8, 10, 'N. réception',1,0,'',true);
            $Pdf->Cell(20.8, 10, 'Date réçue',1,0,'',true);
            $Pdf->Cell(31.3, 10, 'Statut',1,0,'',true);
            $Pdf->Cell(40.8, 10, 'Ajouter par',1,0,'',true);
            $Pdf->Ln();

            foreach($donnees1 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    
                    if($compteur == 1)
                        $Pdf->Cell(53.8,10,$cell ,1);
                    elseif($compteur == 2)
                        $Pdf->Cell(45.8,10,$cell ,1);
                    elseif($compteur == 3)
                        $Pdf->Cell(60.8,10,$cell ,1);
                    elseif($compteur == 4)
                        $Pdf->Cell(23.8,10,$cell ,1);
                    elseif($compteur == 5)
                        $Pdf->Cell(20.8,10,date('d/m/Y',  strtotime($cell)) ,1);
                    elseif($compteur == 6) 
                        $Pdf->Cell(31.3,10,$cell ,1);  
                    else
                        $Pdf->Cell(40.8,10,$cell ,1);
                }
                $Pdf->Ln();
            }
            $Pdf->Cell(53.8,10,'Total courrier '. ARRAY_CATEGORIES[0],1);
            $Pdf->Cell(223.3,10,$nbrDonnees1,1);
            $Pdf->Ln(16);


            if($Pdf->Output('F',$chemin)) return true;
            
        }

        function GenerateRapportDocSortant($Couriers, $chemin)
        {
            $donnees1 = $Couriers->getAllDataSelectWhere('provenance, objet, ref_num, reception_num, type, date_reception, status, saved_by','category', ARRAY_CATEGORIES[1]);
            // var_dump($donnees1);die;
            $nbrDonnees1 = count($donnees1);

            $Pdf = new PDF('L',  'mm', 'A4');
            $Pdf->AddPage();
            $Pdf->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
            $Pdf->SetFont('DejaVu','',16);
            $Pdf->Ln(8);
            $Pdf->Cell(0, 10, 'RAPPORT GLOBAL DES COURRIERS '. strtoupper(ARRAY_CATEGORIES[1]).'S', 1, 1, 'C');
            $Pdf->Ln(4);
            
            // Tableau : Statut général des documents
            $Pdf->SetFont('DejaVu','',14);
            $Pdf->Cell(0, 10, "Tableau : Statut général des documents ".ARRAY_CATEGORIES[1]."s ($nbrDonnees1 documents)");
            $Pdf->Ln(12);
            $Pdf->SetFont('DejaVu','',8);
            $Pdf->SetFillColor(159,217,229);
            $Pdf->Cell(30.8, 10, 'Provenance',1,0,'',true);
            $Pdf->Cell(35.8, 10, 'Objet',1,0,'',true);
            $Pdf->Cell(61.8, 10, 'Réferencement',1,0,'',true);
            $Pdf->Cell(30.8, 10, 'Num. réception',1,0,'',true);
            $Pdf->Cell(30.8, 10, 'Type',1,0,'',true);
            $Pdf->Cell(25.8, 10, 'Date réçue',1,0,'',true);
            $Pdf->Cell(30.5, 10, 'Statut',1,0,'',true);
            $Pdf->Cell(30.8, 10, 'Ajouter par',1,0,'',true);
            $Pdf->Ln();

            foreach($donnees1 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    
                    if($compteur == 1)
                        $Pdf->Cell(30.8,10,$cell ,1);
                    elseif($compteur == 2)
                        $Pdf->Cell(35.8,10,$cell ,1);
                    elseif($compteur == 3)
                        $Pdf->Cell(61.8,10,$cell ,1);
                    elseif($compteur == 4)
                        $Pdf->Cell(30.8,10,$cell ,1);
                    elseif($compteur == 5)
                        $Pdf->Cell(30.8,10,$cell ,1);
                    elseif($compteur == 6)
                        $Pdf->Cell(25.8,10,date('d/m/Y',  strtotime($cell)) ,1);
                    elseif($compteur == 7) 
                        $Pdf->Cell(30.5,10,$cell ,1);  
                    else
                        $Pdf->Cell(30.8,10,$cell ,1);
                }
                $Pdf->Ln();
            }
            $Pdf->Cell(66.6,10,'Total des courriers '. ARRAY_CATEGORIES[1] .'s',1);
            $Pdf->Cell(210.5,10,$nbrDonnees1,1);
            $Pdf->Ln(16);


            if($Pdf->Output('F',$chemin)) return true;
            
        }

        function GenerateRapportDocEnAttente($Rapports, $chemin)
        {
            $donnees1 = $Rapports->get_data_doc_en_attente(ARRAY_STATUS[0]);
            // var_dump($donnees1); die;
            $nbrDonnees1 = count($donnees1);

            $Pdf = new PDF('L',  'mm', 'A4');
            $Pdf->AddPage();
            $Pdf->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
            $Pdf->SetFont('DejaVu','',16);
            $Pdf->Ln(8);
            $Pdf->Cell(0, 10, 'RAPPORTS DES COURRIERS EN COURS DE TRAITEMENT', 1, 1, 'C');
            $Pdf->Ln(4);
            
            // Tableau : Statut général des documents
            $Pdf->SetFont('DejaVu','',14);
            $Pdf->Cell(0, 10, "Tableau : Statut général des documents en cours de traitement");
            $Pdf->Ln(12);
            $Pdf->SetFont('DejaVu','',8);
            $Pdf->SetFillColor(159,217,229);
            $Pdf->Cell(66.6, 10, 'Réferencement',1,0,'',true);
            $Pdf->Cell(47.8, 10, 'Objet',1,0,'',true);
            $Pdf->Cell(40.8, 10, 'Ajouter par',1,0,'',true);
            $Pdf->Cell(25.8, 10, 'Date réçue',1,0,'',true);
            $Pdf->Cell(25.8, 10, 'Date Limite',1,0,'',true);
            $Pdf->Cell(17.8, 10, 'Statut',1,0,'',true);
            $Pdf->Cell(23.5, 10, 'Redirigé vers',1,0,'',true);
            $Pdf->Cell(28.8, 10, 'Temps restant',1,0,'',true);
            $Pdf->Ln();

            foreach($donnees1 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    
                    if($compteur == 1)
                        $Pdf->Cell(66.6,10,$cell ,1);
                    elseif($compteur == 2)
                        $Pdf->Cell(47.8,10,$cell ,1);
                    elseif($compteur == 3)
                        $Pdf->Cell(40.8,10,$cell ,1);
                    elseif($compteur == 4)
                        $Pdf->Cell(25.8,10,$cell ,1);
                    elseif($compteur == 5)
                        $Pdf->Cell(25.8,10,$cell ,1); 
                    elseif($compteur == 6)
                        $Pdf->Cell(17.8,10,$cell ,1); 
                    elseif($compteur == 7)
                        $Pdf->Cell(23.5,10,$cell ,1); 
                    else
                        $Pdf->Cell(28.8,10,$cell ,1);
                }
                $Pdf->Ln();
            }
            $Pdf->Cell(66.6,10,'Total des courriers en cours',1);
            $Pdf->Cell(210.3,10,$nbrDonnees1,1);
            $Pdf->Ln(16);


            if($Pdf->Output('F',$chemin)) return true;
        }

        function GenerateRapportDocClasse($Rapports, $chemin)
        {
            $donnees1 = $Rapports->get_data_doc_classe();
            // var_dump($donnees1); die;
            $nbrDonnees1 = count($donnees1);

            $Pdf = new PDF('L',  'mm', 'A4');
            $Pdf->AddPage();
            $Pdf->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
            $Pdf->SetFont('DejaVu','',16);
            $Pdf->Ln(8);
            $Pdf->Cell(0, 10, 'RAPPORTS DES COURRIERS CLASSES', 1, 1, 'C');
            $Pdf->Ln(4);
            
            // Tableau : Statut général des documents
            $Pdf->SetFont('DejaVu','',14);
            $Pdf->Cell(0, 10, "Tableau : Statut général des documents classés");
            $Pdf->Ln(12);
            $Pdf->SetFont('DejaVu','',8);
            $Pdf->SetFillColor(159,217,229);
            $Pdf->Cell(66.6, 10, 'Réferencement',1,0,'',true);
            $Pdf->Cell(47.8, 10, 'Objet',1,0,'',true);
            $Pdf->Cell(35.8, 10, 'Ajouter par',1,0,'',true);
            $Pdf->Cell(48.1, 10, 'Dossier classé',1,0,'',true);
            $Pdf->Cell(23.8, 10, 'Date réçue',1,0,'',true);
            $Pdf->Cell(23.8, 10, 'Date classée',1,0,'',true);
            $Pdf->Cell(30.8, 10, 'Statut',1,0,'',true);
            $Pdf->Ln();

            foreach($donnees1 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    
                    if($compteur == 1)
                        $Pdf->Cell(66.6,10,$cell ,1);
                    elseif($compteur == 2)
                        $Pdf->Cell(47.8,10,$cell ,1);
                    elseif($compteur == 3)
                        $Pdf->Cell(35.8,10,$cell ,1);
                    elseif($compteur == 4)
                        $Pdf->Cell(48.1,10,$cell ,1);
                    elseif($compteur == 5)
                        $Pdf->Cell(23.8,10,$cell ,1);
                    elseif($compteur == 6)
                        $Pdf->Cell(23.8,10,$cell ,1); 
                    else
                        $Pdf->Cell(30.8,10,$cell ,1);
                }
                $Pdf->Ln();
            }
            $Pdf->Cell(66.6,10,'Total des courriers classés',1);
            $Pdf->Cell(210.3,10,$nbrDonnees1,1);
            $Pdf->Ln(16);


            if($Pdf->Output('F',$chemin)) return true;
        }

        function GenerateRapportDocRedirections($Rapports, $chemin)
        {
            $donnees1 = $Rapports->get_data_doc_redirections();
            // var_dump($donnees1); die;
            $nbrDonnees1 = count($donnees1);

            $Pdf = new PDF('L',  'mm', 'A4');
            $Pdf->AddPage();
            $Pdf->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
            $Pdf->SetFont('DejaVu','',16);
            $Pdf->Ln(8);
            $Pdf->Cell(0, 10, 'RAPPORTS DES COURRIERS REDIRIGES', 1, 1, 'C');
            $Pdf->Ln(4);
            
            // Tableau : Statut général des documents
            $Pdf->SetFont('DejaVu','',14);
            $Pdf->Cell(0, 10, "Tableau : Statut général des documents rédigirés");
            $Pdf->Ln(12);
            $Pdf->SetFont('DejaVu','',8);
            $Pdf->SetFillColor(159,217,229);
            $Pdf->Cell(60.1, 10, 'Réferencement',1,0,'',true);
            $Pdf->Cell(37.8, 10, 'Objet',1,0,'',true);
            $Pdf->Cell(23.8, 10, 'Date rédirigée',1,0,'',true);
            $Pdf->Cell(31.1, 10, 'Rédiriger vers',1,0,'',true);
            $Pdf->Cell(35.8, 10, 'Travail démandé',1,0,'',true);
            $Pdf->Cell(20.8, 10, 'Date limite',1,0,'',true);
            $Pdf->Cell(15.8, 10, 'Statut',1,0,'',true);
            $Pdf->Cell(25.8, 10, 'Date traitement',1,0,'',true);
            $Pdf->Cell(25.8, 10, 'Délai traitement',1,0,'',true);
            $Pdf->Ln();

            foreach($donnees1 as $row)
            {
                $compteur = 0;
                foreach($row as $cell)
                {
                    $compteur++;
                    
                    if($compteur == 1)
                        $Pdf->Cell(60.1,10,$cell ,1);
                    elseif($compteur == 2)
                        $Pdf->Cell(37.8,10,$cell ,1);
                    elseif($compteur == 3)
                        $Pdf->Cell(23.8,10,$cell ,1);
                    elseif($compteur == 4)
                        $Pdf->Cell(31.1,10,$cell ,1);
                    elseif($compteur == 5)
                        $Pdf->Cell(35.8,10,$cell ,1);
                    elseif($compteur == 6)
                        $Pdf->Cell(20.8,10,$cell ,1); 
                    elseif($compteur == 7)
                        $Pdf->Cell(15.8,10,$cell ,1);
                    elseif($compteur == 8)
                        $Pdf->Cell(25.8,10,$cell ,1);
                    else
                        $Pdf->Cell(25.8,10,$cell ,1);
                }
                $Pdf->Ln();
            }
            $Pdf->Cell(60.1,10,'Total des courriers rédirigés',1);
            $Pdf->Cell(216.7,10,$nbrDonnees1,1);
            $Pdf->Ln(16);


            if($Pdf->Output('F',$chemin)) return true;
        }
    }