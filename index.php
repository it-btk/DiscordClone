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
  <title>Discord Cloner | Stell dir vor...</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <a href="./" class="nav-brand">
      <i class="fab fa-discord" style="font-size: 1.8rem;"></i>
      Discord Cloner
    </a>
    <div class="nav-links">
      <a href="clone/server/">Server Cloner</a>
      <a href="clone/emoji/">Emoji Cloner</a>
      <a href="lookup/user/">User Lookup</a>
      <a href="lookup/token/">Token Check</a>
      <a href="wiki/">Wiki</a>
    </div>
    <div class="nav-right">
      <a href="clone/server/" class="btn-login">Starten</a>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Stell dir vor...</h1>
      <p>...du könntest einen gesamten Discord-Server mit all seinen Rollen, Kanälen und Berechtigungen mit nur einem Klick klonen. Oder gezielt Emojis übernehmen, ohne den Rest anzufassen. Willkommen beim Discord Cloner.</p>
      <div class="hero-buttons">
        <a href="clone/server/" class="btn btn-white">
          <i class="fas fa-server"></i> Server Klonen
        </a>
        <a href="#features" class="btn btn-dark">Alle Tools entdecken</a>
      </div>
    </div>
  </section>

  <!-- Feature 1: Server Cloner -->
  <section id="features" class="feature-section">
    <div class="feature-container">
      <div class="feature-image">
        <i class="fas fa-network-wired"></i>
      </div>
      <div class="feature-text">
        <h2>Ein exakter Klon.</h2>
        <p>Erstelle ein perfektes Abbild eines bestehenden Servers. Kanäle, Rollen, Kategorien und Berechtigungen werden nahtlos übernommen, sodass du direkt loslegen kannst, ohne alles komplett neu einzurichten.</p>
      </div>
    </div>
  </section>

  <!-- Feature 2: Emoji Cloner -->
  <section class="feature-section bg-offwhite">
    <div class="feature-container reverse">
      <div class="feature-image">
        <i class="far fa-laugh-squint"></i>
      </div>
      <div class="feature-text">
        <h2>Nur die besten Emojis.</h2>
        <p>Manchmal brauchst du nicht den ganzen Server, sondern nur diese eine geniale Emoji-Sammlung. Mit dem Emoji Cloner transferierst du sie im Handumdrehen auf deinen eigenen Server.</p>
      </div>
    </div>
  </section>

  <!-- Feature 3: User Lookup -->
  <section class="feature-section">
    <div class="feature-container">
      <div class="feature-image">
        <i class="fas fa-user-secret"></i>
      </div>
      <div class="feature-text">
        <h2>Finde alles heraus.</h2>
        <p>Wann wurde dieser Account erstellt? Ist er vertrauenswürdig? Mit dem User Lookup Tool liest du das Erstelldatum und weitere Details direkt aus der Snowflake-ID aus – ganz ohne Token.</p>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats-section">
    <h2>Bereits von vielen genutzt</h2>
    <div class="stats-grid">
      <div class="stat-item">
        <h3 id="stat-total"><?php echo $count; ?></h3>
        <p>Aktionen gesamt</p>
      </div>
      <div class="stat-item">
        <h3 id="stat-servers">0</h3>
        <p>Geklonte Server</p>
      </div>
      <div class="stat-item">
        <h3 id="stat-emojis">0</h3>
        <p>Kopierte Emojis</p>
      </div>
    </div>
    <a href="clone/server/" class="btn btn-blurple" style="padding: 20px 40px; font-size: 1.25rem;">
      <i class="fas fa-rocket"></i> Jetzt kostenlos starten
    </a>
  </section>

  <!-- Footer -->
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
              <li><a href="clone/server/">Server Cloner</a></li>
              <li><a href="clone/emoji/">Emoji Cloner</a></li>
              <li><a href="lookup/user/">User Lookup</a></li>
              <li><a href="lookup/token/">Token Check</a></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>Ressourcen</h4>
            <ul>
              <li><a href="wiki/">Wiki</a></li>
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
        <a href="clone/server/" class="btn-footer">Starten</a>
      </div>
    </div>
  </footer>

  <script>
    fetch('counter.php').then(r => r.json()).then(d => {
      document.getElementById('stat-total').textContent = d.total || 0;
      document.getElementById('stat-servers').textContent = d.servers || 0;
      document.getElementById('stat-emojis').textContent = d.emojis || 0;
    }).catch(() => {});
  </script>
</body>
</html>