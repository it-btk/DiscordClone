import os
import re

modal_styles = """
/* =========================
   TOKEN POPUP / MODAL
========================= */
.token-popup-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.75);
  backdrop-filter: blur(2px);
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
  background: #313338;
  border-radius: 8px;
  padding: 32px;
  max-width: 480px;
  width: 90%;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
  animation: slideUp 0.3s ease;
}
.token-popup h2 {
  font-size: 1.5rem;
  font-weight: 800;
  color: #f2f3f5;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 0.6rem;
}
.token-popup h2 i {
  color: var(--blurple);
}
.token-popup p {
  font-size: 0.95rem;
  line-height: 1.6;
  color: #dbdee1;
  margin-bottom: 1rem;
}
.token-popup .privacy-notice {
  background: #2b2d31;
  padding: 12px 16px;
  border-radius: 4px;
  margin-bottom: 24px;
  font-size: 0.85rem;
  color: #b5bac1;
  display: flex;
  align-items: flex-start;
  gap: 10px;
}
.token-popup .privacy-notice i {
  color: #57F287;
  font-size: 1.1rem;
  margin-top: 0.1rem;
  flex-shrink: 0;
}
.token-popup .privacy-notice strong {
  color: #f2f3f5;
  display: block;
  margin-bottom: 4px;
}
.token-popup .popup-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 24px;
}
.token-popup .popup-btn {
  padding: 10px 24px;
  border: none;
  border-radius: 3px;
  background-color: var(--blurple);
  color: #fff;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.token-popup .popup-btn:hover {
  background-color: var(--blurple-hover);
}
.token-popup .popup-btn:active {
  transform: scale(0.97);
}
.token-popup .popup-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.token-status {
  font-size: 0.85rem;
  color: #57F287;
  margin-top: 8px;
  display: none;
  align-items: center;
  gap: 6px;
}
.token-status.visible {
  display: flex;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to   { opacity: 1; }
}
@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px) scale(0.98); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}
"""

with open('style.css', 'a') as f:
    f.write(modal_styles)

files_to_update = [
    'clone/server/index.php',
    'clone/emoji/index.php'
]

for filepath in files_to_update:
    if not os.path.exists(filepath):
        continue
    
    with open(filepath, 'r') as f:
        content = f.read()

    # Using regex to remove the <style>...</style> block completely
    content = re.sub(r'<style>.*?</style>', '', content, flags=re.DOTALL)

    with open(filepath, 'w') as f:
        f.write(content)

print("Modal styles moved and inline styles stripped from clone indexes.")
