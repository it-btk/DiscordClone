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
  <title>Token Checker – Discord Cloner</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../style.css">
  <style>
    .token-area {
      max-width: 620px;
    }
    .token-area h2 {
      font-size: 0.95rem;
      font-weight: 700;
      margin-bottom: 1.4rem;
      color: rgba(255,255,255,0.9);
    }
    .token-area p {
      font-size: 0.82rem;
      color: var(--text-soft);
      margin-bottom: 1.4rem;
      line-height: 1.6;
    }
    .token-input-row {
      display: flex;
      gap: 0.75rem;
      align-items: flex-end;
    }
    .token-input-row .field {
      flex: 1;
      margin-bottom: 0;
      position: relative;
    }
    .token-input-row .field input {
      padding-right: 3rem;
    }
    .toggle-vis {
      position: absolute;
      right: 1rem;
      bottom: 14px;
      background: none;
      border: none;
      color: rgba(255,255,255,0.35);
      cursor: pointer;
      font-size: 0.9rem;
      transition: color 0.2s;
    }
    .toggle-vis:hover {
      color: rgba(255,255,255,0.7);
    }
    .check-btn {
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
    .check-btn:active {
      transform: scale(0.97);
    }

    /* Loading */
    .token-loading {
      display: none;
      margin-top: 1.5rem;
      padding: 1.5rem;
      align-items: center;
      justify-content: center;
      gap: 0.7rem;
      color: var(--text-soft);
      font-size: 0.9rem;
    }
    .token-loading i {
      font-size: 1.3rem;
      color: var(--primary);
    }

    /* Status card */
    .token-status-card {
      display: none;
      margin-top: 1.5rem;
    }
    .token-status-card.visible {
      display: block;
      animation: fadeIn 0.3s ease;
    }

    .token-status-badge {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      padding: 0.7rem 1.2rem;
      border-radius: 12px;
      font-size: 0.88rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
    }
    .token-status-badge i {
      font-size: 1rem;
    }
    .token-status-badge.valid {
      background: rgba(61,214,140,0.08);
      border: 1px solid rgba(61,214,140,0.15);
      color: var(--success);
    }
    .token-status-badge.invalid {
      background: rgba(255,107,107,0.08);
      border: 1px solid rgba(255,107,107,0.15);
      color: var(--danger);
    }

    /* Profile card */
    .token-profile {
      display: none;
    }
    .token-profile.visible {
      display: block;
    }
    .banner-wrap {
      width: 100%;
      height: 140px;
      border-radius: 16px;
      overflow: hidden;
      margin-bottom: -40px;
      position: relative;
      background: rgba(255,255,255,0.03);
    }
    .banner-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .token-avatar-section {
      display: flex;
      align-items: flex-end;
      gap: 1.2rem;
      margin-bottom: 1.5rem;
      position: relative;
      padding-left: 0.5rem;
    }
    .token-avatar-wrap {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      overflow: hidden;
      flex-shrink: 0;
      border: 3px solid var(--bg);
      position: relative;
    }
    .token-avatar-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .dl-btn-sm {
      position: absolute;
      bottom: 2px;
      right: 2px;
      width: 26px;
      height: 26px;
      border-radius: 50%;
      border: 2px solid var(--bg);
      background: var(--primary);
      color: #fff;
      font-size: 0.6rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.2s;
    }
    .token-avatar-wrap:hover .dl-btn-sm {
      opacity: 1;
    }
    .banner-wrap:hover .dl-btn-sm {
      opacity: 1;
    }
    .dl-btn-sm.banner {
      top: 8px;
      right: 8px;
      bottom: auto;
      opacity: 1;
      background: rgba(0,0,0,0.5);
      border-color: rgba(255,255,255,0.2);
    }
    .token-user-meta h3 {
      font-size: 1.2rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 0.15rem;
    }
    .token-user-meta .username-id {
      font-size: 0.8rem;
      font-family: "JetBrains Mono", "SF Mono", monospace;
      color: var(--text-soft);
    }
    .token-user-meta .user-bio {
      font-size: 0.82rem;
      color: var(--text-soft);
      margin-top: 0.5rem;
      line-height: 1.5;
      display: none;
    }

    /* Info grid */
    .token-info-grid {
      display: none;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-top: 1.2rem;
      padding: 1.5rem;
      background: rgba(255,255,255,0.02);
      border: 1px solid var(--border-color);
      border-radius: 16px;
    }
    .token-info-grid.visible {
      display: grid;
    }
    .token-info-item {
      display: flex;
      flex-direction: column;
      gap: 0.2rem;
    }
    .token-info-item .label {
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: rgba(255,255,255,0.35);
    }
    .token-info-item .value {
      font-size: 0.85rem;
      color: rgba(255,255,255,0.85);
      font-weight: 600;
      word-break: break-all;
    }
    .token-info-item .value.nitro-yes {
      color: var(--primary);
    }
    .token-info-item .value.mfa-on {
      color: var(--success);
    }
    .token-info-item .value.mfa-off {
      color: var(--text-soft);
    }
    .accent-swatch {
      display: inline-block;
      width: 14px;
      height: 14px;
      border-radius: 4px;
      vertical-align: middle;
      margin-right: 0.35rem;
    }
    .accent-row {
      display: none;
    }

    /* Error card */
    .token-error {
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
    .token-error.visible {
      display: flex;
      animation: fadeIn 0.3s ease;
    }
    .token-error i {
      font-size: 1.1rem;
      flex-shrink: 0;
    }

    /* Toast */
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
      .token-input-row {
        flex-direction: column;
      }
      .check-btn {
        width: 100%;
      }
      .token-info-grid {
        grid-template-columns: 1fr;
      }
      .token-avatar-section {
        flex-direction: column;
        align-items: flex-start;
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

  <section class="left-side">
    <div class="token-area">
      <h2><i class="fas fa-shield-alt" style="margin-right:0.4rem;"></i> Token Checker</h2>
      <p>
        Überprüfe jeden Discord Token und erhalte detaillierte Account-Informationen –
        inklusive Avatar, Banner, Badges, Nitro-Status und mehr.
      </p>

      <div class="privacy-notice" style="background:rgba(124,92,255,0.06);border-left:3px solid var(--primary);padding:0.8rem 1rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.8rem;color:rgba(255,255,255,0.7);display:flex;align-items:flex-start;gap:0.6rem;">
        <i class="fas fa-shield-alt" style="color:var(--success);font-size:0.95rem;margin-top:0.1rem;flex-shrink:0;"></i>
        <span><strong style="color:#fff;">Nur lokal.</strong> Der Token wird ausschließlich von deinem Browser an die Discord-API gesendet. Es erfolgt keine Speicherung oder Weiterleitung an Dritte.</span>
      </div>

      <div class="token-input-row">
        <div class="field" style="margin-bottom:0;">
          <label>Discord Token</label>
          <input type="password" id="tokenInput" placeholder="Token hier eingefügen..." autofocus>
          <button class="toggle-vis" id="toggleToken" title="Anzeigen/Verstecken"><i class="fas fa-eye"></i></button>
        </div>
        <button class="check-btn" id="checkBtn">
          <i class="fas fa-search"></i>
          Check
        </button>
      </div>

      <!-- Loading -->
      <div class="token-loading" id="tokenLoading">
        <i class="fas fa-circle-notch fa-spin"></i>
        <span>Token wird überprüft...</span>
      </div>

      <!-- Result card -->
      <div class="token-status-card" id="tokenResult">

        <!-- Status badge -->
        <div class="token-status-badge" id="tokenStatus">
          <i class="fas fa-check-circle"></i>
          <span id="tokenStatusText"></span>
        </div>

        <!-- Profile -->
        <div class="token-profile" id="tokenProfileSection">
          <!-- Banner -->
          <div class="banner-wrap" id="userBannerWrap" style="display:none;">
            <img id="userBannerImg" src="" alt="Banner">
            <button class="dl-btn-sm banner" id="dlBanner" title="Banner herunterladen"><i class="fas fa-download"></i></button>
          </div>

          <div class="token-avatar-section">
            <div class="token-avatar-wrap">
              <img id="userAvatar" src="" alt="Avatar">
              <button class="dl-btn-sm" id="dlAvatar" title="Avatar herunterladen"><i class="fas fa-download"></i></button>
            </div>
            <div class="token-user-meta">
              <h3 id="userName"></h3>
              <div class="username-id" id="userId"></div>
              <div class="user-bio" id="userBio"></div>
            </div>
          </div>
        </div>

        <!-- Info grid -->
        <div class="token-info-grid" id="tokenInfoSection">
          <div class="token-info-item">
            <span class="label">E-Mail</span>
            <span class="value" id="userEmail"></span>
          </div>
          <div class="token-info-item">
            <span class="label">Telefon</span>
            <span class="value" id="userPhone"></span>
          </div>
          <div class="token-info-item">
            <span class="label">Nitro</span>
            <span class="value" id="userNitro"></span>
          </div>
          <div class="token-info-item">
            <span class="label">2FA / MFA</span>
            <span class="value" id="userMfa"></span>
          </div>
          <div class="token-info-item">
            <span class="label">Erstellt am</span>
            <span class="value" id="userCreated"></span>
          </div>
          <div class="token-info-item">
            <span class="label">Badges</span>
            <span class="value" id="userFlags"></span>
          </div>
          <div class="token-info-item">
            <span class="label">Freunde</span>
            <span class="value" id="userFriends"></span>
          </div>
          <div class="token-info-item">
            <span class="label">Server</span>
            <span class="value" id="userServers"></span>
          </div>
          <div class="token-info-item">
            <span class="label">Boosts</span>
            <span class="value" id="userBoosts"></span>
          </div>
          <div class="token-info-item">
            <span class="label">Locale</span>
            <span class="value" id="userLocale"></span>
          </div>
          <div class="token-info-item accent-row" id="accentColorRow">
            <span class="label">Akzentfarbe</span>
            <span class="value" id="userAccentColor"></span>
          </div>
        </div>
      </div>

      <!-- Error card -->
      <div class="token-error" id="tokenError">
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
    <div class="info-panel-content">
      <div class="log-line info" style="animation:none;padding:0.6rem 0.8rem;">
        <i class="fas fa-check-circle"></i>
        Validiert Token & zeigt Account-Details
      </div>
      <div class="log-line info" style="animation:none;padding:0.6rem 0.8rem;">
        <i class="fas fa-image"></i>
        Lädt Avatar & Banner (falls vorhanden)
      </div>
      <div class="log-line info" style="animation:none;padding:0.6rem 0.8rem;">
        <i class="fas fa-tag"></i>
        Zeigt alle Badges, Nitro-Typ & 2FA-Status
      </div>
      <div class="log-line info" style="animation:none;padding:0.6rem 0.8rem;">
        <i class="fas fa-shield-alt"></i>
        Keine Speicherung – rein clientseitig
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

/* ===== Discord Token Checker ===== */
const STORAGE_KEY = 'discord_cloner_token';

const DISCORD_API = 'https://discord.com/api/v10';

const USER_FLAGS = {
  0:  'Discord Staff',
  1:  'Partnered Server Owner',
  2:  'HypeSquad Events',
  3:  'Bug Hunter Level 1',
  6:  'HypeSquad Bravery',
  7:  'HypeSquad Brilliance',
  8:  'HypeSquad Balance',
  9:  'Early Supporter',
  14: 'Bug Hunter Level 2',
  17: 'Early Verified Bot Developer',
  22: 'Active Developer'
};

function showToast(msg, type) {
  const toast = document.getElementById('toast');
  document.getElementById('toastText').textContent = msg;
  toast.style.display = 'flex';
  toast.style.borderColor = type === 'error' ? 'rgba(255,107,107,0.25)' : 'rgba(61,214,140,0.25)';
  toast.style.color = type === 'error' ? 'var(--danger)' : 'var(--success)';
  toast.style.background = type === 'error' ? 'rgba(255,107,107,0.12)' : 'rgba(61,214,140,0.12)';
  setTimeout(() => { toast.style.display = 'none'; }, 3000);
}

function maskEmail(email) {
  if (!email) return null;
  const parts = email.split('@');
  if (parts.length < 2) return email;
  const visible = parts[0].length <= 2 ? parts[0][0] : parts[0].slice(0, 2);
  return visible + '***@' + parts.slice(1).join('@');
}

function maskPhone(phone) {
  if (!phone) return null;
  if (phone.length <= 4) return phone;
  return phone.slice(0, -4).replace(/./g, '*') + phone.slice(-4);
}

function getCreationDate(userId) {
  const timestamp = Number(BigInt(userId) >> 22n) + 1420070400000;
  return new Date(timestamp);
}

function getNitroType(premiumType) {
  switch (premiumType) {
    case 1: return 'Nitro Classic';
    case 2: return 'Nitro Full';
    case 3: return 'Nitro Basic';
    default: return 'Kein Nitro';
  }
}

function getUserBadges(flags) {
  if (!flags) return [];
  const badges = [];
  for (const [bit, name] of Object.entries(USER_FLAGS)) {
    if (flags & (1 << parseInt(bit))) badges.push(name);
  }
  return badges;
}

function getAvatarUrl(user, size) {
  if (user.avatar) {
    const ext = user.avatar.startsWith('a_') ? 'gif' : 'png';
    return 'https://cdn.discordapp.com/avatars/' + user.id + '/' + user.avatar + '.' + ext + '?size=' + (size || 512);
  }
  const defaultIdx = user.discriminator === '0'
    ? (Number(BigInt(user.id) >> 22n) % 6)
    : parseInt(user.discriminator) % 5;
  return 'https://cdn.discordapp.com/embed/avatars/' + defaultIdx + '.png';
}

function getBannerUrl(user, size) {
  if (!user.banner) return null;
  const ext = user.banner.startsWith('a_') ? 'gif' : 'png';
  return 'https://cdn.discordapp.com/banners/' + user.id + '/' + user.banner + '.' + ext + '?size=' + (size || 600);
}

function downloadImage(url, filename) {
  const a = document.createElement('a');
  a.href = url;
  a.download = filename;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
}

async function checkToken(token) {
  const resultEl = document.getElementById('tokenResult');
  const loadingEl = document.getElementById('tokenLoading');
  const errorEl = document.getElementById('tokenError');

  resultEl.classList.remove('visible');
  resultEl.style.display = 'none';
  errorEl.classList.remove('visible');
  errorEl.style.display = 'none';
  loadingEl.style.display = 'flex';

  try {
    const [userRes, friendsRes, guildsRes, boostRes] = await Promise.allSettled([
      fetch(DISCORD_API + '/users/@me', { headers: { 'Authorization': token } }),
      fetch(DISCORD_API + '/users/@me/relationships', { headers: { 'Authorization': token } }),
      fetch(DISCORD_API + '/users/@me/guilds', { headers: { 'Authorization': token } }),
      fetch(DISCORD_API + '/users/@me/guilds/premium/subscription-slots', { headers: { 'Authorization': token } })
    ]);

    loadingEl.style.display = 'none';

    if (userRes.status !== 'fulfilled' || !userRes.value.ok) {
      const status = userRes.status === 'fulfilled' ? userRes.value.status : 0;
      if (status === 401 || status === 403) {
        document.getElementById('tokenStatus').className = 'token-status-badge invalid';
        document.getElementById('tokenStatusText').textContent = 'Ungültiger oder deaktivierter Token';
        resultEl.classList.add('visible');
        resultEl.style.display = 'block';
        document.getElementById('tokenProfileSection').style.display = 'none';
        document.getElementById('tokenInfoSection').classList.remove('visible');
        return;
      }
      errorEl.classList.add('visible');
      errorEl.style.display = 'flex';
      document.getElementById('errorText').textContent = 'Netzwerkfehler – Discord-API nicht erreichbar.';
      return;
    }

    const userData = await userRes.value.json();

    let friendCount = 0;
    if (friendsRes.status === 'fulfilled' && friendsRes.value.ok) {
      const friends = await friendsRes.value.json();
      friendCount = Array.isArray(friends) ? friends.filter(function(f) { return f.type === 1; }).length : 0;
    }

    let guildCount = 0;
    if (guildsRes.status === 'fulfilled' && guildsRes.value.ok) {
      const guilds = await guildsRes.value.json();
      guildCount = Array.isArray(guilds) ? guilds.length : 0;
    }

    let boostSlots = 0;
    if (boostRes.status === 'fulfilled' && boostRes.value.ok) {
      const slots = await boostRes.value.json();
      boostSlots = Array.isArray(slots) ? slots.length : 0;
    }

    renderTokenResult(userData, friendCount, guildCount, boostSlots);

    // Increment counter
    fetch('../../counter.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'action=lookup'
    }).then(function(r) { return r.json(); }).then(function(d) {
      var el = document.getElementById('clone-count');
      if (el) el.textContent = d.total;
    }).catch(function() {});

  } catch (e) {
    loadingEl.style.display = 'none';
    errorEl.classList.add('visible');
    errorEl.style.display = 'flex';
    document.getElementById('errorText').textContent = 'Netzwerkfehler – Discord-API nicht erreichbar.';
  }
}

function renderTokenResult(data, friendCount, guildCount, boostSlots) {
  const resultEl = document.getElementById('tokenResult');
  resultEl.classList.add('visible');
  resultEl.style.display = 'block';

  // Status
  document.getElementById('tokenStatus').className = 'token-status-badge valid';
  document.getElementById('tokenStatusText').textContent = 'Token gültig ✓';
  document.getElementById('tokenProfileSection').style.display = 'block';
  document.getElementById('tokenInfoSection').classList.add('visible');

  // Banner
  var bannerWrap = document.getElementById('userBannerWrap');
  var bannerImg = document.getElementById('userBannerImg');
  var bannerDl = document.getElementById('dlBanner');
  var bannerUrl = getBannerUrl(data, 600);
  if (bannerUrl) {
    bannerImg.src = bannerUrl;
    bannerWrap.style.display = 'block';
    bannerDl.style.display = 'inline-flex';
    bannerDl.onclick = function() { downloadImage(getBannerUrl(data, 2048), data.username + '_banner'); };
  } else {
    bannerWrap.style.display = 'none';
    bannerDl.style.display = 'none';
  }

  // Avatar
  var avatarUrl = getAvatarUrl(data, 256);
  document.getElementById('userAvatar').src = avatarUrl;
  document.getElementById('dlAvatar').onclick = function() { downloadImage(getAvatarUrl(data, 2048), data.username + '_avatar'); };

  // Username
  document.getElementById('userName').textContent = data.global_name || data.username;
  document.getElementById('userId').textContent = '@' + data.username + ' • ID: ' + data.id;

  // Bio
  var bioEl = document.getElementById('userBio');
  if (data.bio && data.bio.trim()) {
    bioEl.textContent = data.bio;
    bioEl.style.display = 'block';
  } else {
    bioEl.style.display = 'none';
  }

  // Accent color
  var accentRow = document.getElementById('accentColorRow');
  var accentEl = document.getElementById('userAccentColor');
  if (data.accent_color) {
    var hex = '#' + data.accent_color.toString(16).padStart(6, '0');
    accentEl.innerHTML = '<span class="accent-swatch" style="background:' + hex + '"></span> ' + hex;
    accentRow.style.display = 'flex';
  } else {
    accentRow.style.display = 'none';
  }

  // Info grid
  document.getElementById('userEmail').textContent = maskEmail(data.email) || 'Keine E-Mail';
  document.getElementById('userPhone').textContent = maskPhone(data.phone) || 'Keine Telefonnummer';

  var nitroEl = document.getElementById('userNitro');
  nitroEl.textContent = getNitroType(data.premium_type);
  nitroEl.className = 'value' + (data.premium_type ? ' nitro-yes' : '');

  var mfaEl = document.getElementById('userMfa');
  mfaEl.textContent = data.mfa_enabled ? 'Aktiviert' : 'Deaktiviert';
  mfaEl.className = 'value ' + (data.mfa_enabled ? 'mfa-on' : 'mfa-off');

  var created = getCreationDate(data.id);
  document.getElementById('userCreated').textContent = created.toLocaleDateString('de-DE', {
    year: 'numeric', month: 'long', day: 'numeric'
  });

  var badges = getUserBadges(data.public_flags || data.flags);
  document.getElementById('userFlags').textContent = badges.length ? badges.join(', ') : 'Keine Badges';

  document.getElementById('userFriends').textContent = String(friendCount);
  document.getElementById('userServers').textContent = String(guildCount);
  document.getElementById('userBoosts').textContent = boostSlots > 0 ? boostSlots + ' Boost' + (boostSlots > 1 ? 's' : '') : 'Keine Boosts';
  document.getElementById('userLocale').textContent = data.locale || '-';
}

/* Event Listeners */
document.addEventListener('DOMContentLoaded', function () {
  var checkBtn = document.getElementById('checkBtn');
  var tokenInput = document.getElementById('tokenInput');
  var toggleBtn = document.getElementById('toggleToken');

  if (checkBtn) {
    checkBtn.addEventListener('click', function () {
      var token = tokenInput.value.trim();
      if (!token) { showToast('Bitte Token eingeben.', 'error'); return; }
      checkToken(token);
    });
  }

  if (tokenInput) {
    tokenInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' && checkBtn) {
        e.preventDefault();
        checkBtn.click();
      }
    });
  }

  if (toggleBtn && tokenInput) {
    toggleBtn.addEventListener('click', function () {
      var isPassword = tokenInput.type === 'password';
      tokenInput.type = isPassword ? 'text' : 'password';
      toggleBtn.querySelector('i').className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
    });
  }
});
</script>
</body>
</html>