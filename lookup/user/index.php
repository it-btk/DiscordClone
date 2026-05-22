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
  <title>User Lookup – Discord Cloner</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../style.css">
  <style>
    .lookup-area {
      max-width: 560px;
    }
    .lookup-area h2 {
      font-size: 1.25rem;
      font-weight: 700;
      margin-bottom: 1.4rem;
      color: var(--text);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .lookup-area p {
      font-size: 0.9rem;
      color: var(--text-muted);
      margin-bottom: 1.4rem;
      line-height: 1.6;
    }
    .lookup-input-row {
      display: flex;
      gap: 0.75rem;
      align-items: flex-end;
    }
    .lookup-input-row .field {
      flex: 1;
      margin-bottom: 0;
    }
    .lookup-btn {
      height: 52px;
      padding: 0 1.8rem;
      border: none;
      border-radius: var(--radius-md);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      font-size: 0.92rem;
      font-weight: 700;
      color: #fff;
      background: var(--blurple);
      flex-shrink: 0;
    }
    .lookup-btn:hover {
      background: var(--blurple-dark);
    }

    /* =========================
       USER RESULT CARD
    ========================= */
    .user-result {
      display: none;
      margin-top: 2rem;
      background: var(--bg-dark);
      border: 1px solid var(--border-light);
      border-radius: var(--radius-xl);
      padding: 1.8rem;
    }
    .user-result.visible {
      display: block;
      animation: fadeIn 0.3s ease;
    }
    .user-header {
      display: flex;
      align-items: center;
      gap: 1.2rem;
      margin-bottom: 1.5rem;
    }
    .user-avatar-wrap {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      overflow: hidden;
      flex-shrink: 0;
      background: var(--bg-darker);
    }
    .user-avatar-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .user-meta h3 {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 0.25rem;
    }
    .user-meta .user-id-display {
      font-size: 0.82rem;
      font-family: "Consolas", "Monaco", monospace;
      color: var(--text-soft);
      word-break: break-all;
    }
    .user-info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.2rem;
    }
    .user-info-item {
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
    }
    .user-info-item .label {
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--text-soft);
    }
    .user-info-item .value {
      font-size: 0.88rem;
      color: var(--text);
      font-weight: 600;
    }
    .user-info-item .value.mono {
      font-family: "Consolas", "Monaco", monospace;
      font-size: 0.82rem;
    }

    /* =========================
       ERROR CARD
    ========================= */
    .user-error {
      display: none;
      margin-top: 1.5rem;
      background: rgba(218, 55, 60, 0.08);
      border: 1px solid rgba(218, 55, 60, 0.15);
      border-radius: var(--radius-md);
      padding: 1.2rem 1.5rem;
      align-items: center;
      gap: 0.8rem;
      color: var(--danger);
      font-size: 0.88rem;
      font-weight: 600;
    }
    .user-error.visible {
      display: flex;
      animation: fadeIn 0.3s ease;
    }
    .user-error i {
      font-size: 1.1rem;
      flex-shrink: 0;
    }

    /* toast */
    .toast {
      position: fixed;
      bottom: 2rem;
      left: 50%;
      transform: translateX(-50%);
      background: var(--bg);
      border: 1px solid var(--border-light);
      color: var(--text);
      padding: 0.8rem 1.5rem;
      border-radius: var(--radius-md);
      font-size: 0.85rem;
      font-weight: 600;
      display: none;
      align-items: center;
      gap: 0.6rem;
      z-index: 999;
      box-shadow: var(--shadow);
      animation: slideUp 0.25s ease;
    }
    .toast.visible {
      display: flex;
    }

    @media (max-width: 640px) {
      .lookup-input-row {
        flex-direction: column;
      }
      .lookup-btn {
        width: 100%;
      }
      .user-info-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body class="page-wrapper">

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

    <div class="lookup-area">
      <h2><i class="fas fa-search"></i> User Lookup</h2>
      <p>
        Finde heraus, wann ein Discord-Account erstellt wurde - ganz ohne Token.
        Gib einfach eine Discord User ID (Snowflake) ein.
      </p>

      <div class="lookup-input-row">
        <div class="field" style="margin-bottom:0;">
          <label>Discord User ID</label>
          <input type="text" id="userIdInput" placeholder="z.B. 876254321876543210" autofocus>
        </div>
        <button class="lookup-btn" id="lookupBtn">
          <i class="fas fa-search"></i>
          Lookup
        </button>
      </div>

      <!-- Result Card -->
      <div class="user-result" id="userResult">
        <div class="user-header">
          <div class="user-avatar-wrap">
            <img id="userAvatar" src="" alt="Avatar">
          </div>
          <div class="user-meta">
            <h3>Discord User</h3>
            <div class="user-id-display" id="userIdDisplay"></div>
          </div>
        </div>

        <div class="user-info-grid">
          <div class="user-info-item">
            <span class="label">Erstellungsdatum</span>
            <span class="value" id="userCreated"></span>
          </div>
          <div class="user-info-item">
            <span class="label">Unix Timestamp</span>
            <span class="value mono" id="userTimestamp"></span>
          </div>
          <div class="user-info-item">
            <span class="label">Account-Alter</span>
            <span class="value" id="userAge"></span>
          </div>
        </div>
      </div>

      <!-- Error Card -->
      <div class="user-error" id="userError">
        <i class="fas fa-exclamation-circle"></i>
        <span id="errorText"></span>
      </div>
    </div>

  </section>

  <!-- RIGHT INFO PANEL -->
  <aside class="panel">
    <div class="panel-header">
      <h3>Info</h3>
    </div>
    <div id="log-box" class="log-box" style="max-height:none;gap:0.8rem;">
      <div class="log-line info" style="animation:none;padding:0.6rem 0.8rem;">
        <i class="fas fa-snowflake"></i>
        Jede Discord User ID ist ein sogenannter Snowflake
      </div>
      <div class="log-line info" style="animation:none;padding:0.6rem 0.8rem;">
        <i class="fas fa-clock"></i>
        Der Timestamp wird direkt aus der ID berechnet – kein API-Call nötig
      </div>
      <div class="log-line info" style="animation:none;padding:0.6rem 0.8rem;">
        <i class="fas fa-shield-alt"></i>
        Kein Token erforderlich, keine Daten verlassen deinen Browser
      </div>
      <div class="log-line info" style="animation:none;padding:0.6rem 0.8rem;">
        <i class="fas fa-image"></i>
        Das angezeigte Avatar-Bild ist der Standard-Discord-Avatar (farblich abhängig von der ID)
      </div>
    </div>
  </aside>

</main>

<!-- Toast -->
<div class="toast" id="toast">
  <i class="fas fa-exclamation-triangle"></i>
  <span id="toastText"></span>
</div>

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

<script>
'use strict';

/* ===== User Lookup by ID (No Token) ===== */
const currentToolLang = 'de';
const toolTranslations = {
  userLookup: {
    de: {
      errorEmpty: 'Bitte gib eine Discord User ID ein.',
      errorInvalidId: 'Ungültige ID. Eine Discord User ID besteht aus 17–20 Ziffern.',
      years: 'Jahre',
      days: 'Tage',
      today: 'Heute erstellt'
    }
  }
};

function showToast(msg, type) {
  const toast = document.getElementById('toast');
  const toastText = document.getElementById('toastText');
  toastText.textContent = msg;
  toast.style.display = 'flex';
  toast.style.borderColor = type === 'error' ? 'rgba(255,107,107,0.25)' : 'rgba(61,214,140,0.25)';
  toast.style.color = type === 'error' ? 'var(--danger)' : 'var(--success)';
  toast.style.background = type === 'error' ? 'rgba(255,107,107,0.12)' : 'rgba(61,214,140,0.12)';
  setTimeout(() => { toast.style.display = 'none'; }, 3000);
}

function getCreationDateUL(userId) {
  const timestamp = Number(BigInt(userId) >> 22n) + 1420070400000;
  return new Date(timestamp);
}

function getDefaultAvatarUrl(userId) {
  const index = Number(BigInt(userId) >> 22n) % 6;
  return 'https://cdn.discordapp.com/embed/avatars/' + index + '.png';
}

function lookupUser(userId) {
  const t = toolTranslations.userLookup[currentToolLang];
  const resultEl = document.getElementById('userResult');
  const errorEl = document.getElementById('userError');
  const adEl = document.getElementById('adContainer');

  resultEl.classList.remove('visible');
  resultEl.style.display = 'none';
  errorEl.classList.remove('visible');
  errorEl.style.display = 'none';

  // Validate: must be a snowflake (numeric, 17-20 digits)
  if (!/^\d{17,20}$/.test(userId)) {
    errorEl.classList.add('visible');
    errorEl.style.display = 'flex';
    document.getElementById('errorText').textContent = t.errorInvalidId;
    return;
  }

  try {
    const created = getCreationDateUL(userId);

    // Sanity check: date should be between Discord epoch (2015) and now
    const discordEpoch = new Date('2015-01-01');
    const now = new Date();
    if (created < discordEpoch || created > now) {
      errorEl.classList.add('visible');
      errorEl.style.display = 'flex';
      document.getElementById('errorText').textContent = t.errorInvalidId;
      return;
    }

    renderUserResult(userId, created);
    if (adEl) adEl.style.display = 'block';

  } catch (e) {
    errorEl.classList.add('visible');
    errorEl.style.display = 'flex';
    document.getElementById('errorText').textContent = t.errorInvalidId;
  }
}

function trackLookup() {
  fetch('../../counter.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'action=lookup'
  }).then(r => r.json()).then(d => {
    const el = document.getElementById('clone-count');
    if (el) el.textContent = d.total;
  }).catch(() => {});
}

function renderUserResult(userId, created) {
  // Track the lookup
  trackLookup();
  const t = toolTranslations.userLookup[currentToolLang];
  const resultEl = document.getElementById('userResult');

  // Avatar (default)
  document.getElementById('userAvatar').src = getDefaultAvatarUrl(userId);

  // ID
  document.getElementById('userIdDisplay').textContent = userId;

  // Creation date
  document.getElementById('userCreated').textContent = created.toLocaleDateString(
    currentToolLang === 'ar' ? 'ar-SA' : 'de-DE',
    { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }
  );

  // Timestamp (Unix)
  document.getElementById('userTimestamp').textContent = Math.floor(created.getTime() / 1000);

  // Time ago
  const diff = Date.now() - created.getTime();
  const days = Math.floor(diff / 86400000);
  const years = Math.floor(days / 365);
  const remainDays = days % 365;
  let ago = '';
  if (years > 0) ago += years + ' ' + t.years;
  if (remainDays > 0) ago += (ago ? ', ' : '') + remainDays + ' ' + t.days;
  if (!ago) ago = t.today;
  document.getElementById('userAge').textContent = ago;

  resultEl.classList.add('visible');
  resultEl.style.display = 'block';
}

/* Event Listeners */
document.addEventListener('DOMContentLoaded', function () {
  const lookupBtn = document.getElementById('lookupBtn');
  const userIdInput = document.getElementById('userIdInput');

  if (lookupBtn) {
    lookupBtn.addEventListener('click', function () {
      const t = toolTranslations.userLookup[currentToolLang];
      const userId = userIdInput.value.trim();
      if (!userId) { showToast(t.errorEmpty, 'error'); return; }
      lookupUser(userId);
    });
  }

  if (userIdInput) {
    userIdInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' && lookupBtn) {
        e.preventDefault();
        lookupBtn.click();
      }
    });
  }
});
</script>
</body>
</html>
