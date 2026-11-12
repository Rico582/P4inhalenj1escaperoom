# Escape Room: Ontsnap uit het Serverhok

Webgame in PHP en MySQL met 3 rooms, 3 vragen per room en een timer van 5 minuten per room.

## Installatie (XAMPP)

1. Start **Apache** en **MySQL** in het XAMPP Control Panel.
2. Kopieer de map `escaperoom` naar `C:\xampp\htdocs\`.
3. Open `http://localhost/phpmyadmin`, kies **Importeren** en importeer `database.sql`.
4. Open `http://localhost/escaperoom/` en start het spel.

De databasegegevens staan in `includes/db.php` (standaard XAMPP: gebruiker `root`, geen wachtwoord).

## Bestanden

| Bestand | Doel |
|---|---|
| `index.php` | Startpagina met spelregels en naamformulier |
| `room1.php`, `room2.php`, `room3.php` | De drie rooms |
| `win.php` | Eindscherm met tijd en top 5 |
| `lose.php` | Game over als de timer op is |
| `database.sql` | Database, tabellen en alle vragen |
| `css/style.css` | Alle opmaak |
| `js/timer.js` | Afteltimer in de browser |
| `includes/db.php` | Databaseverbinding (PDO) |
| `includes/functies.php` | Hulpfuncties |
| `includes/room.php` | Gedeelde logica van de rooms |

## Werking

- De voortgang (naam, room, vraag, starttijd) staat in de PHP-sessie.
- De timer wordt op de server gecontroleerd, dus verversen of de pagina sluiten helpt niet.
- Een fout antwoord toont een rood scherm. Met **Begin opnieuw** begin je weer bij vraag 1 van die room met een nieuwe timer.
- Je kunt niet naar een andere room springen via de adresbalk.
- Na het winnen wordt je score opgeslagen in de tabel `scores`.

## Aanpassen

- Vragen, antwoorden en hints: tabel `vragen`.
- Tijd per room: kolom `tijd_seconden` in de tabel `rooms` (zet tijdelijk op 20 om game over te testen).
