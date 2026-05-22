import os

light_theme_css = """/* =========================
   FUNCTIONAL UI (Tools)
========================= */
.page-wrapper {
  background-color: var(--off-white);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  color: var(--text-dark);
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
  box-shadow: 0 4px 15px rgba(0,0,0,0.06);
  padding: 24px;
  display: flex;
  flex-direction: column;
}

.form-area {
  background: var(--white);
  border-radius: 8px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.06);
  padding: 32px;
}

.form-area h2 {
  font-size: 1.5rem;
  margin-bottom: 12px;
  color: var(--text-dark);
  font-weight: 800;
}

.field {
  margin-bottom: 24px;
}

.field label {
  display: block;
  font-size: 0.75rem;
  font-weight: 800;
  color: #4f5660;
  text-transform: uppercase;
  margin-bottom: 8px;
  letter-spacing: 0.02em;
}

.field input {
  width: 100%;
  padding: 10px 12px;
  border-radius: 3px;
  border: 1px solid #e3e5e8;
  background-color: #f6f6f6;
  font-family: inherit;
  font-size: 1rem;
  color: var(--text-dark);
  transition: border-color 0.2s, background-color 0.2s;
  height: 48px;
}

.field input:focus {
  outline: none;
  background-color: var(--white);
  border-color: var(--blurple);
}

.input-row {
  display: flex;
  gap: 16px;
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
  height: 44px;
}

.main-btn:hover {
  background-color: var(--blurple-hover);
}

.log-box {
  background-color: #23272a;
  color: #f6f6f6;
  border-radius: 6px;
  padding: 16px;
  font-family: "Consolas", "Courier New", monospace;
  font-size: 0.85rem;
  flex: 1;
  overflow-y: auto;
  margin-top: 16px;
  min-height: 300px;
  box-shadow: inset 0 2px 10px rgba(0,0,0,0.2);
}

.log-line { margin-bottom: 6px; }
.log-line.info { color: #b9bbbe; }
.log-line.success, .log-line.done { color: #43b581; }
.log-line.error, .log-line.fail { color: #f04747; }
.log-line.warning, .log-line.warn { color: #faa61a; }

.panel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e3e5e8;
  padding-bottom: 12px;
}

.panel-header h3 {
  color: var(--text-dark);
  font-size: 1.1rem;
  font-weight: 700;
}

.live-badge {
  font-size: 0.75rem;
  font-weight: 700;
  color: #43b581;
  display: flex;
  align-items: center;
  gap: 6px;
}
.live-badge .dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: #43b581;
  box-shadow: 0 0 8px rgba(67, 181, 129, 0.4);
}

.progress-wrap { margin-top: 24px; }
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

/* =========================
   WIKI UI (Embed Style)
========================= */
.wiki-wrapper {
  width: 100%;
  max-width: 860px;
  margin: 0 auto;
  padding: 3rem 1.5rem;
  flex: 1;
}

.wiki-header {
  text-align: center;
  margin-bottom: 3rem;
}

.wiki-header h1 {
  font-size: clamp(2rem, 4vw, 2.8rem);
  font-weight: 800;
  color: var(--text-dark);
}

.wiki-header h1 i {
  color: var(--blurple);
  margin-right: 0.5rem;
}

.wiki-header p {
  font-size: 1.05rem;
  color: #4f5660;
  max-width: 550px;
  margin: 0.8rem auto 0;
  line-height: 1.7;
}

.wiki-card {
  background: var(--white);
  border-radius: 6px;
  border-left: 4px solid var(--blurple);
  padding: 30px;
  margin-bottom: 2rem;
  box-shadow: 0 4px 15px rgba(0,0,0,0.06);
}

.wiki-card h2 {
  font-size: 1.3rem;
  font-weight: 800;
  color: var(--text-dark);
  margin-bottom: 1.2rem;
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.wiki-card h3 {
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--text-dark);
  margin: 1.5rem 0 0.8rem;
}

.wiki-card p, .wiki-card li {
  font-size: 0.95rem;
  line-height: 1.7;
  color: #2e3338;
  margin-bottom: 0.8rem;
}

.wiki-card ul, .wiki-card ol {
  padding-left: 1.5rem;
  margin-bottom: 0.8rem;
}

.wiki-card code {
  background: #f2f3f5;
  color: #2e3338;
  padding: 0.2rem 0.4rem;
  border-radius: 3px;
  font-size: 0.85rem;
  font-family: "Consolas", "Courier New", monospace;
  border: 1px solid #e3e5e8;
}

.wiki-card .step-list {
  list-style: none;
  padding: 0;
}

.wiki-card .step-list li {
  display: flex;
  gap: 1.2rem;
  margin-bottom: 1.5rem;
  align-items: flex-start;
}

.wiki-card .step-num {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: var(--blurple);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  font-weight: 700;
  flex-shrink: 0;
}

.wiki-card .step-list li > div {
  flex: 1;
}

.wiki-card .step-list li strong {
  display: block;
  color: var(--text-dark);
  font-size: 1rem;
  margin-bottom: 0.3rem;
  font-weight: 700;
}

.wiki-card .alert {
  display: flex;
  align-items: flex-start;
  gap: 0.8rem;
  padding: 16px 20px;
  border-radius: 6px;
  font-size: 0.95rem;
  line-height: 1.6;
  margin: 1.5rem 0;
}

.wiki-card .alert.warning {
  background: #fff8e6;
  border-left: 4px solid #faa61a;
  color: #2e3338;
}

.wiki-card .alert.danger {
  background: #fdeaea;
  border-left: 4px solid #f04747;
  color: #2e3338;
}

.wiki-card .alert.info {
  background: #eef0fd;
  border-left: 4px solid #5865F2;
  color: #2e3338;
}

.wiki-card .alert strong {
  color: var(--text-dark);
  font-weight: 700;
}

.wiki-card .faq-item {
  padding: 1.2rem 0;
  border-bottom: 1px solid #e3e5e8;
}

.wiki-card .faq-item:last-child {
  border-bottom: none;
}

.wiki-card .faq-item h4 {
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 0.5rem;
}

/* =========================
   TOKEN POPUP / MODAL
========================= */
.token-popup-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(3px);
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
  background: var(--white);
  border-radius: 8px;
  padding: 32px;
  max-width: 480px;
  width: 90%;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.3s ease;
}
.token-popup h2 {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--text-dark);
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
  color: #4f5660;
  margin-bottom: 1rem;
}
.token-popup .privacy-notice {
  background: #f6f6f6;
  padding: 12px 16px;
  border-radius: 4px;
  margin-bottom: 24px;
  font-size: 0.85rem;
  color: #4f5660;
  display: flex;
  align-items: flex-start;
  gap: 10px;
  border: 1px solid #e3e5e8;
}
.token-popup .privacy-notice i {
  color: #43b581;
  font-size: 1.1rem;
  margin-top: 0.1rem;
  flex-shrink: 0;
}
.token-popup .privacy-notice strong {
  color: var(--text-dark);
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
  color: #43b581;
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

with open('style.css', 'r') as f:
    lines = f.readlines()

new_lines = []
for line in lines:
    if line.strip() == "/* =========================" and "FUNCTIONAL UI (Tools)" in "".join(lines[lines.index(line):lines.index(line)+3]):
        break
    new_lines.append(line)

with open('style.css', 'w') as f:
    f.writelines(new_lines)
    f.write(light_theme_css)

print("Reverted Functional UI, Wiki, and Modals to Light Theme.")
