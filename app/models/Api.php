<?php
class Api extends Model {

    public function getAllUsersAsJson() {
        $sql = "
            SELECT 
                email, 
                nom_postnom AS nom, 
                'membre' AS type 
            FROM members
            
            UNION ALL
            
            SELECT 
                email, 
                nom_complet AS nom, 
                'enseignant' AS type 
            FROM enseignants
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        // Récupération de tous les résultats sous forme de tableau associatif
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Définition de l'en-tête pour une API JSON
        header('Content-Type: application/json; charset=utf-8');

        // Retourne les données encodées en JSON
        return json_encode([
            "status" => "success",
            "count" => count($results),
            "data" => $results
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function generateLobolaApiKey() {
        $prefix = "api_lobola_access_";
        
        // On génère 36 octets de données aléatoires (haute sécurité)
        $randomBytes = random_bytes(36);
        
        // On convertit en Base64 pour avoir un mélange de lettres (A-Z, a-z) et chiffres (0-9)
        $base64 = base64_encode($randomBytes);
        
        // On retire les caractères qui pourraient poser problème (+, /, =) 
        // et on prend les 48 premiers caractères pour une longueur constante
        $cleanToken = substr(str_replace(['+', '/', '='], '', $base64), 0, 48);
        
        return $prefix . $cleanToken;
    }
}