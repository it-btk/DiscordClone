<?php
// Read counter
$counterFile = __DIR__ . '/../counter.json';
$count = 0;
if (file_exists($counterFile)) {
    $data = json_decode(file_get_contents($counterFile), true);
    $count = $data['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wiki – Discord Cloner</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../style.css">
  
</head>
<body class="page-wrapper">

<div class="page-header">
  <nav class="navbar" style="position: relative; padding: 20px 40px;">
    <a href="../" class="nav-brand">
      <i class="fab fa-discord" style="font-size: 1.8rem;"></i>
      Discord Cloner
    </a>
    <div class="nav-links">
      <a href="../clone/server/">Server Cloner</a>
      <a href="../clone/emoji/">Emoji Cloner</a>
      <a href="../lookup/user/">User Lookup</a>
      <a href="../lookup/token/">Token Check</a>
      <a href="../wiki/">Wiki</a>
    </div>
    <div class="nav-right">
      <a href="../clone/server/" class="btn-login">Starten</a>
    </div>
  </nav>
</div>

<main class="wiki-wrapper">
  <div class="wiki-header">
    <h1><i class="fas fa-book"></i> Wiki & Anleitung</h1>
    <p>Alles, was du über die Discord Tools wissen musst – von der Token-Extraktion bis zur Fehlerbehebung.</p>
  </div>

  <!-- TOKEN FINDEN -->
  <div class="wiki-card">
    <h2><i class="fas fa-key"></i> Discord User Token finden</h2>
    <p>Der Discord User Token ist dein persönlicher Authentifizierungsschlüssel. Du brauchst ihn für den Server- und Emoji-Cloner.</p>

    <div class="alert danger">
      <i class="fas fa-exclamation-triangle"></i>
      <span><strong>Wichtig:</strong> Gib deinen Token niemals an andere weiter! Mit deinem Token kann jemand vollen Zugriff auf deinen Discord-Account erhalten.</span>
    </div>

    <h3>Anleitung (Desktop / Browser)</h3>
    <ol class="step-list">
      <li>
        <span class="step-num">1</span>
        <div>
          <strong>Discord im Browser öffnen</strong>
          <p>Öffne <a href="https://discord.com/app" style="color:var(--primary)" target="_blank">discord.com/app</a> und melde dich an.</p>
        </div>
      </li>
      <li>
        <span class="step-num">2</span>
        <div>
          <strong>Entwicklertools öffnen</strong>
          <p>Drücke <code>F12</code> oder <code>Ctrl + Shift + I</code> (Windows) / <code>Cmd + Option + I</code> (Mac).</p>
        </div>
      </li>
      <li>
        <span class="step-num">3</span>
        <div>
          <strong>Zum Network-Tab wechseln</strong>
          <p>Klicke auf den Tab <strong>"Network"</strong> (Netzwerk).</p>
        </div>
      </li>
      <li>
        <span class="step-num">4</span>
        <div>
          <strong>Seite neu laden & filtern</strong>
          <p>Lade die Seite mit <code>F5</code> neu. Gib im Filter <code>api</code> oder <code>/science</code> ein.</p>
        </div>
      </li>
      <li>
        <span class="step-num">5</span>
        <div>
          <strong>Token aus Headers kopieren</strong>
          <p>Klicke auf einen Eintrag → Reiter <strong>"Headers"</strong> → suche nach <code>authorization</code>. Rechts steht dein Token.</p>
        </div>
      </li>
      <li>
        <span class="step-num">6</span>
        <div>
          <strong>Alternativ: Local Storage</strong>
          <p>Tab <strong>"Application"</strong> → <strong>"Local Storage"</strong> → <code>https://discord.com</code> → Eintrag <code>token</code>.</p>
        </div>
      </li>
    </ol>

    <div class="alert info">
      <i class="fas fa-lightbulb"></i>
      <span><strong>Tipp:</strong> Der Token sieht etwa so aus: <code>ODc2MjU0MzIxODc2NTQzMjE.G7hK2L.abcdefghijklmnopqrstuvwxyz123456</code></span>
    </div>
  </div>

  <!-- USER ID -->
  <div class="wiki-card">
    <h2><i class="fas fa-id-badge"></i> Discord User ID finden</h2>
    <ol class="step-list">
      <li>
        <span class="step-num">1</span>
        <div>
          <strong>Entwicklermodus aktivieren</strong>
          <p>Discord <strong>Einstellungen</strong> → <strong>Erweitert</strong> → Entwicklermodus AN.</p>
        </div>
      </li>
      <li>
        <span class="step-num">2</span>
        <div>
          <strong>ID kopieren</strong>
          <p>Rechtsklick auf Namen/Profilbild → <strong>"ID kopieren"</strong>.</p>
        </div>
      </li>
    </ol>
  </div>

  <!-- SERVER IDs -->
  <div class="wiki-card">
    <h2><i class="fas fa-server"></i> Server-IDs finden</h2>
    <p>Für den Cloner brauchst du zwei Server-IDs: Quelle (zu kopieren) und Ziel (wohin kopiert wird).</p>

    <ol class="step-list">
      <li>
        <span class="step-num">1</span>
        <div>
          <strong>Entwicklermodus aktivieren</strong>
          <p>Wie oben: Einstellungen → Erweitert → Entwicklermodus AN.</p>
        </div>
      </li>
      <li>
        <span class="step-num">2</span>
        <div>
          <strong>Server-ID kopieren</strong>
          <p>Rechtsklick auf Servernamen in der linken Leiste → <strong>"Server-ID kopieren"</strong>.</p>
        </div>
      </li>
    </ol>

    <div class="alert danger">
      <i class="fas fa-exclamation-triangle"></i>
      <span><strong>Achtung:</strong> Der Ziel-Server wird beim Server-Cloner <u>vollständig bereinigt</u> (Kanäle, Rollen, Berechtigungen).</span>
    </div>
  </div>

  <!-- FAQ -->
  <div class="wiki-card">
    <h2><i class="fas fa-question-circle"></i> FAQ</h2>

    <div class="faq-item">
      <h4>Token wird nicht akzeptiert?</h4>
      <p>Token vollständig kopieren (ohne Leerzeichen). Beginnt meist mit Großbuchstaben oder Zahl.</p>
    </div>
    <div class="faq-item">
      <h4>Cloner bleibt bei "Vorbereitung" hängen?</h4>
      <p>Token und Server-IDs prüfen. Browser-Konsole <code>F12</code> → Console öffnen für Fehlerdetails.</p>
    </div>
    <div class="faq-item">
      <h4>Nicht alle Kanäle/Rollen werden kopiert?</h4>
      <p>Discord hat API-Rate-Limits. Bei großen Servern Vorgang einfach erneut starten.</p>
    </div>
    <div class="faq-item">
      <h4>Emojis werden nicht kopiert?</h4>
      <p>Ziel-Server muss freie Emoji-Slots haben. Der Cloner stoppt automatisch am Limit.</p>
    </div>
    <div class="faq-item">
      <h4>Ist das legal?</h4>
      <p>Der Einsatz von Automatisierungstools verstößt gegen Discord-AGB. Nutze die Tools nur auf eigenen Servern oder mit ausdrücklicher Erlaubnis.</p>
    </div>
  </div>

  <!-- RECHTLICHES -->
  <div class="wiki-card">
    <h2><i class="fas fa-gavel"></i> Rechtliche Hinweise</h2>

    <div class="alert warning">
      <i class="fas fa-exclamation-triangle"></i>
      <span><strong>Haftungsausschluss:</strong></span>
    </div>
    <ul>
      <li>Dieses Tool wird für Bildungszwecke bereitgestellt.</li>
      <li>Der Einsatz erfolgt auf eigenes Risiko.</li>
      <li>Discord kann Accounts sperren, die gegen die AGB verstoßen.</li>
      <li>Wir sind nicht mit Discord Inc. verbunden.</li>
      <li>Keine Nutzung für Spam, Belästigung oder illegale Aktivitäten.</li>
    </ul>
  </div>
</main>

<footer class="footer">
  <div class="footer-container">
    <div class="footer-top">
      <div class="footer-brand">
        <h2>Discord Cloner</h2>
        <div class="social-links">
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-facebook-square"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
      <div class="footer-links">
        <div class="footer-col">
          <h4>Tools</h4>
          <ul>
            <li><a href="../clone/server/">Server Cloner</a></li>
            <li><a href="../clone/emoji/">Emoji Cloner</a></li>
            <li><a href="../lookup/user/">User Lookup</a></li>
            <li><a href="../lookup/token/">Token Check</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Ressourcen</h4>
          <ul>
            <li><a href="../wiki/">Wiki</a></li>
            <li><a href="https://github.com/it-btk/DiscordClone">GitHub</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-bottom-wrapper">
      <div class="footer-bottom">
        <a href="../" class="footer-bottom-brand">
          <i class="fab fa-discord" style="font-size: 1.8rem;"></i>
          Discord Cloner
        </a>
        <a href="../clone/server/" class="btn-footer">Starten</a>
      </div>
    </div>
  </div>
</footer>

</body>
</html>