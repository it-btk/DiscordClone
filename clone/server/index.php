<?php
// Read counter
$counterFile = __DIR__ . '/../../counter.json';
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
  <title>Server Cloner – Discord Cloner</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../style.css">
  <style>
    /* =========================
       TOKEN POPUP / MODAL
    ========================= */
    .token-popup-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.75);
      backdrop-filter: blur(6px);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      animation: fadeIn 0.25s ease;
    }
    .token-popup-overlay.hidden {
      display: none;
    }
    .token-popup {
      background: linear-gradient(145deg, #0f1629 0%, #0a1020 100%);
      border: 1px solid rgba(124, 92, 255, 0.25);
      border-radius: 24px;
      padding: 2.2rem 2.5rem;
      max-width: 460px;
      width: 90%;
      box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6);
      animation: slideUp 0.3s ease;
    }
    .token-popup h2 {
      font-size: 1.3rem;
      font-weight: 800;
      color: #fff;
      margin-bottom: 0.5rem;
      letter-spacing: -0.04em;
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }
    .token-popup h2 i {
      color: var(--primary);
    }
    .token-popup p {
      font-size: 0.88rem;
      line-height: 1.7;
      color: var(--text-soft);
      margin-bottom: 0.75rem;
    }
    .token-popup .privacy-notice {
      background: rgba(124, 92, 255, 0.08);
      border-left: 3px solid var(--primary);
      padding: 0.8rem 1rem;
      border-radius: 10px;
      margin-bottom: 1.5rem;
      font-size: 0.8rem;
      color: rgba(255, 255, 255, 0.75);
      display: flex;
      align-items: flex-start;
      gap: 0.6rem;
    }
    .token-popup .privacy-notice i {
      color: var(--success);
      font-size: 0.95rem;
      margin-top: 0.1rem;
      flex-shrink: 0;
    }
    .token-popup .privacy-notice strong {
      color: #fff;
    }
    .token-popup .field {
      margin-bottom: 1.2rem;
    }
    .token-popup .field input {
      width: 100%;
      height: 52px;
      padding: 0 1rem;
      border-radius: 14px;
      border: 1px solid rgba(255, 255, 255, 0.08);
      background: rgba(255, 255, 255, 0.03);
      color: #fff;
      font-size: 0.9rem;
      outline: none;
      transition: 0.22s ease;
      font-family: "JetBrains Mono", "SF Mono", monospace;
    }
    .token-popup .field input::placeholder {
      color: rgba(255, 255, 255, 0.18);
    }
    .token-popup .field input:focus {
      border-color: rgba(124, 92, 255, 0.5);
      background: rgba(124, 92, 255, 0.06);
      box-shadow: 0 0 0 4px rgba(124, 92, 255, 0.08);
    }
    .token-popup .popup-actions {
      display: flex;
      justify-content: flex-end;
    }
    .token-popup .popup-btn {
      padding: 0.75rem 2rem;
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--primary), var(--primary-2));
      color: #fff;
      font-size: 0.9rem;
      font-weight: 700;
      cursor: pointer;
      transition: transform 0.15s ease, opacity 0.15s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .token-popup .popup-btn:hover {
      opacity: 0.9;
    }
    .token-popup .popup-btn:active {
      transform: scale(0.97);
    }
    .token-popup .popup-btn:disabled {
      opacity: 0.45;
      cursor: not-allowed;
      transform: none;
    }

    .token-status {
      font-size: 0.72rem;
      color: var(--success);
      margin-top: 0.35rem;
      display: none;
      align-items: center;
      gap: 0.35rem;
    }
    .token-status i {
      font-size: 0.65rem;
    }
    .token-status.visible {
      display: flex;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to   { opacity: 1; }
    }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px) scale(0.96); }
      to   { opacity: 1; transform: translateY(0) scale(1); }
    }
  </style>
</head>
<body class="page-wrapper">

<!-- ===== TOKEN POPUP ===== -->
<div class="token-popup-overlay hidden" id="token-popup-overlay">
  <div class="token-popup">
    <h2><i class="fas fa-server"></i> Discord Token eingeben</h2>
    <p>Um einen kompletten Server klonen zu können, wird dein Discord User Token benötigt.</p>

    <div class="privacy-notice">
      <i class="fas fa-shield-alt"></i>
      <span>
        <strong>Deine Privatsphäre ist sicher.</strong>
        Der Token wird <u>ausschließlich lokal in deinem Browser</u> gespeichert (localStorage).
        Es erfolgt <u>keine</u> Übertragung an Server, keine Speicherung auf Servern
        und keine Weiterleitung an Dritte.
      </span>
    </div>

    <div class="field">
      <label style="display:block;margin-bottom:0.5rem;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.35);">Dein Discord User Token</label>
      <input type="password" id="popup-token-input" placeholder="Token hier eingefügen..." autofocus>
    </div>

    <div class="popup-actions">
      <button class="popup-btn" id="popup-confirm-btn">
        <i class="fas fa-check"></i> Token speichern
      </button>
    </div>
  </div>
</div>

<div class="page-header">
  <nav class="navbar" style="position: relative; padding: 20px 40px;">
    <a href="../../" class="nav-brand">
      <i class="fab fa-discord" style="font-size: 1.8rem;"></i>
      Discord Cloner
    </a>
    <div class="nav-links">
      <a href="../../clone/server/">Server Cloner</a>
      <a href="../../clone/emoji/">Emoji Cloner</a>
      <a href="../../lookup/user/">User Lookup</a>
      <a href="../../lookup/token/">Token Check</a>
      <a href="../../wiki/">Wiki</a>
    </div>
    <div class="nav-right">
      <a href="../../clone/server/" class="btn-login">Starten</a>
    </div>
  </nav>
</div>

<main class="layout">

  <!-- LEFT SIDE -->
  <section class="left-side">

    <div class="form-area">
      <h2><i class="fas fa-server" style="margin-right:0.4rem;"></i> Server Cloner</h2>
      <p style="font-size:0.82rem;color:var(--text-soft);margin-bottom:1.4rem;line-height:1.6;">
        Klone einen kompletten Discord-Server inklusive Rollen, Kanäle, Emojis, Berechtigungen und Namen.
        Der Ziel-Server wird vorher vollständig bereinigt.
      </p>
      <form id="clone-form">
        <div class="field">
          <label>Discord Token</label>
          <input type="password" name="token" id="token" placeholder="Dein Discord User Token..." required>
        </div>

        <div class="token-status" id="token-status">
          <i class="fas fa-check-circle"></i>
          <span>Token aus lokalem Speicher geladen</span>
        </div>

        <div class="input-row" style="margin-top:0.8rem;">
          <div class="field">
            <label>Quell-Server ID</label>
            <input type="text" name="source_id" id="source_id" placeholder="Quell-ID eingeben" required>
          </div>
          <div class="field">
            <label>Ziel-Server ID</label>
            <input type="text" name="dest_id" id="dest_id" placeholder="Ziel-ID eingeben" required>
          </div>
        </div>

        <button type="submit" id="start-btn" class="main-btn">
          <i class="fas fa-play"></i>
          Klonen starten
        </button>

        <!-- PROGRESS -->
        <div id="progress-wrap" class="progress-wrap">
          <div class="progress-track">
            <div class="progress-fill" id="progress-fill"></div>
          </div>
          <div class="progress-status">
            <i class="fas fa-circle-notch fa-spin" id="progress-icon"></i>
            <span id="progress-text">Vorbereitung läuft...</span>
          </div>
        </div>
      </form>
    </div>

  </section>

  <!-- RIGHT LOG PANEL -->
  <aside class="panel">
    <div class="panel-header">
      <h3>Live-Protokoll</h3>
      <div class="live-badge">
        <span class="dot"></span>
        LIVE
      </div>
    </div>
    <div id="logs" class="log-box"></div>
  </aside>

</main>

<footer class="footer">
  <div class="footer-container">
    <div class="footer-top">
      <div class="footer-brand">
        <h2>Discord Cloner</h2>
        <p style="max-width: 250px; color: var(--blurple); margin-top: 10px;">Lokale, sichere und schnelle Tools für deinen Discord Alltag.</p>
      </div>
      <div class="footer-links">
        <div class="footer-col">
          <h4>Tools</h4>
          <ul>
            <li><a href="../../clone/server/">Server Cloner</a></li>
            <li><a href="../../clone/emoji/">Emoji Cloner</a></li>
            <li><a href="../../lookup/user/">User Lookup</a></li>
            <li><a href="../../lookup/token/">Token Check</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Ressourcen</h4>
          <ul>
            <li><a href="../../wiki/">Wiki</a></li>
            <li><a href="https://github.com/it-btk/DiscordClone">GitHub</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-bottom-brand">
        <i class="fab fa-discord" style="font-size: 1.5rem;"></i>
        Discord Cloner
      </div>
      <a href="../../clone/server/" class="btn-footer">Starten</a>
    </div>
  </div>
</footer>

<script src="../../script.js"></script>
<script>
(function () {
  const STORAGE_KEY = 'discord_cloner_token';

  const logBox = document.getElementById('log-box');
  const form = document.getElementById('clone-form');
  const btn = document.getElementById('start-btn');
  const progressWrap = document.getElementById('progress-wrap');
  const progressFill = document.getElementById('progress-fill');
  const progressText = document.getElementById('progress-text');
  const progressIcon = document.getElementById('progress-icon');
  const cloneCountEl = document.getElementById('clone-count');

  const tokenInput = document.getElementById('token');
  const tokenStatus = document.getElementById('token-status');

  // Popup elements
  const popupOverlay = document.getElementById('token-popup-overlay');
  const popupInput = document.getElementById('popup-token-input');
  const popupBtn = document.getElementById('popup-confirm-btn');

  // ---- TOKEN POPUP ----

  const savedToken = localStorage.getItem(STORAGE_KEY);
  if (!savedToken) {
    popupOverlay.classList.remove('hidden');
    setTimeout(() => popupInput.focus(), 350);
  } else {
    tokenInput.value = savedToken;
    tokenStatus.classList.add('visible');
  }

  popupBtn.addEventListener('click', function () {
    const token = popupInput.value.trim();
    if (!token) {
      popupInput.style.borderColor = 'var(--danger)';
      popupInput.placeholder = 'Bitte Token eingeben!';
      return;
    }
    popupInput.style.borderColor = '';
    popupInput.placeholder = 'Token hier eingefügen...';

    localStorage.setItem(STORAGE_KEY, token);

    tokenInput.value = token;
    tokenStatus.classList.add('visible');

    popupOverlay.classList.add('hidden');
  });

  popupInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      popupBtn.click();
    }
  });

  // ---- localStorage token handling ----
  tokenInput.addEventListener('input', function () {
    if (this.value.trim()) {
      localStorage.setItem(STORAGE_KEY, this.value.trim());
    } else {
      localStorage.removeItem(STORAGE_KEY);
      tokenStatus.classList.remove('visible');
    }
  });

  // ---- Form submit (uses DiscordCloner from script.js) ----

  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    const token = tokenInput.value.trim();
    const src = document.getElementById('source_id').value.trim();
    const dst = document.getElementById('dest_id').value.trim();

    if (!token || !src || !dst) {
      log('Bitte alle Felder ausfüllen!', 'fail');
      return;
    }

    localStorage.setItem(STORAGE_KEY, token);

    if (typeof DiscordCloner !== 'undefined') {
      const cloner = new DiscordCloner(token);
      await cloner.startCloning(src, dst);
    } else {
      log('Fehler: DiscordCloner-Klasse nicht gefunden.', 'fail');
    }
  });

})();
</script>
</body>
</html>