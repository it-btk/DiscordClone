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
  <style>
    .wiki-wrapper {
      width: 100%;
      max-width: 860px;
      margin: 0 auto;
      padding: 2rem 1.5rem 3rem;
      flex: 1;
    }
    .wiki-header {
      text-align: center;
      margin-bottom: 2.5rem;
    }
    .wiki-header h1 {
      font-size: clamp(1.8rem, 3vw, 2.4rem);
      font-weight: 800;
      letter-spacing: -0.05em;
      color: #fff;
    }
    .wiki-header h1 i {
      color: var(--primary);
      margin-right: 0.5rem;
    }
    .wiki-header p {
      font-size: 0.9rem;
      color: var(--text-soft);
      max-width: 500px;
      margin: 0.6rem auto 0;
      line-height: 1.7;
    }

    .wiki-card {
      background: rgba(255,255,255,0.02);
      border: 1px solid var(--border-color);
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 1.5rem;
      transition: border-color 0.2s;
    }
    .wiki-card:hover {
      border-color: rgba(255,255,255,0.08);
    }
    .wiki-card h2 {
      font-size: 1.1rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }
    .wiki-card h2 i {
      color: var(--primary);
    }
    .wiki-card h3 {
      font-size: 0.9rem;
      font-weight: 700;
      color: rgba(255,255,255,0.85);
      margin: 1.2rem 0 0.6rem;
    }
    .wiki-card p {
      font-size: 0.85rem;
      line-height: 1.7;
      color: var(--text-soft);
      margin-bottom: 0.7rem;
    }
    .wiki-card ul, .wiki-card ol {
      padding-left: 1.4rem;
      margin-bottom: 0.7rem;
    }
    .wiki-card li {
      font-size: 0.85rem;
      line-height: 1.7;
      color: var(--text-soft);
      margin-bottom: 0.3rem;
    }
    .wiki-card code {
      background: rgba(124,92,255,0.12);
      color: #c8b8ff;
      padding: 0.15rem 0.5rem;
      border-radius: 6px;
      font-size: 0.8rem;
      font-family: "JetBrains Mono", "SF Mono", monospace;
    }

    .wiki-card .step-list {
      list-style: none;
      padding: 0;
    }
    .wiki-card .step-list li {
      display: flex;
      gap: 0.9rem;
      margin-bottom: 1rem;
      align-items: flex-start;
    }
    .wiki-card .step-num {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary), var(--primary-2));
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.72rem;
      font-weight: 700;
      flex-shrink: 0;
      margin-top: 0.1rem;
    }
    .wiki-card .step-list li > div {
      flex: 1;
    }
    .wiki-card .step-list li strong {
      display: block;
      color: rgba(255,255,255,0.85);
      font-size: 0.88rem;
      margin-bottom: 0.15rem;
    }

    .wiki-card .alert {
      display: flex;
      align-items: flex-start;
      gap: 0.7rem;
      padding: 0.8rem 1rem;
      border-radius: 12px;
      font-size: 0.82rem;
      line-height: 1.6;
      margin: 1rem 0;
    }
    .wiki-card .alert i {
      font-size: 0.9rem;
      margin-top: 0.1rem;
      flex-shrink: 0;
    }
    .wiki-card .alert.warning {
      background: rgba(255,184,77,0.06);
      border-left: 3px solid var(--warning);
      color: rgba(255,255,255,0.7);
    }
    .wiki-card .alert.warning i {
      color: var(--warning);
    }
    .wiki-card .alert.danger {
      background: rgba(255,107,107,0.06);
      border-left: 3px solid var(--danger);
      color: rgba(255,255,255,0.7);
    }
    .wiki-card .alert.danger i {
      color: var(--danger);
    }
    .wiki-card .alert.info {
      background: rgba(124,92,255,0.06);
      border-left: 3px solid var(--primary);
      color: rgba(255,255,255,0.7);
    }
    .wiki-card .alert.info i {
      color: var(--primary);
    }
    .wiki-card .alert strong {
      color: #fff;
    }

    .wiki-card .faq-item {
      padding: 0.8rem 0;
      border-bottom: 1px solid rgba(255,255,255,0.04);
    }
    .wiki-card .faq-item:last-child {
      border-bottom: none;
    }
    .wiki-card .faq-item h4 {
      font-size: 0.88rem;
      font-weight: 700;
      color: rgba(255,255,255,0.85);
      margin-bottom: 0.35rem;
    }

    @media (max-width: 640px) {
      .wiki-wrapper {
        padding: 1.5rem 1rem 2rem;
      }
      .wiki-card {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>

<header class="topbar">
  <div class="brand">
    <img src="../logo.png" alt="IT-Solutions Bittkau" class="brand-logo">
    <div class="brand-text">
      <span class="brand-title">Discord Cloner</span>
      <span class="brand-sub">We are not afiliated with Discord</span>
    </div>
  </div>
  <nav class="top-nav">
    <a href="../" class="nav-link">Start</a>
    <a href="clone/server/" class="nav-link">Server Cloner</a>
    <a href="clone/emoji/" class="nav-link">Nur Emojis</a>
    <a href="lookup/user/" class="nav-link">User Lookup</a>
    <a href="lookup/token/" class="nav-link">Token Check</a>
    <a href="wiki/" class="nav-link active">Wiki</a>
  </nav>
  <div class="status-badge">
    <strong><?php echo $count; ?></strong> Aktionen
  </div>
</header>

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

<footer>
  <span>&copy; 2026 IT-Solutions Bittkau</span>
  <span>Alle Rechte vorbehalten.</span>
</footer>

</body>
</html>