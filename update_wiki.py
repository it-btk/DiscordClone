import os
import re

# 1. Update style.css with Wiki styles
wiki_styles = """
/* =========================
   WIKI UI (Embed Style)
========================= */
.wiki-wrapper {
  width: 100%;
  max-width: 860px;
  margin: 0 auto;
  padding: 2rem 1.5rem 3rem;
  flex: 1;
}

.wiki-header {
  text-align: center;
  margin-bottom: 2.5rem;
}

.wiki-header h1 {
  font-size: clamp(1.8rem, 3vw, 2.4rem);
  font-weight: 800;
  color: #f2f3f5;
}

.wiki-header h1 i {
  color: var(--blurple);
  margin-right: 0.5rem;
}

.wiki-header p {
  font-size: 0.95rem;
  color: #b5bac1;
  max-width: 500px;
  margin: 0.6rem auto 0;
  line-height: 1.7;
}

.wiki-card {
  background: #2b2d31;
  border-radius: 4px;
  border-left: 4px solid var(--blurple);
  padding: 24px;
  margin-bottom: 1.5rem;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.wiki-card h2 {
  font-size: 1.15rem;
  font-weight: 700;
  color: #f2f3f5;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.wiki-card h3 {
  font-size: 0.95rem;
  font-weight: 700;
  color: #dbdee1;
  margin: 1.2rem 0 0.6rem;
}

.wiki-card p, .wiki-card li {
  font-size: 0.9rem;
  line-height: 1.6;
  color: #dbdee1;
  margin-bottom: 0.7rem;
}

.wiki-card ul, .wiki-card ol {
  padding-left: 1.4rem;
  margin-bottom: 0.7rem;
}

.wiki-card code {
  background: #1e1f22;
  color: #dbdee1;
  padding: 0.2rem 0.4rem;
  border-radius: 3px;
  font-size: 0.85rem;
  font-family: "Consolas", "Courier New", monospace;
  border: 1px solid #111214;
}

.wiki-card .step-list {
  list-style: none;
  padding: 0;
}

.wiki-card .step-list li {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.2rem;
  align-items: flex-start;
}

.wiki-card .step-num {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: var(--blurple);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  font-weight: 700;
  flex-shrink: 0;
}

.wiki-card .step-list li > div {
  flex: 1;
}

.wiki-card .step-list li strong {
  display: block;
  color: #f2f3f5;
  font-size: 0.95rem;
  margin-bottom: 0.2rem;
}

.wiki-card .alert {
  display: flex;
  align-items: flex-start;
  gap: 0.7rem;
  padding: 12px 16px;
  border-radius: 4px;
  font-size: 0.9rem;
  line-height: 1.5;
  margin: 1rem 0;
}

.wiki-card .alert.warning {
  background: rgba(254, 231, 92, 0.1);
  border-left: 4px solid #FEE75C;
  color: #dbdee1;
}

.wiki-card .alert.danger {
  background: rgba(237, 66, 69, 0.1);
  border-left: 4px solid #ED4245;
  color: #dbdee1;
}

.wiki-card .alert.info {
  background: rgba(88, 101, 242, 0.1);
  border-left: 4px solid #5865F2;
  color: #dbdee1;
}

.wiki-card .alert strong {
  color: #f2f3f5;
}

.wiki-card .faq-item {
  padding: 1rem 0;
  border-bottom: 1px solid #1e1f22;
}

.wiki-card .faq-item:last-child {
  border-bottom: none;
}

.wiki-card .faq-item h4 {
  font-size: 0.95rem;
  font-weight: 700;
  color: #f2f3f5;
  margin-bottom: 0.4rem;
}
"""

with open('style.css', 'a') as f:
    f.write(wiki_styles)

# 2. Strip <style> from wiki/index.php
with open('wiki/index.php', 'r') as f:
    wiki_content = f.read()

# Using regex to remove the <style>...</style> block completely
wiki_content = re.sub(r'<style>.*?</style>', '', wiki_content, flags=re.DOTALL)

with open('wiki/index.php', 'w') as f:
    f.write(wiki_content)

print("Wiki styles moved and inline styles stripped.")
