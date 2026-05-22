import os
import glob
import re

css_additions = """
/* =========================
   FUNCTIONAL UI (Tools)
========================= */
.page-wrapper {
  background-color: var(--off-white);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.page-header {
  background-color: var(--blurple);
  padding-bottom: 20px;
}

.layout {
  display: flex;
  max-width: 1200px;
  margin: 40px auto;
  gap: 30px;
  padding: 0 20px;
  flex: 1;
  width: 100%;
}

.left-side {
  flex: 1;
}

.panel {
  width: 350px;
  background: var(--white);
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  padding: 20px;
  display: flex;
  flex-direction: column;
}

.form-area {
  background: var(--white);
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  padding: 30px;
}

.form-area h2 {
  font-size: 1.5rem;
  margin-bottom: 10px;
  color: var(--text-dark);
}

.field {
  margin-bottom: 20px;
}

.field label {
  display: block;
  font-size: 0.75rem;
  font-weight: 700;
  color: #4f5660;
  text-transform: uppercase;
  margin-bottom: 8px;
}

.field input {
  width: 100%;
  padding: 10px 12px;
  border-radius: 3px;
  border: 1px solid #e3e5e8;
  background-color: #e3e5e8;
  font-family: inherit;
  font-size: 1rem;
  color: var(--text-dark);
  transition: border-color 0.2s;
}

.field input:focus {
  outline: none;
  border-color: var(--blurple);
}

.input-row {
  display: flex;
  gap: 15px;
}
.input-row .field {
  flex: 1;
}

.main-btn {
  background-color: var(--blurple);
  color: var(--white);
  border: none;
  border-radius: 3px;
  padding: 12px 24px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  width: 100%;
  transition: background-color 0.2s;
}

.main-btn:hover {
  background-color: var(--blurple-hover);
}

.log-box {
  background-color: #2f3136;
  color: #dcddde;
  border-radius: 5px;
  padding: 15px;
  font-family: monospace;
  font-size: 0.85rem;
  flex: 1;
  overflow-y: auto;
  margin-top: 15px;
  min-height: 300px;
}

.log-line { margin-bottom: 5px; }
.log-line.info { color: #dcddde; }
.log-line.success, .log-line.done { color: var(--success); }
.log-line.error, .log-line.fail { color: var(--danger); }
.log-line.warning, .log-line.warn { color: var(--warning); }

.panel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e3e5e8;
  padding-bottom: 10px;
}

.live-badge {
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--success);
  display: flex;
  align-items: center;
  gap: 5px;
}
.live-badge .dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: var(--success);
}

.progress-wrap { margin-top: 20px; }
.progress-track {
  height: 8px;
  background: #e3e5e8;
  border-radius: 4px;
  overflow: hidden;
  margin-bottom: 8px;
}
.progress-fill {
  height: 100%;
  background: var(--blurple);
  width: 0%;
  transition: width 0.3s;
}
.progress-status {
  font-size: 0.85rem;
  color: #4f5660;
  display: flex;
  align-items: center;
  gap: 8px;
}

@media (max-width: 900px) {
  .layout { flex-direction: column; }
  .panel { width: 100%; }
}
"""

with open('style.css', 'a') as f:
    f.write(css_additions)

navbar_template = """<div class="page-header">
  <nav class="navbar" style="position: relative; padding: 20px 40px;">
    <a href="{root_path}" class="nav-brand">
      <i class="fab fa-discord" style="font-size: 1.8rem;"></i>
      Discord Cloner
    </a>
    <div class="nav-links">
      <a href="{root_path}clone/server/">Server Cloner</a>
      <a href="{root_path}clone/emoji/">Emoji Cloner</a>
      <a href="{root_path}lookup/user/">User Lookup</a>
      <a href="{root_path}lookup/token/">Token Check</a>
      <a href="{root_path}wiki/">Wiki</a>
    </div>
    <div class="nav-right">
      <a href="{root_path}clone/server/" class="btn-login">Starten</a>
    </div>
  </nav>
</div>"""

footer_template = """<footer class="footer">
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
            <li><a href="{root_path}clone/server/">Server Cloner</a></li>
            <li><a href="{root_path}clone/emoji/">Emoji Cloner</a></li>
            <li><a href="{root_path}lookup/user/">User Lookup</a></li>
            <li><a href="{root_path}lookup/token/">Token Check</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Ressourcen</h4>
          <ul>
            <li><a href="{root_path}wiki/">Wiki</a></li>
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
      <a href="{root_path}clone/server/" class="btn-footer">Starten</a>
    </div>
  </div>
</footer>"""

files_to_update = [
    'clone/server/index.php',
    'clone/emoji/index.php',
    'lookup/token/index.php',
    'lookup/user/index.php',
    'wiki/index.php'
]

for filepath in files_to_update:
    if not os.path.exists(filepath):
        continue
    
    with open(filepath, 'r') as f:
        content = f.read()

    # Determine root path
    depth = filepath.count('/')
    root_path = '../' * depth if depth > 0 else './'

    # Replace <header class="topbar">...</header>
    header_pattern = re.compile(r'<header class="topbar">.*?</header>', re.DOTALL)
    new_header = navbar_template.format(root_path=root_path)
    content = header_pattern.sub(new_header, content)

    # Replace <footer>...</footer>
    footer_pattern = re.compile(r'<footer>.*?</footer>', re.DOTALL)
    new_footer = footer_template.format(root_path=root_path)
    content = footer_pattern.sub(new_footer, content)

    # Add page-wrapper around the content if not there
    # We will just replace <body> with <body class="page-wrapper"> if not already
    content = content.replace('<body>', '<body class="page-wrapper">')

    with open(filepath, 'w') as f:
        f.write(content)

print("Updated subpages successfully.")
