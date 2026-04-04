<?php
    require_once BASE_PATH . 'vendor/tfpdf/tfpdf.php';

class PDF extends tFPDF 
{
    private $couleurPrimaire = [207, 187, 48]; // Votre couleur dorée

    public function __construct()
    {
        parent::__construct('L', 'mm', 'A4'); // Paysage pour plus d'espace
        $this->AliasNbPages();
        $this->SetAutoPageBreak(true, 20);
        
        // Chargement des polices (assurez-vous que les fichiers .ttf sont présents dans le dossier font)
        $this->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
        $this->AddFont('DejaVu', 'B', 'DejaVuSans-Bold.ttf', true);
        $this->AddFont('DejaVu', 'I', 'DejaVuSans-Oblique.ttf', true);
        $this->AddFont('DejaVu', 'BI', 'DejaVuSans-BoldOblique.ttf', true);
    }

    /**
     * Header minimaliste et flottant
     */
    function Header()
    {
        
        $this->SetFillColor(232, 241, 241); // Couleur de fond très légère pour un look "papier"
        $this->Rect(0, 0, $this->GetPageWidth(), $this->GetPageHeight(), 'F');
        // 1. PETIT OVERLINE (Contexte haut de page)
        $this->SetY(12);
        $this->SetFont('DejaVu', 'B', 7);
        $this->SetTextColor(180, 180, 180);
        // Letter-spacing simulé (espaces entre lettres)
        $this->Cell(0, 5, "A R C H I V E S  S Y S T E M  V ". APP_VERSION, 0, 1, 'L');

        // 2. BLOC TITRE AVEC ACCENT VERTICAL
        $currentY = $this->GetY();
        
        // Petit rectangle d'accent (Couleur Primaire) - Très fin et élégant
        $this->SetFillColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->Rect(10, $currentY + 1, 1.5, 10, 'F'); 

        // Titre Principal
        $this->SetXY(15, $currentY);
        $this->SetFont('DejaVu', 'B', 18);
        $this->SetTextColor(0, 15, 14);
        $this->Cell(150, 12, 'COMMUNAUTÉ LOBOLA', 0, 0, 'L');

        // 3. BLOC MÉTADONNÉES (Droite - Look "Swiss Design")
        $this->SetXY(200, $currentY);
        
        // Date avec label discret
        $this->SetFont('DejaVu', 'B', 7);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(40, 4, 'DATE D\'EXPORT', 0, 0, 'L');
        $this->Cell(0, 4, 'RÉFÉRENCE', 0, 1, 'L');

        $this->SetX(200);
        $this->SetFont('Courier', 'B', 10); // Look technique/authentifié
        $this->SetTextColor(0, 15, 14);
        $this->Cell(40, 6, date('d / m / Y'), 0, 0, 'L');
        $this->Cell(0, 6, '#' . date('Y-') . str_pad($this->PageNo(), 3, '0', STR_PAD_LEFT), 0, 1, 'L');

        // 4. LIGNE DE SÉPARATION DOUBLE (Esthétique minimaliste)
        $this->Ln(4);
        // Ligne "Shadow" presque invisible
        $this->SetDrawColor(245, 245, 245);
        $this->SetLineWidth(0.6);
        $this->Line(10, $this->GetY(), 287, $this->GetY());
        
        // Ligne d'accent très courte (souligne le début du contenu)
        $this->SetDrawColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->SetLineWidth(0.8);
        $this->Line(10, $this->GetY(), 30, $this->GetY());

        $this->Ln(10); // Espace de transition vers le corps
    }

    function Footer()
    {
        // Barre décorative bleue
        $this->SetFillColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]); 
        $this->RoundedRect(11, $this->GetPageHeight() - 17, 12, 12, 0, 'F', '14'); 
        $this->RoundedRect($this->GetPageWidth() - 21.2, $this->GetPageHeight() - 17, 12, 12, 0, 'F', '14'); 

        $imagePath = 'assets/images/logo.jpg';
        if (file_exists($imagePath)) {
            $this->Image($imagePath, 12, $this->GetPageHeight() - 16, 10, 10);
            $this->Image($imagePath, $this->GetPageWidth() - 20, $this->GetPageHeight() - 16, 10, 10);
        }

        $this->SetY(-15);
        $this->SetFont('DejaVu', '', 8);
        $this->SetTextColor(0, 15, 14);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
    /**
     * Design UX Avancé : Liste de membres style "Application Dashboard"
     */
    public function generateMembersActivityReport($membres) {
        $this->AddPage('L');
        $this->AliasNbPages();

        // --- TITRE DE SECTION ---
        $this->SetFont('DejaVu', 'B', 24);
        $this->SetTextColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->Cell(0, 15, "Suivi des membres - 23e Session", 0, 1, 'C');
        
        $this->SetFont('DejaVu', '', 10);
        $this->SetTextColor(80, 80, 80);
        $this->Cell(0, 5, count($membres) . " membres répertoriés dans le cheminement des enseignements de cette session", 0, 1, 'C');
        $this->Ln(10);

        // --- EN-TÊTE DE LISTE (FLOTTANT) ---
        $w = [105, 70, 35, 40, 40];
        $this->SetFont('DejaVu', 'B', 8);
        $this->SetTextColor(0, 15, 14);
        
        $this->Cell($w[0], 10, 'MEMBRE', 0, 0, 'L');
        $this->Cell($w[1], 10, 'COORDONNÉES', 0, 0, 'L');
        $this->Cell($w[2], 10, "TAUX D'ÉCOUTE", 0, 0, 'L');
        $this->Cell($w[3], 10, "ENSEIGNEMENT LU", 0, 0, 'L');
        $this->Cell($w[4], 10, 'ASSIDUITÉ', 0, 1, 'L');

        // Ligne de séparation d'en-tête
        $this->SetDrawColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->SetLineWidth(0.4);
        $this->Line(10, $this->GetY(), 287, $this->GetY());
        $this->Ln(4);

        // --- CORPS (DESIGN PAR LIGNES ÉPURÉES) ---
        foreach($membres as $m) {
            // Vérification saut de page
            if($this->GetY() > 185) $this->AddPage('L');

            $currentY = $this->GetY();
            
            // Fond de ligne alterné très subtil
            $this->SetFillColor(230, 235, 235);
            $this->Rect(10, $currentY, 277, 18, 'F');

            // 1. Colonne Identité (Gras et Large)
            $this->SetXY(15, $currentY + 5);
            $this->SetFont('DejaVu', 'B', 11);
            $this->SetTextColor(50, 50, 50);
            $this->Cell($w[0], 8, mb_convert_encoding($m['nom_postnom'], 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');

            // 2. Colonne Email (Italique gris)
            $this->SetFont('DejaVu', '', 10);
            $this->SetTextColor(50, 50, 50);
            $this->Cell($w[1], 8, $m['email'], 0, 0, 'L');

            // 3. Colonne Téléphone (Style Mono)
            $this->SetFont('Courier', 'B', 11);
            $this->SetTextColor(50, 50, 50);
            $this->Cell($w[2], 8, $m['stats']['progress_bar'] . '%', 0, 0, 'L');
            
            // 4. Colonne Enseignement (Style Mono)
            $this->SetFont('Courier', 'B', 11);
            $this->SetTextColor(50, 50, 50);
            $this->Cell($w[3], 8, $m['stats']['read_count'] .' Lu / '. $m['stats']['total_to_read'], 0, 0, 'L');

            // 5. Colonne Assiduité (Badge arrondi avec couleur selon le statut)
            $this->SetFont('DejaVu', 'B', 9);
            $this->SetTextColor(50, 50, 50);
            $this->Cell($w[4], 8, Helper::getAssiduityGrade($m['stats']['progress_bar']), 0, 0, 'L'); // Cellule vide pour alignement à droite

            $this->Ln(14); // Espacement entre les lignes
        }

        $this->Output('D', 'Suivi_des_membres_-_23e_Session.pdf');
    }

    public function generateMembersActifsReport($membres)
    {
        $this->AddPage();
        $this->AliasNbPages();

        // --- TITRE DE SECTION ---
        $this->SetFont('DejaVu', 'B', 24);
        $this->SetTextColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->Cell(0, 15, "liste des membres actifs", 0, 1, 'C');
        
        $this->SetFont('DejaVu', '', 10);
        $this->SetTextColor(80, 80, 80);
        $this->Cell(0, 5, count($membres) . " membres répertoriés", 0, 1, 'C');
        $this->Ln(10);

        // En-têtes du tableau
        $this->SetFillColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->SetFont('DejaVu', 'B', 10);

        // Définition des largeurs de colonnes (Total 277mm pour A4 Paysage avec marges de 10mm)
        $w = [85, 50, 73, 45, 25];
        $header = ['Nom Complet', 'Initiation', 'Email', 'Téléphone', 'Statut'];

        for($i=0; $i<count($header); $i++) {
            if($i == 4) {
                $this->Cell($w[$i], 12, $header[$i], 0, 0, 'C', true);
            } else {
                $this->Cell($w[$i], 12, $header[$i], 0, 0, 'L', true);
            }
        }
        $this->Ln();

        // Données des membres
        $this->SetTextColor(50, 50, 50);
        $this->SetFont('DejaVu', '', 9);
        
        // Couleurs pour les lignes alternées
        // $this->SetFillColor(250, 249, 240); // Très léger doré/beige pour l'alternance

        foreach($membres as $m) {
            // Extraction des données (s'adapte objet ou tableau)
            $id = $m->member_id;
            $nom = $m->nom_postnom;
            $niveau = $m->niveau_initiation === '' ? 'Pas encore initié' : $m->niveau_initiation;
            $email = $m->email;
            $tel = $m->phone_number;
            // $mod = $m->modalite_engagement;
            $statut = $m->status;

            $this->Cell($w[0], 10, $nom, 0, 0, 'L');
            $this->Cell($w[1], 10, $niveau, 0, 0, 'L');
            $this->Cell($w[2], 10, $email, 0, 0, 'L');
            $this->Cell($w[3], 10, $tel, 0, 0, 'L');
            // $this->Cell($w[4], 10, $mod, 0, 0, 'L');
            $this->Cell($w[4], 10, $statut, 0, 0, 'C');
            $this->Ln();
            // $fill = !$fill;
        }
        // Ligne de fermeture du tableau
        // $this->Cell(array_sum($w), 0, '', 'T');
        $this->Output('D', 'communaute_lobola_-_Liste_des_membres_actifs.pdf');
    }

    public function generateMembersAttenteIntegrationReport($membres)
    {
        $this->AddPage();
        $this->AliasNbPages();

        // --- TITRE DE SECTION ---
        $this->SetFont('DejaVu', 'B', 24);
        $this->SetTextColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->Cell(0, 15, "liste des membres en attente d'intégration", 0, 1, 'C');
        
        $this->SetFont('DejaVu', '', 10);
        $this->SetTextColor(80, 80, 80);
        $this->Cell(0, 5, count($membres) . " membres répertoriés", 0, 1, 'C');
        $this->Ln(10);

        // En-têtes du tableau
        $this->SetFillColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->SetFont('DejaVu', 'B', 10);

        // Définition des largeurs de colonnes (Total 277mm pour A4 Paysage avec marges de 10mm)
        $w = [83, 40, 70, 40, 45, 25];
        $header = ['Nom Complet', 'Initiation', 'Email', 'Téléphone', 'Date d\'intégration'];

        for($i=0; $i<count($header); $i++) {
            if($i == 5) {
                $this->Cell($w[$i], 12, $header[$i], 0, 0, 'C', true);
            } else {
                $this->Cell($w[$i], 12, $header[$i], 0, 0, 'L', true);
            }
        }
        $this->Ln();

        // Données des membres
        $this->SetTextColor(50, 50, 50);
        $this->SetFont('DejaVu', '', 9);
        
        // Couleurs pour les lignes alternées
        // $this->SetFillColor(250, 249, 240); // Très léger doré/beige pour l'alternance

        foreach($membres as $m) {
            // Extraction des données (s'adapte objet ou tableau)
            $id = $m->member_id;
            $nom = $m->nom_postnom;
            $niveau = $m->niveau_initiation === '' ? 'Pas encore initié' : $m->niveau_initiation;
            $email = $m->email;
            $tel = $m->phone_number;
            // $mod = $m->modalite_engagement;
            // $statut = "Attente d'intégration";
            $dateIntegration = date('d/m/Y', strtotime($m->created_at));

            $this->Cell($w[0], 10, $nom, 0, 0, 'L');
            $this->Cell($w[1], 9, $niveau, 0, 0, 'L');
            $this->Cell($w[2], 10, $email, 0, 0, 'L');
            $this->Cell($w[3], 10, $tel, 0, 0, 'L');
            // $this->Cell($w[4], 10, $mod, 0, 0, 'L');
            // $this->Cell($w[4], 9, $statut, 0, 0, 'C');
            $this->Cell($w[5], 10, $dateIntegration, 0, 0, 'C');
            $this->Ln();
            // $fill = !$fill;
        }
        // Ligne de fermeture du tableau
        // $this->Cell(array_sum($w), 0, '', 'T');
        $this->Output('D', 'communaute_lobola_-_Liste_des_membres_en_attente_d_integration.pdf');
    }

    public function generateMembersIntegrationValideReport($membres)
    {
        $this->AddPage();
        $this->AliasNbPages();

        // --- TITRE DE SECTION ---
        $this->SetFont('DejaVu', 'B', 24);
        $this->SetTextColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->Cell(0, 15, "liste des membres intégration validée", 0, 1, 'C');
        
        $this->SetFont('DejaVu', '', 10);
        $this->SetTextColor(80, 80, 80);
        $this->Cell(0, 5, count($membres) . " membres répertoriés", 0, 1, 'C');
        $this->Ln(10);

        // En-têtes du tableau
        $this->SetFillColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->SetFont('DejaVu', 'B', 10);

        // Définition des largeurs de colonnes (Total 277mm pour A4 Paysage avec marges de 10mm)
        $w = [85, 50, 73, 40, 30];
        $header = ['Nom Complet', 'Initiation', 'Email', 'Téléphone', 'Statut'];

        for($i=0; $i<count($header); $i++) {
            if($i == 4) {
                $this->Cell($w[$i], 12, $header[$i], 0, 0, 'C', true);
            } else {
                $this->Cell($w[$i], 12, $header[$i], 0, 0, 'L', true);
            }
        }
        $this->Ln();

        // Données des membres
        $this->SetTextColor(50, 50, 50);
        $this->SetFont('DejaVu', '', 9);
        
        // Couleurs pour les lignes alternées
        // $this->SetFillColor(250, 249, 240); // Très léger doré/beige pour l'alternance

        foreach($membres as $m) {
            // Extraction des données (s'adapte objet ou tableau)
            $id = $m->member_id;
            $nom = $m->nom_postnom;
            $niveau = $m->niveau_initiation === '' ? 'Pas encore initié' : $m->niveau_initiation;
            $email = $m->email;
            $tel = $m->phone_number;
            // $mod = $m->modalite_engagement;
            $statut = $m->status;

            $this->Cell($w[0], 10, $nom, 0, 0, 'L');
            $this->Cell($w[1], 10, $niveau, 0, 0, 'L');
            $this->Cell($w[2], 10, $email, 0, 0, 'L');
            $this->Cell($w[3], 10, $tel, 0, 0, 'L');
            // $this->Cell($w[4], 10, $mod, 0, 0, 'L');
            $this->Cell($w[4], 10, $statut, 0, 0, 'C');
            $this->Ln();
            // $fill = !$fill;
        }
        // Ligne de fermeture du tableau
        // $this->Cell(array_sum($w), 0, '', 'T');
        $this->Output('D', 'communaute_lobola_-_Liste_des_membres_integration_valide.pdf');
    }

    public function generateMembersIntegrationRejeteReport($membres)
    {
        $this->AddPage();
        $this->AliasNbPages();

        // --- TITRE DE SECTION ---
        $this->SetFont('DejaVu', 'B', 24);
        $this->SetTextColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->Cell(0, 15, "liste des membres intégration rejetée", 0, 1, 'C');
        
        $this->SetFont('DejaVu', '', 10);
        $this->SetTextColor(80, 80, 80);
        $this->Cell(0, 5, count($membres) . " membres répertoriés", 0, 1, 'C');
        $this->Ln(10);

        // En-têtes du tableau
        $this->SetFillColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->SetFont('DejaVu', 'B', 10);

        // Définition des largeurs de colonnes (Total 277mm pour A4 Paysage avec marges de 10mm)
        $w = [85, 50, 73, 40, 30];
        $header = ['Nom Complet', 'Initiation', 'Email', 'Téléphone', 'Statut'];

        for($i=0; $i<count($header); $i++) {
            if($i == 4) {
                $this->Cell($w[$i], 12, $header[$i], 0, 0, 'C', true);
            } else {
                $this->Cell($w[$i], 12, $header[$i], 0, 0, 'L', true);
            }
        }
        $this->Ln();

        // Données des membres
        $this->SetTextColor(50, 50, 50);
        $this->SetFont('DejaVu', '', 9);
        
        // Couleurs pour les lignes alternées
        // $this->SetFillColor(250, 249, 240); // Très léger doré/beige pour l'alternance

        foreach($membres as $m) {
            // Extraction des données (s'adapte objet ou tableau)
            $id = $m->member_id;
            $nom = $m->nom_postnom;
            $niveau = $m->niveau_initiation === '' ? 'Pas encore initié' : $m->niveau_initiation;
            $email = $m->email;
            $tel = $m->phone_number;
            // $mod = $m->modalite_engagement;
            $statut = "Réjeté";

            $this->Cell($w[0], 10, $nom, 0, 0, 'L');
            $this->Cell($w[1], 10, $niveau, 0, 0, 'L');
            $this->Cell($w[2], 10, $email, 0, 0, 'L');
            $this->Cell($w[3], 10, $tel, 0, 0, 'L');
            // $this->Cell($w[4], 10, $mod, 0, 0, 'L');
            $this->Cell($w[4], 10, $statut, 0, 0, 'C');
            $this->Ln();
            // $fill = !$fill;
        }
        // Ligne de fermeture du tableau
        // $this->Cell(array_sum($w), 0, '', 'T');
        $this->Output('D', 'communaute_lobola_-_Liste_des_membres_integration_rejete.pdf');
    }

    public function generateMembersSuspendusReport($membres)
    {
        $this->AddPage();
        $this->AliasNbPages();

        // --- TITRE DE SECTION ---
        $this->SetFont('DejaVu', 'B', 24);
        $this->SetTextColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->Cell(0, 15, "liste des membres suspendus", 0, 1, 'C');
        
        $this->SetFont('DejaVu', '', 10);
        $this->SetTextColor(80, 80, 80);
        $this->Cell(0, 5, count($membres) . " membres répertoriés", 0, 1, 'C');
        $this->Ln(10);

        // En-têtes du tableau
        $this->SetFillColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
        $this->SetFont('DejaVu', 'B', 10);

        // Définition des largeurs de colonnes (Total 277mm pour A4 Paysage avec marges de 10mm)
        $w = [85, 50, 73, 40, 30];
        $header = ['Nom Complet', 'Initiation', 'Email', 'Téléphone', 'Statut'];

        for($i=0; $i<count($header); $i++) {
            if($i == 4) {
                $this->Cell($w[$i], 12, $header[$i], 0, 0, 'C', true);
            } else {
                $this->Cell($w[$i], 12, $header[$i], 0, 0, 'L', true);
            }
        }
        $this->Ln();

        // Données des membres
        $this->SetTextColor(50, 50, 50);
        $this->SetFont('DejaVu', '', 9);
        
        // Couleurs pour les lignes alternées
        // $this->SetFillColor(250, 249, 240); // Très léger doré/beige pour l'alternance

        foreach($membres as $m) {
            // Extraction des données (s'adapte objet ou tableau)
            $id = $m->member_id;
            $nom = $m->nom_postnom;
            $niveau = $m->niveau_initiation === '' ? 'Pas encore initié' : $m->niveau_initiation;
            $email = $m->email;
            $tel = $m->phone_number;
            // $mod = $m->modalite_engagement;
            $statut = "Suspendu";

            $this->Cell($w[0], 10, $nom, 0, 0, 'L');
            $this->Cell($w[1], 10, $niveau, 0, 0, 'L');
            $this->Cell($w[2], 10, $email, 0, 0, 'L');
            $this->Cell($w[3], 10, $tel, 0, 0, 'L');
            // $this->Cell($w[4], 10, $mod, 0, 0, 'L');
            $this->Cell($w[4], 10, $statut, 0, 0, 'C');
            $this->Ln();
            // $fill = !$fill;
        }
        // Ligne de fermeture du tableau
        // $this->Cell(array_sum($w), 0, '', 'T');
        $this->Output('D', 'communaute_lobola_-_Liste_des_membres_integration_suspendus.pdf');
    }

    /**
     * Fonction utilitaire pour des rectangles arrondis (UI Look)
     */
    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F') $op='f';
        elseif($style=='FD' || $style=='DF') $op='B';
        else $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }








    // function Header()
    // {
    //     // 1. Titre Principal (Gauche)
    //     $this->SetFont('DejaVu', 'B', 18);
    //     $this->SetTextColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
    //     $this->Cell(150, 10, 'ANNUAIRE OFFICIEL DES MEMBRES', 0, 0, 'L');

    //     // 2. Bloc d'informations (Droite) - Aligné sur le haut
    //     $this->SetFont('DejaVu', '', 8);
    //     $this->SetTextColor(150, 150, 150);
    //     $this->Cell(0, 5, mb_strtoupper('Document Authentifié par le Registre'), 0, 1, 'R');
        
    //     $this->SetX(160); // Repositionnement pour la deuxième ligne de droite
    //     $this->SetFont('DejaVu', 'B', 9);
    //     $this->SetTextColor(100, 100, 100);
    //     $this->Cell(0, 5, 'GENERE LE : ' . date('d/m/Y | H:i'), 0, 1, 'R');

    //     // 3. Double Ligne Décorative (Architecture moderne)
    //     $this->Ln(3);
        
    //     // Ligne principale épaisse
    //     $this->SetDrawColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
    //     $this->SetLineWidth(0.8);
    //     $this->Line(10, 26, 287, 26);
        
    //     // Ligne fine de rappel (1mm en dessous)
    //     $this->SetDrawColor(210, 180, 140); // Couleur dorée/accent
    //     $this->SetLineWidth(0.2);
    //     $this->Line(10, 27.2, 287, 27.2);

    //     $this->Ln(12); // Espace généreux avant le contenu
    // }

    // /**
    //  * Pied de page avec numérotation élégante
    //  */
    // function Footer()
    // {
    //     $this->SetY(-15);
    //     $this->SetFont('DejaVu', 'I', 8);
    //     $this->SetTextColor(150, 150, 150);
        
    //     // Ligne de séparation discrète
    //     $this->SetDrawColor(230, 230, 230);
    //     $this->Line(10, $this->GetY(), 287, $this->GetY());
        
    //     $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' / {nb}', 0, 0, 'C');
    //     $this->Cell(0, 10, 'Confidentiel - Usage Interne', 0, 0, 'R');
    // }
    // /**
    //  * Génère le tableau des membres
    //  * @param array $membres Liste d'objets ou tableaux de membres
    //  */
    // public function generateMembersActivityReport($membres) {
    //     $this->AddPage('L'); // Mode Paysage pour plus de fluidité
        
    //     // --- DESIGN DE L'EN-TÊTE ---
    //     $this->SetFont('DejaVu', 'B', 22);
    //     $this->SetTextColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
    //     $this->Cell(0, 20, "SESSION D'ÉVALUATION SPIRITUELLE", 0, 1, 'L');
        
    //     // Sous-titre
    //     $this->SetFont('DejaVu', '', 10);
    //     $this->SetTextColor(120, 120, 120);
    //     $this->Cell(0, -10, mb_strtoupper("Registre des âmes en cheminement - " . date('d/m/Y')), 0, 1, 'L');
    //     $this->Ln(15);

    //     // --- TABLEAU ---
    //     $w = [85, 75, 60, 50]; 
    //     $header = ['Identité du Disciple', 'Courriel', 'Contact', 'État de Présence'];

    //     // Style En-tête Minimaliste Noir Profond
    //     $this->SetFillColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
    //     $this->SetTextColor(255, 255, 255);
    //     $this->SetFont('DejaVu', 'B', 8);

    //     foreach($header as $i => $title) {
    //         // Letter-spacing simulé pour le look premium
    //         $spacedTitle = implode(' ', str_split(mb_strtoupper($title, 'UTF-8')));
    //         $this->Cell($w[$i], 12, '  ' . $spacedTitle, 0, 0, 'L', true);
    //     }
    //     $this->Ln();

    //     // --- CORPS ---
    //     $this->SetFont('DejaVu', '', 10);
    //     $h_row = 15;
    //     $fill = false;

    //     foreach($membres as $m) {
    //         if($this->GetY() + $h_row > 180) $this->AddPage('L');

    //         $nom = $m['nom_postnom'] ?? 'N/A';
    //         $email = $m['email'] ?? '-';
    //         $tel = $m['phone_number'] ?? '-';
    //         $is_active = (isset($m['status']) && $m['status']);

    //         // Style de ligne
    //         $this->SetFillColor(255, 255, 255);
    //         if ($fill) $this->SetFillColor(252, 253, 253);
            
    //         $this->SetDrawColor(245, 245, 245);
    //         $currentY = $this->GetY();

    //         // Nom avec accent vertical (votre couleur secondaire)
    //         $this->SetTextColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
    //         $this->SetFont('DejaVu', 'B', 10);
    //         $this->Cell($w[0], $h_row, '      ' . $nom, 'B', 0, 'L', true);
            
    //         // Ligne d'accent
    //         $this->SetDrawColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
    //         $this->SetLineWidth(0.8);
    //         $this->Line($this->GetX() - $w[0], $currentY + 4, $this->GetX() - $w[0], $currentY + $h_row - 4);
            
    //         // Autres données
    //         $this->SetLineWidth(0.1);
    //         $this->SetDrawColor(240, 240, 240);
    //         $this->SetTextColor(100, 100, 100);
    //         $this->SetFont('DejaVu', '', 9);
    //         $this->Cell($w[1], $h_row, $email, 'B', 0, 'L', true);
    //         $this->Cell($w[2], $h_row, $tel, 'B', 0, 'L', true);

    //         // Statut avec pastille ronde (UI/UX)
    //         $this->Cell($w[3], $h_row, '', 'B', 0, 'L', true);
            
    //         $bulletX = $this->GetX() - $w[3] + 10;
    //         $bulletY = $currentY + ($h_row / 2);
            
    //         if($is_active) {
    //             $this->SetFillColor(0, 150, 136); // Teal spirituel
    //             $status_txt = "ACTIF";
    //         } else {
    //             $this->SetFillColor(200, 200, 200); // Gris retrait
    //             $status_txt = "RETRAIT";
    //         }

    //         $this->Circle($bulletX, $bulletY, 1.5, 'F');
            
    //         $this->SetXY($bulletX + 4, $currentY);
    //         $this->SetFont('DejaVu', 'B', 7);
    //         $this->Cell(30, $h_row, $status_txt, 0, 0, 'L');

    //         $this->Ln();
    //         $fill = !$fill;
    //     }

    //     $this->Output('I', 'Rapport_Spirituel.pdf');
    // }

    /**
     * Helper function pour dessiner un cercle (à ajouter dans votre classe PDF si non présente)
     */
    /**
     * Méthode pour dessiner une ellipse (indispensable pour les pastilles de statut)
     */
    // function Ellipse($x, $y, $rx, $ry, $style='D') {
    //     if($style=='F') $op='f';
    //     elseif($style=='FD' || $style=='DF') $op='B';
    //     else $op='S';
        
    //     $lx=4/3*(M_SQRT2-1)*$rx;
    //     $ly=4/3*(M_SQRT2-1)*$ry;
        
    //     $this->_out(sprintf('%.2F %.2F m',($x+$rx)*$this->k,($this->h-$y)*$this->k));
    //     $this->_Arc($x+$rx, $y-$ly, $x+$lx, $y-$ry, $x, $y-$ry);
    //     $this->_Arc($x-$lx, $y-$ry, $x-$rx, $y-$ly, $x-$rx, $y);
    //     $this->_Arc($x-$rx, $y+$ly, $x-$lx, $y+$ry, $x, $y+$ry);
    //     $this->_Arc($x+$lx, $y+$ry, $x+$rx, $y+$ly, $x+$rx, $y);
    //     $this->_out($op);
    // }

    // function Circle($x, $y, $r, $style='D') {
    //     $this->Ellipse($x, $y, $r, $r, $style);
    // }
    // public function generateMembersActivityReport($membres)
    // {
    //     $this->AddPage();
        
    //     // En-têtes du tableau
    //     $this->SetFillColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
    //     $this->SetTextColor(255, 255, 255);
    //     $this->SetDrawColor($this->couleurPrimaire[0], $this->couleurPrimaire[1], $this->couleurPrimaire[2]);
    //     $this->SetFont('DejaVu', 'B', 10);

    //     // Définition des largeurs de colonnes (Total 277mm pour A4 Paysage avec marges de 10mm)
    //     $w = [60, 60, 50, 45, 47];
    //     $header = ['Nom Complet', 'Email', 'Téléphone', 'Statut'];

    //     for($i=0; $i<count($header); $i++) {
    //         $this->Cell($w[$i], 12, $header[$i], 1, 0, 'C', true);
    //     }
    //     $this->Ln();

    //     // Données des membres
    //     $this->SetTextColor(50, 50, 50);
    //     $this->SetFont('DejaVu', '', 9);
    //     $fill = false;
        
    //     // Couleurs pour les lignes alternées
    //     $this->SetFillColor(250, 249, 240); // Très léger doré/beige pour l'alternance

    //     foreach($membres as $m) {
    //         // Extraction des données (s'adapte objet ou tableau)
    //         $id = $m['member_id'];
    //         $nom = $m['nom_postnom'];
    //         $email = $m['email'];
    //         $tel = $m['phone_number'];
    //         // $mod = $m['modalite_engagement'];
    //         $statut = $m['status'] ? 'Actif' : 'En attente';

    //         $this->Cell($w[0], 10, $nom, 'LR', 0, 'L', $fill);
    //         $this->Cell($w[1], 10, $email, 'LR', 0, 'L', $fill);
    //         $this->Cell($w[2], 10, $tel, 'LR', 0, 'C', $fill);
    //         // $this->Cell($w[3], 10, $mod, 'LR', 0, 'C', $fill);
            
    //         // Couleur spécifique pour le statut
    //         if ($statut == 'Actif') {
    //             $this->SetTextColor(46, 125, 50); // Vert
    //         } else {
    //             $this->SetTextColor(211, 47, 47); // Rouge
    //         }
    //         $this->Cell($w[4], 10, $statut, 'LR', 0, 'C', $fill);
            
    //         $this->SetTextColor(50, 50, 50); // Reset couleur texte
    //         $this->Ln();
    //         $fill = !$fill;
    //     }

    //     // Ligne de fermeture du tableau
    //     $this->Cell(array_sum($w), 0, '', 'T');
        
    //     $this->Output('I', 'liste_membres.pdf');
    // }
}