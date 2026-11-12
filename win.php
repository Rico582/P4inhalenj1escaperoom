<?php
session_start();
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functies.php';

// Alleen toegankelijk als je alle 3 de rooms hebt opgelost
if (!isset($_SESSION['naam'])) {
    header('Location: index.php');
    exit;
}

if ($_SESSION['room'] <= 3) {
    header('Location: room' . $_SESSION['room'] . '.php');
    exit;
}

// Totale speeltijd in seconden
$seconden = $_SESSION['eindtijd'] - $_SESSION['start_spel'];

// Score maar één keer opslaan (ook als de pagina wordt ververst)
if (empty($_SESSION['score_opgeslagen'])) {
    $stmt = $pdo->prepare('INSERT INTO scores (naam, seconden) VALUES (?, ?)');
    $stmt->execute([$_SESSION['naam'], $seconden]);
    $_SESSION['score_opgeslagen'] = true;
}

// De 5 snelste spelers ophalen
$top = $pdo->query('SELECT naam, seconden, datum FROM scores ORDER BY seconden ASC, datum ASC LIMIT 5')->fetchAll();
$medailles = ['🥇', '🥈', '🥉'];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ontsnapt! - Escape Room</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="win">

    <main class="kaart">
        <div class="trofee">🏆</div>
        <h1>Gefeliciteerd, <?= h($_SESSION['naam']) ?>!</h1>
        <p class="verhaal">
            De laatste deur zwaait open en je staat buiten. Je bent ontsnapt uit het serverhok van TechCorp!
        </p>
        <p class="tijdresultaat">Jouw tijd: <strong><?= formatTijd($seconden) ?></strong></p>

        <h2>Top 5 snelste spelers</h2>
        <table class="scoretabel">
            <tr>
                <th>#</th>
                <th>Naam</th>
                <th>Tijd</th>
                <th>Datum</th>
            </tr>
            <?php foreach ($top as $nummer => $rij) : ?>
                <tr>
                    <td><?= $medailles[$nummer] ?? ($nummer + 1) ?></td>
                    <td><?= h($rij['naam']) ?></td>
                    <td><?= formatTijd($rij['seconden']) ?></td>
                    <td><?= date('d-m-Y', strtotime($rij['datum'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p><a class="knop" href="index.php">Speel opnieuw</a></p>
    </main>

</body>
</html>
