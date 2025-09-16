<?php
/**
 * Script de test du ServicePraticien
 * Usage: php tests/scripts/test_service.php
 */

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/bootstrap.php';

use toubilib\core\application\usecases\ServicePraticien;
use toubilib\infra\repositories\PDOPraticienRepository;

echo "=== Test du Service Praticien ===\n";

try {
    // Récupération de la connexion PDO depuis la config existante
    // (adaptez selon votre bootstrap.php)
    $container = require __DIR__ . '/../../config/bootstrap.php';
    $pdo = $container->get(PDO::class);

    // Ou si pas de container, connexion directe :
    // $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=toubiprat', 'toubi', 'password');

    // Test du service
    $repository = new PDOPraticienRepository($pdo);
    $service = new ServicePraticien($repository);

    $praticiens = $service->listerPraticiens();

    echo "✅ Nombre de praticiens : " . count($praticiens) . "\n\n";

    if (!empty($praticiens)) {
        echo "📋 Premier praticien (DTO) :\n";
        $premier = $praticiens[0];
        echo "- ID: {$premier->id}\n";
        echo "- Nom: {$premier->titre} {$premier->prenom} {$premier->nom}\n";
        echo "- Ville: {$premier->ville}\n";
        echo "- Spécialité: {$premier->specialite}\n";
        echo "- Accepte nouveaux patients: " . ($premier->accepteNouveauPatient ? 'Oui' : 'Non') . "\n\n";

        echo "📋 Liste complète :\n";
        foreach ($praticiens as $praticien) {
            echo "- {$praticien->titre} {$praticien->prenom} {$praticien->nom} ({$praticien->specialite})\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "📍 Fichier : " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "📍 Trace :\n" . $e->getTraceAsString() . "\n";
}