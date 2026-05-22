const logBox = document.getElementById('log-box');
const form = document.getElementById('clone-form');
const btn = document.getElementById('start-btn');
const progressWrap = document.getElementById('progress-wrap');
const progressFill = document.getElementById('progress-fill');
const progressText = document.getElementById('progress-text');
const progressIcon = document.getElementById('progress-icon');
const cloneCount = document.getElementById('clone-count');

function log(msg, type = 'info') {
  const icons = { info: 'fa-info-circle', done: 'fa-check-circle', fail: 'fa-times-circle', warn: 'fa-exclamation-circle' };
  const el = document.createElement('div');
  el.className = 'log-line ' + type;
  el.innerHTML = '<i class="fas ' + (icons[type] || 'fa-info-circle') + '"></i> ' + msg;
  logBox.appendChild(el);
  logBox.scrollTop = logBox.scrollHeight;
}

function setProgress(pct, text) {
  progressFill.style.width = Math.min(pct, 100) + '%';
  if (text) progressText.textContent = text;
}

function wait(ms) { return new Promise(r => setTimeout(r, ms)); }

class Cloner {
  constructor(token) {
    this.token = token;
    this.base = 'https://discord.com/api/v9';
    this.running = false;
  }

  delay(ms) { return new Promise(r => setTimeout(r, ms)); }

  async get(ep) {
    try {
      const r = await fetch(this.base + ep, { headers: { "Authorization": this.token, "Content-Type": "application/json" } });
      if (r.status === 200) return await r.json();
      log('Fehler ' + ep + ' (' + r.status + ')', 'fail');
      return null;
    } catch (e) { log('Fehler: ' + e.message, 'fail'); return null; }
  }

  async post(ep, data) {
    try {
      const r = await fetch(this.base + ep, { method: 'POST', headers: { "Authorization": this.token, "Content-Type": "application/json" }, body: JSON.stringify(data) });
      if (r.status === 200 || r.status === 201) return await r.json();
      log('Fehler POST ' + ep, 'fail');
      return null;
    } catch (e) { log('Fehler: ' + e.message, 'fail'); return null; }
  }

  async postRaw(ep, data) {
    try {
      const r = await fetch(this.base + ep, { method: 'POST', headers: { "Authorization": this.token, "Content-Type": "application/json" }, body: JSON.stringify(data) });
      const body = r.status === 200 || r.status === 201 ? await r.json() : null;
      return { data: body, status: r.status };
    } catch (e) { log('Fehler: ' + e.message, 'fail'); return { data: null, status: 0 }; }
  }

  async del(ep) {
    try {
      const r = await fetch(this.base + ep, { method: 'DELETE', headers: { "Authorization": this.token, "Content-Type": "application/json" } });
      return r.status === 200 || r.status === 204;
    } catch (e) { log('Fehler: ' + e.message, 'fail'); return false; }
  }

  async patch(ep, data) {
    try {
      const r = await fetch(this.base + ep, { method: 'PATCH', headers: { "Authorization": this.token, "Content-Type": "application/json" }, body: JSON.stringify(data) });
      return r.status === 200 || r.status === 204;
    } catch (e) { log('Fehler: ' + e.message, 'fail'); return false; }
  }

  async incrementCounter(action) {
    try {
      const r = await fetch('counter.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=' + (action || 'server')
      });
      if (r.ok) {
        const data = await r.json();
        if (cloneCount) cloneCount.textContent = data.total;
      }
    } catch (e) { /* ignore */ }
  }

  async start(src, dst) {
    this.running = true;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Klonen läuft...';
    progressWrap.style.display = 'block';
    logBox.innerHTML = '';

    const srcGuild = await this.get('/guilds/' + src);
    if (!srcGuild) {
      log('Quell-Server nicht erreichbar. Token oder ID falsch.', 'fail');
      this.running = false; btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-play"></i> Klonen starten';
      progressWrap.style.display = 'none';
      return;
    }
    log('Starte: "' + srcGuild.name + '"', 'info');
    setProgress(5, 'Server gefunden — starte Klonvorgang...');

    log('Bereinige Ziel-Server...', 'info');
    setProgress(10, 'Lösche Kanäle...');
    const channels = await this.get('/guilds/' + dst + '/channels');
    if (channels) for (const ch of channels) {
      if (!this.running) break;
      if (await this.del('/channels/' + ch.id)) log('Kanal gelöscht: ' + ch.name, 'done');
      else log('Fehler: ' + ch.name, 'fail');
      await wait(300);
    }
    setProgress(20, 'Lösche Rollen...');
    const roles = await this.get('/guilds/' + dst + '/roles');
    if (roles) for (const r of roles) {
      if (!this.running || r.name === "@everyone") continue;
      if (await this.del('/guilds/' + dst + '/roles/' + r.id)) log('Rolle gelöscht: ' + r.name, 'done');
      else log('Fehler: ' + r.name, 'fail');
      await wait(300);
    }
    if (!this.running) { this.stop(); return; }

    log('Klone Rollen...', 'info');
    setProgress(30, 'Klone Rollen...');
    const srcRoles = await this.get('/guilds/' + src + '/roles');
    const roleMap = {};
    if (srcRoles) {
      const sorted = srcRoles.filter(r => r.name !== "@everyone").sort((a, b) => b.position - a.position);
      for (const r of sorted) {
        if (!this.running) break;
        const nr = await this.post('/guilds/' + dst + '/roles', {
          name: r.name, color: r.color, hoist: r.hoist,
          permissions: r.permissions.toString(), mentionable: r.mentionable
        });
        if (nr) { roleMap[r.id] = nr.id; log('Rolle: ' + nr.name, 'done'); }
        else log('Fehler: ' + r.name, 'fail');
        await wait(500);
      }
    }
    if (!this.running) { this.stop(); return; }

    log('Klone Kanäle...', 'info');
    setProgress(50, 'Klone Kanäle...');
    const srcCh = await this.get('/guilds/' + src + '/channels');
    if (srcCh) {
      const cats = srcCh.filter(c => c.type === 4).sort((a, b) => a.position - b.position);
      for (const c of cats) {
        if (!this.running) break;
        const nc = await this.post('/guilds/' + dst + '/channels', {
          name: c.name.length > 100 ? c.name.slice(0, 97) + '...' : c.name,
          type: 4, position: c.position
        });
        if (nc) { roleMap[c.id] = nc.id; log('Kategorie: ' + nc.name, 'done'); }
        else log('Fehler: ' + c.name, 'fail');
        await wait(700);
      }
      const others = srcCh.filter(c => c.type !== 4).sort((a, b) => a.position - b.position);
      for (const c of others) {
        if (!this.running) break;
        const nc = await this.post('/guilds/' + dst + '/channels', {
          name: c.name.length > 100 ? c.name.slice(0, 97) + '...' : c.name,
          type: c.type, position: c.position,
          topic: (c.topic || '').slice(0, 1024),
          bitrate: c.bitrate || 64000, user_limit: c.user_limit || 0,
          parent_id: roleMap[c.parent_id] || null, nsfw: c.nsfw || false
        });
        if (nc) log('Kanal: ' + nc.name, 'done');
        else log('Fehler: ' + c.name, 'fail');
        await wait(700);
      }
    }
    if (!this.running) { this.stop(); return; }

    log('Klone Emojis...', 'info');
    setProgress(75, 'Klone Emojis...');
    await this.cloneEmojis(src, dst);
    if (!this.running) { this.stop(); return; }

    setProgress(85, 'Kopiere Server-Info...');
    const info = { name: srcGuild.name };
    if (srcGuild.icon) {
      const ext = srcGuild.icon.startsWith('a_') ? 'gif' : 'png';
      const ir = await fetch('https://cdn.discordapp.com/icons/' + src + '/' + srcGuild.icon + '.' + ext + '?size=1024');
      if (ir.ok) {
        const blob = await ir.blob();
        info.icon = await new Promise(r => { const rd = new FileReader(); rd.onload = () => r(rd.result); rd.readAsDataURL(blob); });
      }
    }
    if (await this.patch('/guilds/' + dst, info)) log('Server-Name: ' + srcGuild.name, 'done');

    await this.incrementCounter();

    setProgress(100, 'Klonen abgeschlossen!');
    log('Klonen erfolgreich abgeschlossen!', 'done');

    progressIcon.className = 'fas fa-check-circle';
    progressIcon.style.color = '#55efc4';

    setTimeout(() => { location.reload(); }, 3000);

    this.running = false;
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-check"></i> Abgeschlossen';
  }

  async cloneEmojis(src, dst) {
    const emojis = await this.get('/guilds/' + src + '/emojis');
    if (!emojis || emojis.length === 0) {
      log('Keine Emojis zum Klonen gefunden.', 'info');
      return;
    }

    log('Klone ' + emojis.length + ' Emojis...', 'info');
    let cloned = 0, failed = 0;

    for (const emoji of emojis) {
      if (!this.running) break;
      try {
        const ext = emoji.animated ? 'gif' : 'png';
        const url = 'https://cdn.discordapp.com/emojis/' + emoji.id + '.' + ext + '?size=128';
        const resp = await fetch(url);
        if (!resp.ok) {
          log('Übersprungen: ' + emoji.name + ' (Download fehlgeschlagen)', 'warn');
          failed++;
          continue;
        }
        const blob = await resp.blob();
        const base64 = await new Promise(resolve => {
          const reader = new FileReader();
          reader.onload = () => resolve(reader.result);
          reader.readAsDataURL(blob);
        });

        const result = await this.postRaw('/guilds/' + dst + '/emojis', {
          name: emoji.name,
          image: base64
        });

        if (result.data) {
          log('Emoji geklont: ' + emoji.name, 'done');
          cloned++;
        } else if (result.status === 400 || result.status === 403) {
          const remaining = emojis.length - cloned - failed - 1;
          log('Emoji-Limit erreicht! ' + remaining + ' Emojis übersprungen.', 'warn');
          break;
        } else {
          log('Fehler: ' + emoji.name, 'fail');
          failed++;
        }
      } catch (e) {
        log('Übersprungen: ' + emoji.name + ' (' + e.message + ')', 'warn');
        failed++;
      }
      await this.delay(400);
    }

    if (cloned > 0) log(cloned + ' von ' + emojis.length + ' Emojis geklont.', 'done');
    if (failed > 0) log(failed + ' Emojis fehlgeschlagen.', 'warn');
  }

  stop() {
    this.running = false;
    log('Abgebrochen.', 'warn');
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-play"></i> Klonen starten';
    progressWrap.style.display = 'none';
  }
}


