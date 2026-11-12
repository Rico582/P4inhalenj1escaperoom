-- =====================================================
-- Escape Room - database
-- Importeren: phpMyAdmin > Importeren > kies dit bestand
-- =====================================================

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS escaperoom
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE escaperoom;

-- Oude tabellen verwijderen, zodat je dit bestand opnieuw kunt importeren
DROP TABLE IF EXISTS scores;
DROP TABLE IF EXISTS vragen;
DROP TABLE IF EXISTS rooms;

-- -----------------------------------------------------
-- Tabel: rooms (de 3 kamers)
-- -----------------------------------------------------
CREATE TABLE rooms (
    id            INT          NOT NULL AUTO_INCREMENT,
    naam          VARCHAR(100) NOT NULL,
    verhaal       TEXT         NOT NULL,
    tijd_seconden INT          NOT NULL DEFAULT 300,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabel: vragen (3 vragen per room)
-- juist = de letter van het goede antwoord (A, B, C of D)
-- -----------------------------------------------------
CREATE TABLE vragen (
    id        INT          NOT NULL AUTO_INCREMENT,
    room_id   INT          NOT NULL,
    volgorde  INT          NOT NULL,
    vraag     VARCHAR(255) NOT NULL,
    optie_a   VARCHAR(150) NOT NULL,
    optie_b   VARCHAR(150) NOT NULL,
    optie_c   VARCHAR(150) NOT NULL,
    optie_d   VARCHAR(150) NOT NULL,
    juist     CHAR(1)      NOT NULL,
    hint      VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY unieke_volgorde (room_id, volgorde),
    FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabel: scores (spelers die zijn ontsnapt)
-- -----------------------------------------------------
CREATE TABLE scores (
    id       INT         NOT NULL AUTO_INCREMENT,
    naam     VARCHAR(30) NOT NULL,
    seconden INT         NOT NULL,
    datum    DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Gegevens: rooms (300 seconden = 5 minuten per room)
-- -----------------------------------------------------
INSERT INTO rooms (id, naam, verhaal, tijd_seconden) VALUES
(1, 'Het Serverhok',
    'De deur valt achter je dicht en het slot klikt. Je zit opgesloten in het serverhok van TechCorp! Beantwoord de netwerkvragen om de deur te openen.',
    300),
(2, 'De Database Kluis',
    'Je staat voor een zware kluis vol klantgegevens. Het beveiligingssysteem draait op een database. Beantwoord de SQL-vragen om de kluis te openen.',
    300),
(3, 'De Code Kelder',
    'In de kelder zit de laatste deur naar buiten. Het slot is geschreven in PHP. Los de laatste vragen op en ontsnap!',
    300);

-- -----------------------------------------------------
-- Gegevens: vragen
-- -----------------------------------------------------

-- Room 1: Het Serverhok (netwerken)
INSERT INTO vragen (room_id, volgorde, vraag, optie_a, optie_b, optie_c, optie_d, juist, hint) VALUES
(1, 1, 'Welk protocol zorgt voor een beveiligde (versleutelde) verbinding met een website?',
    'HTTP', 'FTP', 'HTTPS', 'SMTP', 'C',
    'Kijk naar het slotje in de adresbalk van je browser.'),
(1, 2, 'Wat is de taak van een DHCP-server?',
    'Websites opslaan en tonen', 'Automatisch IP-adressen uitdelen aan apparaten', 'E-mails versturen', 'Virussen verwijderen', 'B',
    'Je laptop krijgt thuis vanzelf een IP-adres toegewezen.'),
(1, 3, 'Uit hoeveel bits bestaat een IPv4-adres?',
    '16', '24', '64', '32', 'D',
    'Een IPv4-adres bestaat uit 4 getallen (octetten) van elk 8 bits.');

-- Room 2: De Database Kluis (SQL)
INSERT INTO vragen (room_id, volgorde, vraag, optie_a, optie_b, optie_c, optie_d, juist, hint) VALUES
(2, 1, 'Met welk SQL-commando haal je gegevens op uit een tabel?',
    'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'A',
    'Met dit commando kies je welke kolommen je wilt zien.'),
(2, 2, 'Welk SQL-onderdeel gebruik je om alleen bepaalde rijen op te halen?',
    'ORDER BY', 'GROUP BY', 'FROM', 'WHERE', 'D',
    'Je gebruikt dit om een voorwaarde (conditie) te stellen in je query.'),
(2, 3, 'Wat is een primary key (primaire sleutel)?',
    'Een wachtwoord voor de database', 'Een back-up van de tabel', 'Een kolom die elke rij in de tabel uniek maakt', 'Een sleutel om de database te versleutelen', 'C',
    'Denk aan het id-nummer van een klant: dat is nooit voor twee klanten hetzelfde.');

-- Room 3: De Code Kelder (PHP)
INSERT INTO vragen (room_id, volgorde, vraag, optie_a, optie_b, optie_c, optie_d, juist, hint) VALUES
(3, 1, 'Met welk teken begint de naam van een variabele in PHP?',
    'Met een #', 'Met een $', 'Met een @', 'Met een &', 'B',
    'Denk aan een valutateken.'),
(3, 2, 'Welke superglobal bevat de gegevens van een formulier dat met method="post" is verstuurd?',
    '$_GET', '$_SESSION', '$_POST', '$_COOKIE', 'C',
    'De naam van de verzendmethode zit in de naam van de variabele.'),
(3, 3, 'Met welke functie start je een sessie in PHP?',
    'session_start()', 'start_session()', 'new_session()', 'begin_session()', 'A',
    'Je zet deze functie bovenaan elk bestand waarin je $_SESSION gebruikt.');
