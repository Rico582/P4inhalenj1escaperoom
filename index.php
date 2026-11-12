<?php
session_start();
require __DIR__ . '/includes/functies.php';

$naam        = '';
$foutmelding = '';

// Formulier verstuurd: nieuw spel starten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = trim($_POST['naam'] ?? '');

    if ($naam === '') {
        $foutmelding = 'Vul eerst je naam in.';
    } elseif (mb_strlen($naam) > 30) {
        $foutmelding = 'Je naam mag maximaal 30 tekens lang zijn.';
    } else {
        // Alles resetten en beginnen bij room 1
        $_SESSION = [];
        $_SESSION['naam']       = $naam;
        $_SESSION['start_spel'] = time();
        $_SESSION['room']       = 1;
        $_SESSION['vraag']      = 1;
        $_SESSION['room_start'] = time();
        $_SESSION['fout']       = false;

        header('Location: room1.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escape Room - Ontsnap uit het Serverhok</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="start">

    <main class="kaart">
        <h1>🔐 Ontsnap uit het Serverhok</h1>
        <p class="verhaal">
            Je loopt stage bij TechCorp. Net als je naar huis wilt, valt de deur van het
            serverhok in het slot. Alleen wie de vragen van het beveiligingssysteem goed
            beantwoordt, komt weer buiten. Lukt het jou om te ontsnappen?
        </p>

        <h2>Spelregels</h2>
        <ul class="regels">
            <li>🚪 Er zijn 3 rooms met elk 3 vragen.</li>
            <li>⏱️ Je hebt 5 minuten per room.</li>
            <li>❌ Fout antwoord? Dan begin je deze room opnieuw.</li>
            <li>⏰ Is de tijd op? Dan ben je game over.</li>
        </ul>

        <?php if ($foutmelding !== '') : ?>
            <p class="foutmelding"><?= h($foutmelding) ?></p>
        <?php endif; ?>

        <form method="post" action="index.php">
            <label for="naam">Wat is je naam?</label>
            <input type="text" id="naam" name="naam" maxlength="30" value="<?= h($naam) ?>" required>
            <button type="submit" class="knop">Start het spel 🚀</button>
        </form>
    </main>

</body>
</html>
