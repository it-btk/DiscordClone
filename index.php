<?php
// Read counter
$counterFile = __DIR__ . '/counter.json';
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
  <title>Discord Cloner</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    .index-wrapper {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem 1.5rem;
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .hero-section {
      text-align: center;
      margin-bottom: 3rem;
      max-width: 640px;
      animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .hero-section .badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.4rem 1.2rem;
      border-radius: 999px;
      background: linear-gradient(135deg, rgba(138, 99, 255, 0.1), rgba(67, 178, 255, 0.05));
      border: 1px solid rgba(138, 99, 255, 0.2);
      font-size: 0.75rem;
      font-weight: 700;
      color: var(--primary-2);
      margin-bottom: 1.5rem;
      box-shadow: 0 0 15px rgba(138, 99, 255, 0.1);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
    }
    .hero-section h1 {
      font-size: clamp(2.8rem, 6vw, 4.2rem);
      line-height: 1.1;
      font-weight: 800;
      letter-spacing: -0.04em;
      color: #fff;
      margin-bottom: 1.2rem;
    }
    .hero-section h1 span {
      background: linear-gradient(135deg, #fff 0%, var(--primary-2) 40%, var(--primary) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      filter: drop-shadow(0 2px 10px rgba(138, 99, 255, 0.2));
    }
    .hero-section p {
      font-size: 1.1rem;
      line-height: 1.8;
      color: var(--text-soft);
      max-width: 540px;
      margin: 0 auto;
    }

    .tool-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
      width: 100%;
      max-width: 950px;
    }
    .tool-card {
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 24px;
      padding: 2.2rem 1.8rem;
      text-decoration: none;
      color: var(--text);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      display: flex;
      flex-direction: column;
      gap: 1rem;
      position: relative;
      overflow: hidden;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
    .tool-card::before {
      content: "";
      position: absolute;
      inset: 0;
      border-radius: 24px;
      padding: 2px;
      background: linear-gradient(135deg, var(--primary), var(--primary-2), transparent 60%);
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      opacity: 0;
      transition: opacity 0.4s ease;
      z-index: 1;
      pointer-events: none;
    }
    .tool-card:hover {
      border-color: transparent;
      background: rgba(255, 255, 255, 0.05);
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3), 0 0 20px var(--primary-glow);
    }
    .tool-card:hover::before {
      opacity: 1;
    }
    .tool-card .icon-wrap {
      width: 50px;
      height: 50px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.6rem;
      background: linear-gradient(135deg, rgba(138, 99, 255, 0.15), rgba(67, 178, 255, 0.1));
      color: var(--primary);
      box-shadow: inset 0 0 0 1px rgba(138, 99, 255, 0.2);
      transition: transform 0.3s ease, color 0.3s ease;
    }
    .tool-card:hover .icon-wrap {
      transform: scale(1.1) rotate(5deg);
      color: #fff;
    }
    .tool-card h3 {
      font-size: 1.15rem;
      font-weight: 700;
      color: #fff;
      margin: 0;
      letter-spacing: -0.01em;
    }
    .tool-card p {
      font-size: 0.85rem;
      line-height: 1.7;
      color: var(--text-soft);
      margin: 0;
      flex: 1;
    }
    .tool-card .action-tag {
      font-size: 0.75rem;
      font-weight: 700;
      color: var(--primary-2);
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-top: auto;
      padding-top: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .tool-card .action-tag i {
      font-size: 0.65rem;
      transition: transform 0.2s ease;
    }
    .tool-card:hover .action-tag i {
      transform: translateX(6px);
    }

    .stats-row {
      display: flex;
      gap: 2.5rem;
      margin-top: 3rem;
      flex-wrap: wrap;
      justify-content: center;
    }
    .stat-item {
      text-align: center;
    }
    .stat-item .num {
      font-size: 1.6rem;
      font-weight: 800;
      color: #fff;
      letter-spacing: -0.04em;
    }
    .stat-item .label {
      font-size: 0.72rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--text-muted);
      margin-top: 0.15rem;
    }
    .stat-item .num.purple { color: var(--primary); }
    .stat-item .num.green  { color: var(--success); }
    .stat-item .num.blue   { color: var(--primary-2); }

    @media (max-width: 760px) {
      .tool-grid {
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
    <a href="../../" class="nav-link" active>Start</a>
    <a href="../../clone/server/" class="nav-link">Server Cloner</a>
    <a href="../../clone/emoji/" class="nav-link">Nur Emojis</a>
    <a href="../../lookup/user/" class="nav-link">User Lookup</a>
    <a href="../../lookup/token/" class="nav-link">Token Check</a>
    <a href="../../wiki/" class="nav-link">Wiki</a>
  </nav>
  <div class="status-badge">
    <strong id="clone-count"><?php echo $count; ?></strong> Aktionen
  </div>
</header>

<main class="index-wrapper">
  <div class="hero-section">
    <div class="badge">
      <i class="fas fa-shield-alt"></i>
      Lokal & Privat
    </div>
    <h1>Discord <span>Tools</span></h1>
    <p>
      Wähle aus, was du tun möchtest. Alle Daten bleiben <strong>ausschließlich lokal in deinem Browser</strong> – nichts wird an Server gesendet.
    </p>
  </div>

  <div class="tool-grid">
    <a href="clone/server/" class="tool-card">
      <div class="icon-wrap"><i class="fas fa-server"></i></div>
      <h3>Server Cloner</h3>
      <p>Klone einen gesamten Discord-Server inklusive Rollen, Kanäle, Emojis und Berechtigungen.</p>
      <span class="action-tag"><i class="fas fa-arrow-right"></i> Öffnen</span>
    </a>

    <a href="clone/emoji/" class="tool-card">
      <div class="icon-wrap"><i class="fas fa-smile"></i></div>
      <h3>Emoji Cloner</h3>
      <p>Kopiere ausschließlich die Emojis von einem Server auf einen anderen – der Rest bleibt unberührt.</p>
      <span class="action-tag"><i class="fas fa-arrow-right"></i> Öffnen</span>
    </a>

    <a href="lookup/user/" class="tool-card">
      <div class="icon-wrap"><i class="fas fa-search"></i></div>
      <h3>User Lookup</h3>
      <p>Finde heraus, wann ein Discord-Account erstellt wurde – ganz ohne Token, direkt aus der Snowflake-ID.</p>
      <span class="action-tag"><i class="fas fa-arrow-right"></i> Öffnen</span>
    </a>
  </div>

  <div class="stats-row" id="stats-row">
    <div class="stat-item">
      <div class="num purple" id="stat-total">0</div>
      <div class="label">Aktionen gesamt</div>
    </div>
    <div class="stat-item">
      <div class="num green" id="stat-servers">0</div>
      <div class="label">Server geklont</div>
    </div>
    <div class="stat-item">
      <div class="num blue" id="stat-emojis">0</div>
      <div class="label">Emoji-Kopien</div>
    </div>
    <div class="stat-item">
      <div class="num" id="stat-lookups">0</div>
      <div class="label">Lookups</div>
    </div>
  </div>
</main>

<footer>
  <span>&copy; 2026 IT-Solutions Bittkau</span>
  <span>Alle Rechte vorbehalten.</span>
</footer>

<script>
  fetch('counter.php').then(r => r.json()).then(d => {
    document.getElementById('clone-count').textContent = d.total || 0;
    document.getElementById('stat-total').textContent = d.total || 0;
    document.getElementById('stat-servers').textContent = d.servers || 0;
    document.getElementById('stat-emojis').textContent = d.emojis || 0;
    document.getElementById('stat-lookups').textContent = d.lookups || 0;
  }).catch(() => {});
</script>
</body>
</html>