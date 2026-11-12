// Timer voor een room: telt af en stuurt je naar lose.php als de tijd op is.
// De startwaarde (in seconden) komt van de server via data-seconden.

const timerElement = document.getElementById('timer');
let seconden = parseInt(timerElement.dataset.seconden, 10);

function toonTijd() {
    const minuten = Math.floor(seconden / 60);
    const rest = seconden % 60;

    timerElement.textContent =
        String(minuten).padStart(2, '0') + ':' + String(rest).padStart(2, '0');

    // De laatste 30 seconden wordt de timer rood en gaat knipperen
    if (seconden <= 30) {
        timerElement.classList.add('timer-bijna');
    }
}

toonTijd();

const interval = setInterval(function () {
    seconden--;

    if (seconden <= 0) {
        clearInterval(interval);
        window.location.href = 'lose.php';
        return;
    }

    toonTijd();
}, 1000);
