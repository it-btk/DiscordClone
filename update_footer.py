import os
import re

footer_template = """<footer class="footer">
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
    <div class="footer-bottom-wrapper">
      <div class="footer-bottom">
        <a href="{root_path}" class="footer-bottom-brand">
          <i class="fab fa-discord" style="font-size: 1.8rem;"></i>
          Discord Cloner
        </a>
        <a href="{root_path}clone/server/" class="btn-footer">Starten</a>
      </div>
    </div>
  </div>
</footer>"""

files_to_update = [
    'index.php',
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

    depth = filepath.count('/')
    root_path = '../' * depth if depth > 0 else './'

    footer_pattern = re.compile(r'<footer class="footer">.*?</footer>', re.DOTALL)
    new_footer = footer_template.format(root_path=root_path)
    
    if footer_pattern.search(content):
        content = footer_pattern.sub(new_footer, content)
        with open(filepath, 'w') as f:
            f.write(content)
        print(f"Updated footer in {filepath}")
    else:
        print(f"Footer pattern not found in {filepath}")
