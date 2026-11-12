<?php
// Verbinding met de database (standaardinstellingen van XAMPP)
$host       = 'localhost';
$dbnaam     = 'escaperoom';
$gebruiker  = 'root';
$wachtwoord = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbnaam;charset=utf8mb4",
        $gebruiker,
        $wachtwoord
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Geen verbinding met de database. Is MySQL gestart in XAMPP en is database.sql geïmporteerd?');
}
