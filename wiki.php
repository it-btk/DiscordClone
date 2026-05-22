<?php
// Read counter
$counterFile = __DIR__ . '/counter.json';
$count = 0;
if (file_exists($counterFile)) {
    $data = json_decode(file_get_contents($counterFile), true);
    $count = $data['count'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wiki – Discord Cloner</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    .wiki-content {
      max-width: 800px;
      margin: 0 auto;
      padding: 0 1.5rem 3rem;
    }

    .wiki-content h1 {
      font-size: 2rem;
      font-weight: 800;
      letter-spacing: -0.05em;
      color: #fff;
      margin-bottom: 2rem;
    }

    .wiki-section {
      background: rgba(255,255,255,0.02);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 18px;
      padding: 1.8rem;
      margin-bottom: 2rem;
    }

    .wiki-section h2 {
      font-size: 1.2rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }

    .wiki-section h2 i {
      color: var(--primary);
    }

    .wiki-section h3 {
      font-size: 0.95rem;
      font-weight: 700;
      color: rgba(255,255,255,0.85);
      margin: 1.2rem 0 0.6rem;
    }

    .wiki-section p {
      font-size: 0.88rem;
      line-height: 1.7;
      color: var(--text-soft);
      margin-bottom: 0.8rem;
    }

    .wiki-section ul, .wiki-section ol {
      padding-left: 1.5rem;
      margin-bottom: 0.8rem;
    }

    .wiki-section li {
      font-size: 0.88rem;
      line-height: 1.7;
      color: var(--text-soft);
      margin-bottom: 0.3rem;
    }

    .wiki-section code {
      background: rgba(124,92,255,0.15);
      color: #c8b8ff;
      padding: 0.15rem 0.5rem;
      border-radius: 6px;
      font-size: 0.82rem;
      font-family: "JetBrains Mono", "SF Mono", monospace;
    }

    .wiki-section .highlight-box {
      background: rgba(255,184,77,0.08);
      border-left: 3px solid var(--warning);
      padding: 0.8rem 1rem;
      border-radius: 10px;
      margin: 1rem 0;
      font-size: 0.85rem;
      color: var(--text-soft);
    }

    .wiki-section .highlight-box i {
      color: var(--warning);
      margin-right: 0.5rem;
    }

    .wiki-section .danger-box {
      background: rgba(255,107,107,0.08);
      border-left: 3px solid var(--danger);
      padding: 0.8rem 1rem;
      border-radius: 10px;
      margin: 1rem 0;
      font-size: 0.85rem;
      color: var(--text-soft);
    }

    .wiki-section .danger-box i {
      color: var(--danger);
      margin-right: 0.5rem;
    }

    .wiki-step {
      display: flex;
      gap: 1rem;
      align-items: flex-start;
      margin-bottom: 1rem;
    }

    .wiki-step-num {
      background: var(--primary);
      color: #fff;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.78rem;
      font-weight: 700;
      flex-shrink: 0;
      margin-top: 0.15rem;
    }

    @media (max-width: 640px) {
      .wiki-content {
        padding: 0 1rem 2rem;
      }
      .wiki-section {
        padding: 1.2rem;
      }
    }
  </style>
</head>
<body>

<header class="topbar">
  <div class="brand">
    <img src="logo.png" alt="IT-Solutions Bittkau" class="brand-logo">
    <div class="brand-text">
      <span class="brand-title">Discord Cloner</span>
      <span class="brand-sub">We are not afiliated with Discord</span>
    </div>
  </div>
  <nav class="top-nav">
    <a href="index.php" class="nav-link">Start</a>
    <a href="server.php" class="nav-link">Server Cloner</a>
    <a href="emojis.php" class="nav-link">Nur Emojis</a>
    <a href="lookup.php" class="nav-link">User Lookup</a>
    <a href="wiki.php" class="nav-link" style="background:rgba(124,92,255,0.15);color:#fff;">Wiki / Anleitung</a>
  </nav>
  <div class="status-badge">
    <span>Bereits <strong><?php echo $count; ?></strong> Server geklont</span>
  </div>
</header>

<main class="wiki-content">
  <h1><i class="fas fa-book" style="color:var(--primary);margin-right:0.6rem;"></i> Wiki & Anleitung</h1>

  <!-- TOKEN ANLEITUNG -->
  <div class="wiki-section">
    <h2><i class="fas fa-key"></i> Discord User Token finden</h2>
    <p>Der Discord User Token ist dein persönlicher Authentifizierungsschlüssel. Du brauchst ihn, damit der Cloner im Namen deines Accounts Aktionen durchführen kann.</p>

    <div class="danger-box">
      <i class="fas fa-exclamation-triangle"></i>
      <strong>Wichtig:</strong> Gib deinen Token niemals an andere weiter! Mit deinem Token kann jemand vollen Zugriff auf deinen Discord-Account erhalten.
    </div>

    <h3>Anleitung (Desktop / Browser)</h3>

    <div class="wiki-step">
      <span class="wiki-step-num">1</span>
      <div>
        <strong>Discord im Browser öffnen</strong>
        <p>Öffne <a href="https://discord.com/app" style="color:var(--primary)" target="_blank">discord.com/app</a> und melde dich an.</p>
      </div>
    </div>

    <div class="wiki-step">
      <span class="wiki-step-num">2</span>
      <div>
        <strong>Entwicklertools öffnen</strong>
        <p>Drücke <code>F12</code> oder <code>Ctrl + Shift + I</code> (Windows) / <code>Cmd + Option + I</code> (Mac), um die Entwicklertools zu öffnen.</p>
      </div>
    </div>

    <div class="wiki-step">
      <span class="wiki-step-num">3</span>
      <div>
        <strong>Zum Network-Tab wechseln</strong>
        <p>Klicke auf den Tab <strong>"Network"</strong> (Netzwerk).</p>
      </div>
    </div>

    <div class="wiki-step">
      <span class="wiki-step-num">4</span>
      <div>
        <strong>Seite neu laden</strong>
        <p>Lade die Discord-Seite mit <code>F5</code> oder <code>Ctrl + R</code> neu. Es erscheinen viele Einträge im Network-Tab.</p>
      </div>
    </div>

    <div class="wiki-step">
      <span class="wiki-step-num">5</span>
      <div>
        <strong>Requests filtern</strong>
        <p>Gib oben im Filter <code>api</code> oder <code>/science</code> ein, um die Liste einzugrenzen.</p>
      </div>
    </div>

    <div class="wiki-step">
      <span class="wiki-step-num">6</span>
      <div>
        <strong>Token kopieren</strong>
        <p>Klicke auf einen beliebigen Eintrag. Im Reiter <strong>"Headers"</strong> (Kopfzeilen) suchst du nach <code>authorization</code>. Rechts neben dem Wort steht dein Token (ein langer String aus Buchstaben, Zahlen und Punkten/Strichen).</p>
        <p>Alternativ: Im Tab <strong>"Application"</strong> (Anwendung) → <strong>"Local Storage"</strong> → <code>https://discord.com</code> → dort findest du den Eintrag <code>token</code>.</p>
      </div>
    </div>

    <div class="wiki-step">
      <span class="wiki-step-num">7</span>
      <div>
        <strong>Token im Cloner einfügen</strong>
        <p>Kopiere den gesamten Token (ohne Anführungszeichen) und füge ihn im Feld "Discord Token" im Cloner ein.</p>
      </div>
    </div>

    <div class="highlight-box">
      <i class="fas fa-lightbulb"></i>
      <strong>Tipp:</strong> Der Token sieht etwa so aus: <code>ODc2MjU0MzIxODc2NTQzMjE.G7hK2L.abcdefghijklmnopqrstuvwxyz123456</code>
    </div>
  </div>

  <!-- USER ID ANLEITUNG -->
  <div class="wiki-section">
    <h2><i class="fas fa-id-badge"></i> Discord User ID finden</h2>
    <p>Deine User ID wird benötigt, falls du bestimmte Aktionen auf einen bestimmten User beschränken möchtest.</p>

    <div class="wiki-step">
      <span class="wiki-step-num">1</span>
      <div>
        <strong>Entwicklermodus aktivieren</strong>
        <p>Gehe zu Discord <strong>Einstellungen</strong> → <strong>Erweitert</strong> (Advanced) → Aktiviere <strong>"Entwicklermodus"</strong> (Developer Mode).</p>
      </div>
    </div>

    <div class="wiki-step">
      <span class="wiki-step-num">2</span>
      <div>
        <strong>ID kopieren</strong>
        <p>Klicke mit der <strong>rechten Maustaste</strong> auf deinen Namen oder dein Profilbild → Wähle <strong>"ID kopieren"</strong> (Copy ID).</p>
      </div>
    </div>
  </div>

  <!-- SERVER ID ANLEITUNG -->
  <div class="wiki-section">
    <h2><i class="fas fa-server"></i> Server-IDs finden (Quelle & Ziel)</h2>
    <p>Für den Cloner brauchst du zwei Server-IDs: die ID des Servers, den du kopieren möchtest (Quelle), und die ID des Servers, in den kopiert werden soll (Ziel).</p>

    <h3>Quell-Server ID</h3>
    <div class="wiki-step">
      <span class="wiki-step-num">1</span>
      <div>
        <strong>Entwicklermodus aktivieren</strong>
        <p>Wie oben: Einstellungen → Erweitert → Entwicklermodus AN.</p>
      </div>
    </div>
    <div class="wiki-step">
      <span class="wiki-step-num">2</span>
      <div>
        <strong>Auf den Server klicken</strong>
        <p>Klicke mit der <strong>rechten Maustaste</strong> auf den Servernamen in der linken Serverliste → <strong>"Server-ID kopieren"</strong> (Copy Server ID).</p>
      </div>
    </div>

    <h3>Ziel-Server ID</h3>
    <p>Wiederhole die gleichen Schritte für den Zielserver. Stelle sicher, dass dein Account auf dem Zielserver die Berechtigung hat, Kanäle und Rollen zu erstellen!</p>

    <div class="danger-box">
      <i class="fas fa-exclamation-triangle"></i>
      <strong>Achtung:</strong> Der Ziel-Server wird <u>vollständig bereinigt</u>! Alle vorhandenen Kanäle, Rollen und Berechtigungen werden gelöscht, bevor der Klonvorgang startet. Lege vorher ein Backup an, falls du den ursprünglichen Zustand brauchst.
    </div>
  </div>

  <!-- FAQ -->
  <div class="wiki-section">
    <h2><i class="fas fa-question-circle"></i> FAQ & Troubleshooting</h2>

    <h3>Warum wird mein Token nicht akzeptiert?</h3>
    <p>Stelle sicher, dass du den kompletten Token kopiert hast (ohne Leerzeichen am Anfang/Ende). Tokens beginnen meist mit einem Großbuchstaben oder einer Zahl.</p>

    <h3>Der Cloner bleibt bei "Vorbereitung läuft..." hängen</h3>
    <p>Überprüfe, ob Token und Server-IDs korrekt sind. Öffne die Browser-Konsole (<code>F12</code> → Console), um Fehlermeldungen zu sehen.</p>

    <h3>Es werden nicht alle Kanäle/Rollen kopiert</h3>
    <p>Discord hat API-Limits (Rate Limits). Der Cloner wartet zwischen den Anfragen, aber bei sehr großen Servern kann es trotzdem zu Timeouts kommen. Starte den Vorgang einfach erneut – bereits kopierte Elemente werden übersprungen.</p>

    <h3>Emojis werden nicht kopiert</h3>
    <p>Der Ziel-Server muss genügend freie Emoji-Slots haben (bei Nitro-Boost-Leveln gibt es mehr Slots). Der Cloner stoppt automatisch, wenn das Limit erreicht ist.</p>

    <h3>Ist das legal?</h3>
    <p>Das Klonen von Discord-Servern verstößt gegen die Discord-AGB (Nutzungsbedingungen), insbesondere gegen das Verbot von Automatisierung. Nutze den Cloner nur auf Servern, deren Eigentümer du bist oder von denen du die ausdrückliche Erlaubnis hast. Wir übernehmen keine Haftung für Missbrauch.</p>
  </div>

  <!-- RECHTLICHES -->
  <div class="wiki-section">
    <h2><i class="fas fa-gavel"></i> Rechtliche Hinweise</h2>

    <div class="danger-box">
      <i class="fas fa-exclamation-triangle"></i>
      <strong>Haftungsausschluss:</strong>
    </div>
    <ul>
      <li>Dieses Tool wird für Bildungszwecke bereitgestellt.</li>
      <li>Der Einsatz erfolgt auf eigenes Risiko.</li>
      <li>Discord kann Accounts sperren, die gegen die AGB verstoßen.</li>
      <li>Wir sind nicht mit Discord Inc. verbunden.</li>
      <li>Verwende den Cloner nicht für Spam, Belästigung oder andere illegale Aktivitäten.</li>
    </ul>
  </div>

</main>

<footer>
  <span>&copy; 2026 IT-Solutions Bittkau</span>
  <span>Alle Rechte vorbehalten.</span>
</footer>

</body>
</html>