<?php
// Kleine hulpfuncties die op meerdere pagina's worden gebruikt

// Maakt tekst veilig om te tonen in HTML (voorkomt XSS)
function h($tekst)
{
    return htmlspecialchars($tekst, ENT_QUOTES, 'UTF-8');
}

// Zet seconden om naar mm:ss, bijvoorbeeld 125 wordt 02:05
function formatTijd($seconden)
{
    $seconden = (int) $seconden;
    return sprintf('%02d:%02d', intdiv($seconden, 60), $seconden % 60);
}
