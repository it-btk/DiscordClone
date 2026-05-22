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
      font-size: 0.95rem;
      font-weight: 700;
      margin-bottom: 1.4rem;
      color: rgba(255,255,255,0.9);
    }
    .lookup-area p {
      font-size: 0.82rem;
      color: var(--text-soft);
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
      border-radius: 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      font-size: 0.92rem;
      font-weight: 700;
      color: #fff;
      background: linear-gradient(135deg, var(--primary), var(--primary-2));
      flex-shrink: 0;
      transition: transform 0.15s, opacity 0.15s;
    }
    .lookup-btn:active {
      transform: scale(0.97);
    }

    /* =========================
       USER RESULT CARD
    ========================= */
    .user-result {
      display: none;
      margin-top: 2rem;
      background: rgba(255,255,255,0.02);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 20px;
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
      background: rgba(255,255,255,0.04);
    }
    .user-avatar-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .user-meta h3 {
      font-size: 1.1rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 0.25rem;
    }
    .user-meta .user-id-display {
      font-size: 0.82rem;
      font-family: "JetBrains Mono", "SF Mono", monospace;
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
      color: rgba(255,255,255,0.35);
    }
    .user-info-item .value {
      font-size: 0.88rem;
      color: rgba(255,255,255,0.85);
      font-weight: 600;
    }
    .user-info-item .value.mono {
      font-family: "JetBrains Mono", "SF Mono", monospace;
      font-size: 0.82rem;
    }

    /* =========================
       ERROR CARD
    ========================= */
    .user-error {
      display: none;
      margin-top: 1.5rem;
      background: rgba(255,107,107,0.06);
      border: 1px solid rgba(255,107,107,0.15);
      border-radius: 14px;
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
      background: rgba(255,107,107,0.12);
      border: 1px solid rgba(255,107,107,0.25);
      color: var(--danger);
      padding: 0.8rem 1.5rem;
      border-radius: 12px;
      font-size: 0.85rem;
      font-weight: 600;
      display: none;
      align-items: center;
      gap: 0.6rem;
      z-index: 999;
      backdrop-filter: blur(8px);
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
<body>

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
    <a href="../../clone/server/" class="nav-link">Server Cloner</a>
    <a href="../../clone/emoji/" class="nav-link">Nur Emojis</a>
    <a href="../../lookup/user/" class="nav-link active">User Lookup</a>
    <a href="../../lookup/token/" class="nav-link">Token Check</a>
    <a href="../../wiki/" class="nav-link">Wiki</a>
  </nav>
  <div class="status-badge">
    <strong id="clone-count"><?php echo $count; ?></strong> Aktionen
  </div>
</header>

<main class="layout">

  <!-- LEFT SIDE -->
  <section class="left-side">

    <div class="lookup-area">
      <h2><i class="fas fa-search" style="margin-right:0.4rem;"></i> User Lookup</h2>
      <p>
        Finde heraus, wann ein Discord-Account erstellt wurde – ganz ohne Token.
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

<footer>
  <span>&copy; 2026 IT-Solutions Bittkau</span>
  <span>Alle Rechte vorbehalten.</span>
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