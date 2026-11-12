<?php
/*
 * Gedeelde logica en opmaak voor alle rooms.
 * Elk roomX.php-bestand zet eerst $room_nummer en laadt daarna dit bestand.
 */

session_start();
require __DIR__ . '/db.php';
require __DIR__ . '/functies.php';

$aantal_rooms  = 3;
$aantal_vragen = 3;

// 1. Is het spel gestart? Zo niet: terug naar de startpagina
if (!isset($_SESSION['naam'])) {
    header('Location: index.php');
    exit;
}

// 2. Zit de speler in deze room? Zo niet: doorsturen naar de juiste pagina
if ($_SESSION['room'] != $room_nummer) {
    $bestemming = ($_SESSION['room'] > $aantal_rooms) ? 'win.php' : 'room' . $_SESSION['room'] . '.php';
    header('Location: ' . $bestemming);
    exit;
}

// 3. Gegevens van deze room ophalen
$stmt = $pdo->prepare('SELECT * FROM rooms WHERE id = ?');
$stmt->execute([$room_nummer]);
$room = $stmt->fetch();

if (!$room) {
    die('Deze room staat niet in de database. Controleer of database.sql is geïmporteerd.');
}

// 4. "Begin opnieuw" na een fout antwoord (alleen mogelijk op het rode scherm)
if ($_SESSION['fout'] && isset($_GET['opnieuw'])) {
    $_SESSION['vraag']      = 1;
    $_SESSION['fout']       = false;
    $_SESSION['room_start'] = time(); // De timer begint ook opnieuw. Wil je dat de klok doorloopt? Verwijder deze regel.
    header('Location: room' . $room_nummer . '.php');
    exit;
}

// 5. Timer: hoeveel seconden zijn er nog over? Is de tijd op, dan ben je game over
$resterend = $room['tijd_seconden'] - (time() - $_SESSION['room_start']);

if ($resterend <= 0 && !$_SESSION['fout']) {
    header('Location: lose.php');
    exit;
}

// 6. De huidige vraag ophalen
$stmt = $pdo->prepare('SELECT * FROM vragen WHERE room_id = ? AND volgorde = ?');
$stmt->execute([$room_nummer, $_SESSION['vraag']]);
$vraag = $stmt->fetch();

if (!$vraag) {
    die('Vraag niet gevonden. Controleer of database.sql is geïmporteerd.');
}

// 7. Antwoord verwerken (formulier is verstuurd)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$_SESSION['fout']) {
    $gekozen  = $_POST['antwoord'] ?? '';
    $vraag_id = (int) ($_POST['vraag_id'] ?? 0);

    // Alleen verwerken als het antwoord bij de huidige vraag hoort
    if ($gekozen !== '' && $vraag_id === (int) $vraag['id']) {

        if ($gekozen === $vraag['juist']) {
            // Goed antwoord: volgende vraag
            $_SESSION['vraag']++;
            $_SESSION['melding'] = '✅ Goed! Je hebt een sleutel gevonden.';

            // Alle vragen van deze room goed: door naar de volgende room
            if ($_SESSION['vraag'] > $aantal_vragen) {
                $_SESSION['room']++;
                $_SESSION['vraag']      = 1;
                $_SESSION['room_start'] = time();
                $_SESSION['melding']    = '🔓 Room opgelost! De deur gaat open...';

                // Laatste room opgelost: eindtijd bewaren voor win.php
                if ($_SESSION['room'] > $aantal_rooms) {
                    $_SESSION['eindtijd'] = time();
                }
            }
        } else {
            // Fout antwoord: rood scherm tonen
            $_SESSION['fout'] = true;
        }
    }

    // Pagina opnieuw laden (voorkomt dubbel versturen bij verversen)
    header('Location: room' . $room_nummer . '.php');
    exit;
}

// 8. Gegevens voor de pagina klaarzetten
$melding = $_SESSION['melding'] ?? '';
unset($_SESSION['melding']);

$opties = [
    'A' => $vraag['optie_a'],
    'B' => $vraag['optie_b'],
    'C' => $vraag['optie_c'],
    'D' => $vraag['optie_d'],
];

$body_class = $_SESSION['fout'] ? 'fout-scherm' : 'room' . $room_nummer;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($room['naam']) ?> - Escape Room</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?= $body_class ?>">

<?php if ($_SESSION['fout']) : ?>

    <!-- Rood scherm na een fout antwoord -->
    <main class="fout-box">
        <h1>❌ Fout antwoord!</h1>
        <p>Het alarm gaat af en alle deuren vergrendelen. Je moet deze room opnieuw beginnen.</p>
        <a class="knop" href="room<?= $room_nummer ?>.php?opnieuw=1">Begin opnieuw</a>
    </main>

<?php else : ?>

    <header class="topbalk">
        <div>👤 <?= h($_SESSION['naam']) ?></div>
        <div>Room <?= $room_nummer ?> van <?= $aantal_rooms ?></div>
        <div id="timer" class="timer" data-seconden="<?= (int) $resterend ?>">--:--</div>
    </header>

    <main class="kaart">
        <h1><?= h($room['naam']) ?></h1>
        <p class="verhaal"><?= h($room['verhaal']) ?></p>

        <?php if ($melding !== '') : ?>
            <p class="melding"><?= h($melding) ?></p>
        <?php endif; ?>

        <!-- Voortgang: 🔑 = vraag opgelost, 🔒 = nog te doen -->
        <div class="voortgang">
            <?php for ($i = 1; $i <= $aantal_vragen; $i++) : ?>
                <?= ($i < $_SESSION['vraag']) ? '🔑' : '🔒' ?>
            <?php endfor; ?>
        </div>

        <h2>Vraag <?= $_SESSION['vraag'] ?> van <?= $aantal_vragen ?></h2>
        <p class="vraag"><?= h($vraag['vraag']) ?></p>

        <form method="post" action="room<?= $room_nummer ?>.php">
            <input type="hidden" name="vraag_id" value="<?= (int) $vraag['id'] ?>">

            <?php foreach ($opties as $letter => $tekst) : ?>
                <label class="optie">
                    <input type="radio" name="antwoord" value="<?= $letter ?>" required>
                    <span><?= $letter ?>. <?= h($tekst) ?></span>
                </label>
            <?php endforeach; ?>

            <button type="submit" class="knop">Controleer antwoord</button>
        </form>

        <details class="hint">
            <summary>💡 Hint nodig?</summary>
            <p><?= h($vraag['hint']) ?></p>
        </details>
    </main>

    <script src="js/timer.js"></script>

<?php endif; ?>

</body>
</html>
