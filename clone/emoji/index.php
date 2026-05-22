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
  <link rel="stylesheet" href="../../style.css">
  
</head>
<body class="page-wrapper">

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
      <label style="display:block;margin-bottom:0.5rem;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-soft);">Dein Discord User Token</label>
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
      <h2><i class="fas fa-smile"></i> Emoji-Kloner</h2>
      <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:1.4rem;line-height:1.6;">
        Kopiere nur die Emojis von einem Discord-Server auf einen anderen.
        Der Ziel-Server bleibt ansonsten unberuehrt.
      </p>
      <form id="emoji-form">
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
    <div class="footer-bottom-wrapper">
      <div class="footer-bottom">
        <a href="../../" class="footer-bottom-brand">
          <i class="fab fa-discord" style="font-size: 1.8rem;"></i>
          Discord Cloner
        </a>
        <a href="../../clone/server/" class="btn-footer">Starten</a>
      </div>
    </div>
  </div>
</footer>

<script src="../../script.js"></script>
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
  const tokenStatus = document.getElementById('token-status');

  // Popup elements
  const popupOverlay = document.getElementById('token-popup-overlay');
  const popupInput = document.getElementById('popup-token-input');
  const popupBtn = document.getElementById('popup-confirm-btn');

  // ---- TOKEN POPUP ----

  // Check if token exists in localStorage. If not, show the popup.
  const savedToken = localStorage.getItem(STORAGE_KEY);
  if (!savedToken) {
    popupOverlay.classList.remove('hidden');
    setTimeout(() => popupInput.focus(), 350);
  } else {
    tokenInput.value = savedToken;
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

    localStorage.setItem(STORAGE_KEY, token);
    tokenInput.value = token;
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

  // ---- localStorage token handling ----
  tokenInput.addEventListener('input', function () {
    if (this.value.trim()) {
      localStorage.setItem(STORAGE_KEY, this.value.trim());
    } else {
      localStorage.removeItem(STORAGE_KEY);
      tokenStatus.classList.remove('visible');
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

    // Save token before starting
    localStorage.setItem(STORAGE_KEY, token);

    const cloner = new EmojiCloner(token);
    await cloner.start(src, dst);
  });

})();
</script>
</body>
</html>
