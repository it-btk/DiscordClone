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
    /* =========================
       INDEX PAGE – OVERRIDE LAYOUT
    ========================= */
    .index-layout {
      width: 100%;
      max-width: 780px;
      margin: 0 auto;
      padding: 3rem 1.5rem;
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .index-hero {
      text-align: center;
      margin-bottom: 2.5rem;
      max-width: 540px;
    }
    .index-hero h1 {
      font-size: clamp(2rem, 4vw, 3.2rem);
      line-height: 1.1;
      margin-bottom: 0.8rem;
      color: #fff;
      font-weight: 800;
      letter-spacing: -0.07em;
    }
    .index-hero p {
      font-size: 0.95rem;
      line-height: 1.7;
      color: var(--text-soft);
    }
    .index-hero p strong {
      color: var(--text);
    }

    /* =========================
       CARDS
    ========================= */
    .cards {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
      width: 100%;
    }
    .card {
      background: rgba(255,255,255,0.02);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 20px;
      padding: 2rem;
      text-decoration: none;
      color: var(--text);
      transition: border-color 0.2s, transform 0.15s, background 0.2s;
      display: flex;
      flex-direction: column;
      gap: 0.8rem;
    }
    .card:hover {
      border-color: rgba(124,92,255,0.3);
      background: rgba(124,92,255,0.04);
      transform: translateY(-2px);
    }
    .card-icon {
      font-size: 2rem;
      color: var(--primary);
      width: 48px;
      height: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 14px;
      background: rgba(124,92,255,0.1);
    }
    .card h3 {
      font-size: 1.1rem;
      font-weight: 700;
      color: #fff;
      margin: 0;
    }
    .card p {
      font-size: 0.84rem;
      line-height: 1.6;
      color: var(--text-soft);
      margin: 0;
    }
    .card .tag {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--primary-2);
      margin-top: auto;
      padding-top: 0.5rem;
    }
    .card .tag i {
      font-size: 0.65rem;
    }

    @media (max-width: 640px) {
      .cards {
        grid-template-columns: 1fr;
      }
      .index-layout {
        padding: 2rem 1rem;
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
    <a href="index.php" class="nav-link" style="background:rgba(124,92,255,0.15);color:#fff;">Start</a>
    <a href="server.php" class="nav-link">Server Cloner</a>
    <a href="emojis.php" class="nav-link">Nur Emojis</a>
    <a href="lookup.php" class="nav-link">User Lookup</a>
    <a href="wiki.php" class="nav-link">Wiki / Anleitung</a>
  </nav>
  <div class="status-badge">
    <span>Bereits <strong id="clone-count"><?php echo $count; ?></strong> Server geklont</span>
  </div>
</header>

<main class="index-layout">

  <div class="index-hero">
    <h1>Discord Cloner</h1>
    <p>
      Wähle aus, was du kopieren möchtest. Dein Token wird <strong>nur lokal in deinem Browser</strong> gespeichert.
    </p>
  </div>

  <div class="cards">
    <a href="server.php" class="card">
      <div class="card-icon"><i class="fas fa-server"></i></div>
      <h3>Kompletten Server klonen</h3>
      <p>Klone einen gesamten Discord-Server inklusive Rollen, Kanäle, Emojis, Berechtigungen und Servernamen.</p>
      <span class="tag"><i class="fas fa-arrow-right"></i> Server Cloner öffnen</span>
    </a>

    <a href="emojis.php" class="card">
      <div class="card-icon"><i class="fas fa-smile"></i></div>
      <h3>Nur Emojis kopieren</h3>
      <p>Kopiere ausschließlich die Emojis von einem Server auf einen anderen – der Rest bleibt unberührt.</p>
      <span class="tag"><i class="fas fa-arrow-right"></i> Emoji Cloner öffnen</span>
    </a>
  </div>

</main>

<footer>
  <span>&copy; 2026 IT-Solutions Bittkau</span>
  <span>Alle Rechte vorbehalten.</span>
</footer>

<script>
  // Update clone count via counter.php
  fetch('counter.php').then(r => r.json()).then(d => {
    const el = document.getElementById('clone-count');
    if (el) el.textContent = d.total;
  }).catch(() => {});
</script>
</body>
</html>