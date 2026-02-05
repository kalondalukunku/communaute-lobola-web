<?php
class Helper {

    public static function formatDate($date)
    {
        if (empty($date)) return '';

        $timestamp = strtotime($date);
        if ($timestamp === false) return $date;

        return date('d/m/Y', $timestamp);
    }
    
    public static function formatDate2(string $date)
    {
        $timestamp = strtotime($date);
        return $date = date("d/m/Y à H:i", $timestamp);
    }

    public static function textTruncate($text, $maxLength = 50)
    {
        if (strlen($text) <= $maxLength) {
            return $text;
        }

        $truncated = substr($text, 0, $maxLength);
        // $lastSpace = strrpos($truncated, ' ');

        // if ($lastSpace !== false) {
        //     $truncated = substr($truncated, 0, $lastSpace);
        // }

        return $truncated . '...';
    }

    public static function getUrlPart()
    {
        return explode('/', $_GET['url']);
    }

    public static function setActive($url, $second = false)
    {
        if($second !== false) 
            return Self::getUrlPart()[0].'/'.Self::getUrlPart()[1] === $url ? "text-white bg-[var(--color-primary)] font-medium" : "text-gray-300 hover:text-white hover:bg-blue-900/50";
        return Self::getUrlPart()[0] === $url ? "text-white bg-[var(--color-primary)]" : "text-gray-300 hover:text-white hover:bg-blue-900/50";
    }

    public static function setActiveAdmin($url, $second = false)
    {
        if($second !== false) 
            return Self::getUrlPart()[0].'/'.Self::getUrlPart()[1] === $url ? "bg-primary text-paper font-bold shadow-lg shadow-primary/10" : "hover:bg-white/5 transition text-gray-400 group";
        return Self::getUrlPart()[0] === $url ? "bg-primary text-paper font-bold shadow-lg shadow-primary/10" : "hover:bg-white/5 transition text-gray-400 group";
    }

    public static function tempsRestant($date) 
    {
        if($date === null) return 'Non défini';

        date_default_timezone_set('Africa/Kinshasa');
        $timestamp_donnee = strtotime($date);
        $timestamp_actuel = time();

        $diff = $timestamp_donnee - $timestamp_actuel;

        if($diff <= 0) return 'Temps écoulé';

        $jours = floor($diff / 86400);
        $heures = floor(($diff % 86400) / 3600);
        $minutes = floor(($diff % 3600) / 60);
        $s = $jours > 1 ? 's' : '';

        return $jours ."jour$s ". $heures ."h ". $minutes ."min";
    }

    public static function courierColors($state) 
    {
        switch ($state) {
            case 'Temps écoulé':
                return "danger";

            case 'traité':
                return "info";

            case 'classé sans suite':
                return "primary";
                
            case 'classé':
                return "info";

            default :
                return "";
        };
    }

    public static function detectDeviceSize() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $mobileAgents = ['iPhone','Android','webOs','BlackBerry','iPod','Opera Mini','IEMobile'];
        $tabletAgents = ['iPad','Android'];

        foreach($mobileAgents as $agent) {
            if(stripos($userAgent, $agent) !== false) return 'mobile';
        }

        foreach($tabletAgents as $agent) {
            if(stripos($userAgent, $agent) !== false && stripos($userAgent, 'Mobile') === false) return 'md';
        }

        return 'lg';
    }

    public static function formatText($text) {
        switch (self::detectDeviceSize()) {
            case 'mobile':
                $limit = 9;
                break;

            case 'md':
                $limit = 14;
                break;

            case 'lg':
                $limit = 19;
                break;
        };
        
        if(strlen($text) <= $limit) return $text;
        $textCoup = substr($text,0, $limit);

        return $textCoup . '...';
    }

    public static function formatText2($text) {
        switch (self::detectDeviceSize()) {
            case 'mobile':
                $limit = 24;
                break;

            case 'md':
                $limit = 40;
                break;

            case 'lg':
                $limit = 87;
                break;
        };
        
        if(strlen($text) <= $limit) return $text;
        $textCoup = substr($text,0, $limit);

        return $textCoup . '...';
    }

    public static function stateSpinner($courier) {
        switch ($courier->status) {
            case 'en cours':
                return '<div class="spinner-grow spinner-grow-sm text-'.self::courierColors($courier).' mt-1" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>';

            case 'traité':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-journal-check text-'.self::courierColors($courier).'" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                            <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                            <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                        </svg>';
        };
    }

    public static function timeEgo($time) {
        
        $jours = floor($time / 86400);
        $heures = floor(($time % 86400) / 3600);
        $minutes = floor(($time % 3600) / 60);
        $s = $jours > 1 ? 's' : '';

        return $jours ."jour$s ". $heures ."h ". $minutes ."min";
    }

    public static function tempsEcoule($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $unites = [
            "année" => $diff->y,
            "mois" => $diff->m,
            "jour" => $diff->d,
            "heure" => $diff->h,
            "munite" => $diff->i,
            "seconde" => $diff->s,
        ];

        $result = [];
        foreach($unites as $unite => $value)
        {
            if($value)
            {
                $result[] = $value . " " . $unite . ($value > 1 ? 's' : '');
            }
        }

        if(!$full)
        {
            $result = array_slice($result, 0, 1);
        }

        return $result ? 'Il y a '. implode(', ', $result) : "A l'instant";
    }
        
    public static function getData(array $tableData, string $field, ?string $databaseValue = null) 
    {
        if (!isset($tableData[$field]) && !isset($databaseValue)) return '';
        if (isset($databaseValue) && !isset($table[$field])) return htmlentities($databaseValue);
    
        return htmlentities($tableData[$field]);
    }

    public static function getSelectedValue(string $field, string $value, ?string $databaseValue = null) 
    {
        $postedValue = $_POST[$field] ?? '';
        
        if ($postedValue === $value) return 'selected';
        
        if($databaseValue !== null && $databaseValue == $value) return 'selected';

    
        return "";
    }

    public static function getCheckedValue(string $field, string $value) 
    {
        if(isset($_POST[$field]) && $_POST[$field] === $value) return 'checked';
    
        return null;
    }
    
    public static function lengthValidation(string $data, int $min, int $max) 
    {
        if (mb_strlen($data) < $min) return false;
        if (mb_strlen($data) > $max) return false;

        return true;
    }

    public static function docIsUrgent($priority) 
    {
        if($priority === 'urgent') return 'urgent';
        return '';
    }

    public static function userCorrespondant($user)
    {
        return $user === $_SESSION[SITE_NAME_SESSION_USER]['nom'] ? '(Vous)' : '';
    }

    public static function getFirstLetter(string $string): string
    {
        // Vérifie si la chaîne est vide avant d'essayer de la traiter
        if (empty($string)) {
            return '';
        }

        // Utilise mb_substr pour la sécurité UTF-8 (longueur de 1 à partir du début, index 0)
        // Assurez-vous que l'encodage par défaut est défini, souvent 'UTF-8'
        return mb_substr($string, 0, 1);
    }

    public static function generatePaginationElement($page, $content, $isActive = false, $isDisabled = false) {
        $baseUrl = "?page=" . $page;
        // On conserve les paramètres de recherche s'ils existent dans l'URL
        if (isset($_GET['search'])) {
            $baseUrl .= "&search=" . urlencode($_GET['search']);
        }

        if ($isDisabled) {
            return '<button class="w-8 h-8 rounded-xl border border-gray-100 flex items-center justify-center text-gray-200 cursor-not-allowed transition" disabled>' . $content . '</button>';
        }

        if ($isActive) {
            return '<button class="w-8 h-8 rounded-xl bg-secondary text-primary font-bold text-xs shadow-lg shadow-secondary/10">' . $content . '</button>';
        }

        return '<a href="' . $baseUrl . '" class="w-8 h-8 rounded-xl border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-white hover:border-gray-300 transition text-xs font-bold">' . $content . '</a>';
    }

    /**
     * Génère la structure complète de la pagination
     */
    public static function generatePaginationFull($currentPage, $totalPages) {
        if ($totalPages < 1) return;

        echo '<div class="flex items-center gap-2">';

        // --- 1. Bouton Précédent ---
        $prev_page = $currentPage - 1;
        $is_prev_disabled = $currentPage <= 1;
        $prev_icon = '<i class="fas fa-chevron-left text-[10px]"></i>';
        echo self::generatePaginationElement($prev_page, $prev_icon, false, $is_prev_disabled);

        // --- 2. Liens des pages ---
        $display_limit = 3; 
        $start_page = max(1, $currentPage - 1);
        $end_page = min($totalPages, $start_page + $display_limit - 1);

        // Ajustement si on est à la fin
        if ($end_page - $start_page < $display_limit - 1) {
            $start_page = max(1, $totalPages - $display_limit + 1);
        }

        // Première page et points de suspension
        if ($start_page > 1) {
            echo self::generatePaginationElement(1, '1');
            if ($start_page > 2) {
                echo "<span class='w-8 h-8 flex items-center justify-center text-gray-400 text-xs'>...</span>";
            }
        }

        // Boucle des pages numériques
        for ($i = $start_page; $i <= $end_page; $i++) {
            echo self::generatePaginationElement($i, (string)$i, $i === $currentPage);
        }

        // Dernière page et points de suspension
        if ($end_page < $totalPages) {
            if ($end_page < $totalPages - 1) {
                echo "<span class='w-8 h-8 flex items-center justify-center text-gray-400 text-xs'>...</span>";
            }
            echo self::generatePaginationElement($totalPages, (string)$totalPages);
        }

        // --- 3. Bouton Suivant ---
        $next_page = $currentPage + 1;
        $is_next_disabled = $currentPage >= $totalPages;
        $next_icon = '<i class="fas fa-chevron-right text-[10px]"></i>';
        echo self::generatePaginationElement($next_page, $next_icon, false, $is_next_disabled);

        echo '</div>';
    }

    public static function returnTxtVwDc1($statut_conformite, $validite = null)
    {
        $txt = $statut_conformite;
        if($validite) $txt .= " - valable jusqu'au ". self::formatDate($validite);

        return $txt;
    }

    public static function returnTxtVwDc2($niveau_etude, $etablissement, $annee_obtention, $ville, $pays)
    {
        $txt = "$niveau_etude - $etablissement ($annee_obtention) - $ville - $pays";

        return $txt;
    }

    public static function getFirstTwoInitials(string $name): string 
    {
        // 1. Nettoyer les espaces superflus et séparer la chaîne en tableau
        $words = explode(' ', trim($name));
        
        // Filtrer le tableau pour supprimer les entrées vides (doubles espaces)
        $words = array_filter($words);
        
        $initials = "";
        $count = 0;

        foreach ($words as $word) {
            // 2. On récupère le premier caractère de chaque mot
            // mb_substr est utilisé pour gérer correctement les caractères accentués (UTF-8)
            $initials .= mb_substr($word, 0, 1);
            $count++;

            // 3. On s'arrête dès qu'on a récupéré 2 initiales
            if ($count >= 2) {
                break;
            }
        }

        // Retourner en majuscules
        return mb_strtoupper($initials);
    }
}