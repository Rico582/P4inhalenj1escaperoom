<?php
session_start();
require __DIR__ . '/includes/functies.php';

$naam = $_SESSION['naam'] ?? 'speler';
$room = min($_SESSION['room'] ?? 1, 3);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game over - Escape Room</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="lose">

    <main class="kaart">
        <h1>⏰ Game over</h1>
        <p class="verhaal">
            De tijd is om, <?= h($naam) ?>! De deuren blijven op slot en je zit nog steeds
            vast in het serverhok. Je kwam tot room <?= $room ?> van 3.
        </p>
        <p><a class="knop" href="index.php">Opnieuw proberen</a></p>
    </main>

</body>
</html>
