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
  <title>Emoji Cloner – Discord Cloner</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../style.css">
  <style>
    /* small additions for the emoji-only page */
    .token-row {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    .token-row .field {
      flex: 1;
      margin-bottom: 0;
    }
    .save-token-toggle {
      display: flex;
      align-items: center;
      gap: 0.45rem;
      margin-top: 0.25rem;
      font-size: 0.78rem;
      color: var(--text-soft);
      cursor: pointer;
      user-select: none;
    }
    .save-token-toggle input[type="checkbox"] {
      accent-color: var(--primary);
      width: 16px;
      height: 16px;
      cursor: pointer;
    }
    .save-token-toggle:hover {
      color: #fff;
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
<body>

<!-- ===== TOKEN POPUP ===== -->
<div class="token-popup-overlay hidden" id="token-popup-overlay">
  <div class="token-popup">
    <h2><i class="fas fa-lock"></i> Discord Token eingeben</h2>
    <p>Um Emojis zwischen Servern kopieren zu können, wird dein Discord User Token benötigt.</p>

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

<header class="topbar">
  <div class="brand">
    <img src="../../logo.png" alt="IT-Solutions Bittkau" class="brand-logo">
    <div class="brand-text">
      <span class="brand-title">Discord Cloner</span>
      <span class="brand-sub">We are not afiliated with Discord</span>
    </div>
  </div>
  <nav class="top-nav">
    <a href="../../" class="nav-link">Start</a>
    <a href="../server/" class="nav-link">Server Cloner</a>
    <a href="../emoji/" class="nav-link active">Nur Emojis</a>
    <a href="../../lookup/user/" class="nav-link">User Lookup</a>
    <a href="../../wiki/" class="nav-link">Wiki</a>
  </nav>
  <div class="status-badge">
    <strong id="clone-count"><?php echo $count; ?></strong> Aktionen
  </div>
</header>

<main class="layout">

  <!-- LEFT SIDE -->
  <section class="left-side">

    <div class="form-area">
      <h2><i class="fas fa-smile" style="margin-right:0.4rem;"></i> Emoji-Kloner</h2>
      <p style="font-size:0.82rem;color:var(--text-soft);margin-bottom:1.4rem;line-height:1.6;">
        Kopiere nur die Emojis von einem Discord-Server auf einen anderen.
        Der Ziel-Server bleibt ansonsten unberührt.
      </p>
      <form id="emoji-form">
        <div class="field">
          <label>Discord Token</label>
          <input type="password" name="token" id="token" placeholder="Dein Discord User Token..." required>
        </div>

        <label class="save-token-toggle">
          <input type="checkbox" id="save-token" checked>
          <i class="fas fa-save"></i> Token im Browser speichern
        </label>
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
          <i class="fas fa-copy"></i>
          Emojis kopieren
        </button>

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
    <div id="log-box" class="log-box"></div>
  </aside>

</main>

<footer>
  <span>&copy; 2026 IT-Solutions Bittkau</span>
  <span>Alle Rechte vorbehalten.</span>
</footer>

<script src="../script.js"></script>
<script>
(function () {
  const STORAGE_KEY = 'discord_cloner_token';

  const logBox = document.getElementById('log-box');
  const form = document.getElementById('emoji-form');
  const btn = document.getElementById('start-btn');
  const progressWrap = document.getElementById('progress-wrap');
  const progressFill = document.getElementById('progress-fill');
  const progressText = document.getElementById('progress-text');
  const progressIcon = document.getElementById('progress-icon');

  const tokenInput = document.getElementById('token');
  const saveCheckbox = document.getElementById('save-token');
  const tokenStatus = document.getElementById('token-status');

  // Popup elements
  const popupOverlay = document.getElementById('token-popup-overlay');
  const popupInput = document.getElementById('popup-token-input');
  const popupBtn = document.getElementById('popup-confirm-btn');

  // ---- TOKEN POPUP ----

  // Check if token exists in localStorage. If not, show the popup.
  const savedToken = localStorage.getItem(STORAGE_KEY);
  if (!savedToken) {
    // Show the popup on first visit (no token stored yet)
    popupOverlay.classList.remove('hidden');

    // Focus the input
    setTimeout(() => popupInput.focus(), 350);
  } else {
    // Token exists – pre-fill the form fields
    tokenInput.value = savedToken;
    saveCheckbox.checked = true;
    tokenStatus.classList.add('visible');
  }

  // Handle popup confirm button
  popupBtn.addEventListener('click', function () {
    const token = popupInput.value.trim();
    if (!token) {
      popupInput.style.borderColor = 'var(--danger)';
      popupInput.placeholder = 'Bitte Token eingeben!';
      return;
    }
    popupInput.style.borderColor = '';
    popupInput.placeholder = 'Token hier eingefügen...';

    // Store in localStorage
    localStorage.setItem(STORAGE_KEY, token);

    // Pre-fill the main form
    tokenInput.value = token;
    saveCheckbox.checked = true;
    tokenStatus.classList.add('visible');

    // Close the popup
    popupOverlay.classList.add('hidden');
  });

  // Allow Enter key to confirm
  popupInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      popupBtn.click();
    }
  });

  // ---- localStorage token handling (for the main form) ----

  // Save token when checkbox is toggled
  saveCheckbox.addEventListener('change', function () {
    if (this.checked) {
      const token = tokenInput.value.trim();
      if (token) {
        localStorage.setItem(STORAGE_KEY, token);
        tokenStatus.querySelector('span').textContent = 'Token gespeichert';
        tokenStatus.classList.add('visible');
        setTimeout(() => tokenStatus.classList.remove('visible'), 2500);
      } else {
        this.checked = false;
      }
    } else {
      localStorage.removeItem(STORAGE_KEY);
      tokenStatus.classList.remove('visible');
    }
  });

  // Also save on token input change if checkbox is checked
  tokenInput.addEventListener('input', function () {
    if (saveCheckbox.checked && this.value.trim()) {
      localStorage.setItem(STORAGE_KEY, this.value.trim());
    }
  });

  // ---- Log / Progress helpers ----

  function log(msg, type = 'info') {
    const icons = { info: 'fa-info-circle', done: 'fa-check-circle', fail: 'fa-times-circle', warn: 'fa-exclamation-circle' };
    const el = document.createElement('div');
    el.className = 'log-line ' + type;
    el.innerHTML = '<i class="fas ' + (icons[type] || 'fa-info-circle') + '"></i> ' + msg;
    logBox.appendChild(el);
    logBox.scrollTop = logBox.scrollHeight;
  }

  function setProgress(pct, text) {
    progressFill.style.width = Math.min(pct, 100) + '%';
    if (text) progressText.textContent = text;
  }

  function wait(ms) { return new Promise(r => setTimeout(r, ms)); }

  // ---- Emoji-only Cloner ----

  class EmojiCloner {
    constructor(token) {
      this.token = token;
      this.base = 'https://discord.com/api/v9';
      this.running = false;
    }

    async get(ep) {
      try {
        const r = await fetch(this.base + ep, {
          headers: { 'Authorization': this.token, 'Content-Type': 'application/json' }
        });
        if (r.status === 200) return await r.json();
        log('Fehler ' + ep + ' (' + r.status + ')', 'fail');
        return null;
      } catch (e) {
        log('Netzwerkfehler: ' + e.message, 'fail');
        return null;
      }
    }

    async post(ep, data) {
      try {
        const r = await fetch(this.base + ep, {
          method: 'POST',
          headers: { 'Authorization': this.token, 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });
        if (r.status === 200 || r.status === 201) return await r.json();
        return null;
      } catch (e) {
        log('Netzwerkfehler: ' + e.message, 'fail');
        return null;
      }
    }

    async postRaw(ep, data) {
      try {
        const r = await fetch(this.base + ep, {
          method: 'POST',
          headers: { 'Authorization': this.token, 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });
        const body = (r.status === 200 || r.status === 201) ? await r.json() : null;
        return { data: body, status: r.status };
      } catch (e) {
        log('Netzwerkfehler: ' + e.message, 'fail');
        return { data: null, status: 0 };
      }
    }

    async incrementCounter() {
      try {
        const r = await fetch('../../counter.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'action=emojis'
        });
        if (r.ok) {
          const data = await r.json();
          const el = document.getElementById('clone-count');
          if (el) el.textContent = data.total;
        }
      } catch (e) { /* ignore */ }
    }

    async start(src, dst) {
      this.running = true;
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Kopiere Emojis...';
      progressWrap.style.display = 'block';
      logBox.innerHTML = '';

      // Verify source guild is reachable
      setProgress(5, 'Verbinde mit Quell-Server...');
      const srcGuild = await this.get('/guilds/' + src);
      if (!srcGuild) {
        log('Quell-Server nicht erreichbar. Token oder ID prüfen.', 'fail');
        this.stop();
        return;
      }
      log('Quell-Server gefunden: "' + srcGuild.name + '"', 'info');

      // Verify dest guild is reachable
      setProgress(10, 'Verbinde mit Ziel-Server...');
      const dstGuild = await this.get('/guilds/' + dst);
      if (!dstGuild) {
        log('Ziel-Server nicht erreichbar. ID prüfen.', 'fail');
        this.stop();
        return;
      }
      log('Ziel-Server gefunden: "' + dstGuild.name + '"', 'info');

      // Count existing emojis on dest
      setProgress(15, 'Prüfe Ziel-Server Emojis...');
      const dstEmojis = await this.get('/guilds/' + dst + '/emojis');
      const dstEmojiCount = dstEmojis ? dstEmojis.length : 0;
      log('Ziel-Server hat bereits ' + dstEmojiCount + ' Emoji(s)', 'info');

      // Fetch source emojis
      setProgress(20, 'Lade Quell-Emojis...');
      const srcEmojis = await this.get('/guilds/' + src + '/emojis');
      if (!srcEmojis || srcEmojis.length === 0) {
        log('Keine Emojis auf dem Quell-Server gefunden.', 'warn');
        this.stop();
        return;
      }
      log(srcEmojis.length + ' Emojis auf Quell-Server gefunden.', 'info');

      const maxSlots = 50 - dstEmojiCount;
      const slotsLeft = Math.max(0, maxSlots);
      if (slotsLeft <= 0) {
        log('Ziel-Server hat bereits das Emoji-Limit erreicht (50).', 'fail');
        this.stop();
        return;
      }
      if (srcEmojis.length > slotsLeft) {
        log('Warnung: Nur ' + slotsLeft + ' von ' + srcEmojis.length + ' Emojis passen (Limit 50).', 'warn');
      }

      // Copy emojis
      log('Beginne Kopiervorgang...', 'info');
      let cloned = 0, failed = 0, skipped = 0;

      for (let i = 0; i < srcEmojis.length; i++) {
        if (!this.running) break;

        const emoji = srcEmojis[i];
        const pct = 20 + Math.round(((i + 1) / srcEmojis.length) * 70);
        setProgress(pct, 'Kopiere Emoji ' + (i + 1) + '/' + srcEmojis.length + ' …');

        try {
          const ext = emoji.animated ? 'gif' : 'png';
          const url = 'https://cdn.discordapp.com/emojis/' + emoji.id + '.' + ext + '?size=128';

          const resp = await fetch(url);
          if (!resp.ok) {
            log('Übersprungen: ' + emoji.name + ' (Download fehlgeschlagen)', 'warn');
            skipped++;
            continue;
          }

          const blob = await resp.blob();
          const base64 = await new Promise(resolve => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.readAsDataURL(blob);
          });

          const result = await this.postRaw('/guilds/' + dst + '/emojis', {
            name: emoji.name,
            image: base64
          });

          if (result.data) {
            log('Emoji kopiert: ' + emoji.name, 'done');
            cloned++;
          } else if (result.status === 400 || result.status === 403) {
            const remaining = srcEmojis.length - cloned - failed - skipped - 1;
            log('Emoji-Limit erreicht! ' + remaining + ' übersprungen.', 'warn');
            break;
          } else {
            log('Fehler bei: ' + emoji.name, 'fail');
            failed++;
          }
        } catch (e) {
          log('Fehler bei ' + emoji.name + ': ' + e.message, 'warn');
          skipped++;
        }

        await wait(400);
      }

      // Summary
      if (cloned > 0) {
        await this.incrementCounter();
      }

      setProgress(100, cloned + ' Emojis kopiert!');
      log('===== Zusammenfassung =====', 'info');
      log('Erfolgreich kopiert: ' + cloned, cloned > 0 ? 'done' : 'info');
      if (failed > 0) log('Fehlgeschlagen: ' + failed, 'fail');
      if (skipped > 0) log('Übersprungen: ' + skipped, 'warn');

      progressIcon.className = 'fas fa-check-circle';
      progressIcon.style.color = '#55efc4';

      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-check"></i> Abgeschlossen';
      this.running = false;
    }

    stop() {
      this.running = false;
      log('Vorgang abgebrochen.', 'warn');
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-copy"></i> Emojis kopieren';
      progressWrap.style.display = 'none';
    }
  }

  // ---- Form submit ----

  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    const token = tokenInput.value.trim();
    const src = document.getElementById('source_id').value.trim();
    const dst = document.getElementById('dest_id').value.trim();

    if (!token || !src || !dst) {
      log('Bitte alle Felder ausfüllen!', 'fail');
      return;
    }

    // Save token if checkbox is checked
    if (saveCheckbox.checked) {
      localStorage.setItem(STORAGE_KEY, token);
    }

    const cloner = new EmojiCloner(token);
    await cloner.start(src, dst);
  });

})();
</script>
</body>
</html>