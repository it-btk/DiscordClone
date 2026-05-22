'use strict';

// Global log helper for backward compatibility
function log(msg, type) {
  var logs = document.getElementById('logs');
  if (!logs) return;
  var entry = document.createElement('div');
  entry.className = 'log-line log-' + (type || 'info');
  var text = document.createElement('span');
  text.textContent = msg;
  entry.appendChild(text);
  logs.appendChild(entry);
  logs.scrollTop = logs.scrollHeight;
}

class DiscordCloner {
    constructor(token) {
        this.token = token;
        this.baseUrl = 'https://discord.com/api/v10';
        this.isCloning = false;
        this.currentOperation = null;
        this.onProgress = null;
        this._progressCurrent = 0;
        this._progressTotal = 0;
    }

    _tick() {
        this._progressCurrent++;
        if (this.onProgress) {
            this.onProgress(this._progressCurrent, this._progressTotal);
        }
    }

    delay(ms) {
        return new Promise(resolve => {
            if (!this.isCloning) return resolve();
            this.currentOperation = setTimeout(resolve, ms);
        });
    }

    _timestamp() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        return `${h}:${m}:${s}`;
    }

    log(message, type = 'info', indent = 0) {
        const logContainer = document.getElementById('logs');
        if (!logContainer) return;

        const entry = document.createElement('div');
        entry.className = `log-line log-${type}`;

        const tsSpan = document.createElement('span');
        tsSpan.className = 'log-timestamp';
        tsSpan.textContent = `[${this._timestamp()}]`;
        tsSpan.style.color = 'rgba(255,255,255,0.35)';
        tsSpan.style.marginRight = '0.5rem';
        tsSpan.style.fontSize = '0.7rem';
        tsSpan.style.fontFamily = '"JetBrains Mono", monospace';

        const textSpan = document.createElement('span');
        const prefix = '\u00a0\u00a0'.repeat(indent);
        textSpan.textContent = prefix + message;

        entry.appendChild(tsSpan);
        entry.appendChild(textSpan);
        logContainer.appendChild(entry);
        logContainer.scrollTop = logContainer.scrollHeight;
    }

    async _request(method, endpoint, body = null) {
        const MAX_RETRIES = 5;
        for (let attempt = 0; attempt < MAX_RETRIES; attempt++) {
            try {
                const opts = {
                    method,
                    headers: {
                        'Authorization': this.token,
                        'Content-Type': 'application/json'
                    }
                };
                if (body !== null) opts.body = JSON.stringify(body);

                const response = await fetch(`${this.baseUrl}${endpoint}`, opts);

                if (response.status === 429) {
                    let retryAfter = 1000;
                    try {
                        const json = await response.json();
                        retryAfter = (json.retry_after || 1) * 1000;
                    } catch (_) { /* ignore parse error */ }

                    this.log(`⏱ Rate limited — retrying in ${Math.round(retryAfter)}ms...`, 'warn');
                    await this.delay(retryAfter + 100);
                    continue;
                }

                return response;
            } catch (error) {
                this.log(`Network error [${method} ${endpoint}]: ${error.message}`, 'fail');
                return null;
            }
        }
        this.log(`Max retries reached for ${method} ${endpoint}`, 'fail');
        return null;
    }

    async get(endpoint) {
        const r = await this._request('GET', endpoint);
        if (!r) return null;
        if (r.ok) return r.json();
        this.log(`GET ${endpoint} → ${r.status}`, 'fail');
        return null;
    }

    async post(endpoint, data) {
        const r = await this._request('POST', endpoint, data);
        if (!r) return null;
        if (r.status === 200 || r.status === 201) return r.json();
        this.log(`POST ${endpoint} → ${r.status}`, 'fail');
        return null;
    }

    async postRaw(endpoint, data) {
        const r = await this._request('POST', endpoint, data);
        if (!r) return { data: null, status: 0 };
        if (r.status === 200 || r.status === 201) return { data: await r.json(), status: r.status };
        return { data: null, status: r.status };
    }

    async patch(endpoint, data) {
        const r = await this._request('PATCH', endpoint, data);
        if (!r) return false;
        if (r.status === 200 || r.status === 204) return true;
        this.log(`PATCH ${endpoint} → ${r.status}`, 'fail');
        return false;
    }

    async del(endpoint) {
        const r = await this._request('DELETE', endpoint);
        if (!r) return false;
        if (r.status === 200 || r.status === 204) return true;
        this.log(`DELETE ${endpoint} → ${r.status}`, 'fail');
        return false;
    }

    // ---------- Cleanup ----------

    async deleteAllChannels(guildId) {
        const channels = await this.get(`/guilds/${guildId}/channels`);
        if (!channels) return;
        for (const ch of channels) {
            if (!this.isCloning) break;
            await this.del(`/channels/${ch.id}`);
            this.log(`Deleted channel: ${ch.name}`, 'done', 1);
            await this.delay(200);
        }
    }

    async deleteAllRoles(guildId) {
        const roles = await this.get(`/guilds/${guildId}/roles`);
        if (!roles) return;
        for (const role of roles) {
            if (!this.isCloning) break;
            if (role.name === '@everyone') continue;
            await this.del(`/guilds/${guildId}/roles/${role.id}`);
            this.log(`Deleted role: ${role.name}`, 'done', 1);
            await this.delay(200);
        }
    }

    async deleteAllEmojis(guildId) {
        const emojis = await this.get(`/guilds/${guildId}/emojis`);
        if (!emojis || emojis.length === 0) return;
        for (const emoji of emojis) {
            if (!this.isCloning) break;
            await this.del(`/guilds/${guildId}/emojis/${emoji.id}`);
            this.log(`Deleted emoji: ${emoji.name}`, 'done', 1);
            await this.delay(200);
        }
    }

    // ---------- Server details ----------

    async getServerDetails(guildId) {
        const guild = await this.get(`/guilds/${guildId}`);
        if (!guild) return null;

        let iconBase64 = null;
        if (guild.icon) {
            const ext = guild.icon.startsWith('a_') ? 'gif' : 'png';
            try {
                const iconUrl = `https://cdn.discordapp.com/icons/${guildId}/${guild.icon}.${ext}?size=1024`;
                const resp = await fetch(iconUrl);
                if (resp.ok) {
                    const blob = await resp.blob();
                    iconBase64 = await new Promise(resolve => {
                        const reader = new FileReader();
                        reader.onload = () => resolve(reader.result);
                        reader.readAsDataURL(blob);
                    });
                }
            } catch (e) {
                this.log(`Could not fetch icon: ${e.message}`, 'warn', 1);
            }
        }
        return { name: guild.name, icon: iconBase64 };
    }

    async getEveryonePermissions(guildId) {
        const roles = await this.get(`/guilds/${guildId}/roles`);
        if (roles) {
            const ev = roles.find(r => r.name === '@everyone');
            if (ev) return ev.permissions.toString();
        }
        return '0';
    }

    // ---------- Permission overwrites ----------

    processOverwrites(overwrites, roleIdMap, everyoneSourceId, everyoneDestId, everyoneDefaultPerms) {
        const result = [];
        let foundEveryone = false;

        for (const ow of overwrites) {
            if (ow.type === 0) {
                if (ow.id === everyoneSourceId) {
                    foundEveryone = true;
                    result.push({ id: everyoneDestId, type: 0, allow: ow.allow.toString(), deny: ow.deny.toString() });
                } else if (roleIdMap[ow.id]) {
                    result.push({ id: roleIdMap[ow.id], type: 0, allow: ow.allow.toString(), deny: ow.deny.toString() });
                }
            }
        }

        if (!foundEveryone) {
            result.push({ id: everyoneDestId, type: 0, allow: everyoneDefaultPerms, deny: '0' });
        }
        return result;
    }

    // ---------- Clone roles ----------

    async cloneRoles(sourceGuildId, destGuildId) {
        const roles = await this.get(`/guilds/${sourceGuildId}/roles`);
        if (!roles) {
            this.log('Failed to fetch source roles', 'fail');
            return { roleIdMap: null, sourceEveryoneId: null, everyoneDestId: null };
        }

        const roleIdMap = {};
        const sourceEveryone = roles.find(r => r.name === '@everyone');
        const sourceEveryoneId = sourceEveryone ? sourceEveryone.id : null;
        const sortedRoles = roles
            .filter(r => r.name !== '@everyone')
            .sort((a, b) => b.position - a.position);

        for (const role of sortedRoles) {
            if (!this.isCloning) break;
            const newRole = await this.post(`/guilds/${destGuildId}/roles`, {
                name:        role.name,
                color:       role.color,
                hoist:       role.hoist,
                permissions: role.permissions.toString(),
                mentionable: role.mentionable
            });
            if (newRole) {
                roleIdMap[role.id] = newRole.id;
                this.log(`Cloned role: ${newRole.name}`, 'done', 1);
            } else {
                this.log(`Failed role: ${role.name}`, 'fail', 1);
            }
            this._tick();
            await this.delay(300);
        }

        const destRoles = await this.get(`/guilds/${destGuildId}/roles`);
        const everyoneDestId = destRoles ? destRoles.find(r => r.name === '@everyone')?.id : null;
        return { roleIdMap, sourceEveryoneId, everyoneDestId };
    }

    // ---------- Clone channels ----------

    async _tryCreateChannel(destGuildId, payload) {
        const { data, status } = await this.postRaw(`/guilds/${destGuildId}/channels`, payload);
        return { data, status };
    }

    async cloneChannels(sourceGuildId, destGuildId, roleIdMap, everyoneSourceId, everyoneDestId) {
        const channels = await this.get(`/guilds/${sourceGuildId}/channels`);
        if (!channels) { this.log('Failed to fetch channels', 'fail'); return; }

        const everyoneDefaultPerms = await this.getEveryonePermissions(sourceGuildId);

        const categories = channels.filter(ch => ch.type === 4).sort((a, b) => a.position - b.position);
        const others     = channels.filter(ch => ch.type !== 4).sort((a, b) => a.position - b.position);
        const failedChannels = [];

        // Clone categories
        for (const cat of categories) {
            if (!this.isCloning) break;
            const name = cat.name.length > 100 ? cat.name.slice(0, 97) + '...' : cat.name;
            const payload = {
                name,
                type: 4,
                position: cat.position,
                permission_overwrites: this.processOverwrites(
                    cat.permission_overwrites || [], roleIdMap, everyoneSourceId, everyoneDestId, everyoneDefaultPerms
                )
            };
            try {
                const { data: newCat } = await this._tryCreateChannel(destGuildId, payload);
                if (newCat) {
                    roleIdMap[cat.id] = newCat.id;
                    this.log(`Cloned category: ${newCat.name}`, 'done', 1);
                } else {
                    failedChannels.push({ channel: cat, payload, isCategory: true });
                }
            } catch (e) {
                failedChannels.push({ channel: cat, payload, isCategory: true });
            }
            this._tick();
            await this.delay(400);
        }

        // Clone other channels
        for (const ch of others) {
            if (!this.isCloning) break;
            const name  = ch.name.length > 100  ? ch.name.slice(0, 97)   + '...' : ch.name;
            const topic = (ch.topic || '').length > 1024 ? ch.topic.slice(0, 1021) + '...' : (ch.topic || '');
            const payload = {
                name,
                type:                  ch.type,
                position:              ch.position,
                topic,
                bitrate:               ch.bitrate || 64000,
                user_limit:            ch.user_limit || 0,
                parent_id:             roleIdMap[ch.parent_id] || null,
                nsfw:                  ch.nsfw || false,
                rate_limit_per_user:   ch.rate_limit_per_user || 0,
                permission_overwrites: this.processOverwrites(
                    ch.permission_overwrites || [], roleIdMap, everyoneSourceId, everyoneDestId, everyoneDefaultPerms
                )
            };
            try {
                const { data: newCh } = await this._tryCreateChannel(destGuildId, payload);
                if (newCh) {
                    this.log(`Cloned channel: ${newCh.name}`, 'done', 1);
                } else {
                    failedChannels.push({ channel: ch, payload, isCategory: false });
                }
            } catch (e) {
                failedChannels.push({ channel: ch, payload, isCategory: false });
            }
            this._tick();
            await this.delay(400);
        }

        // Retry failed channels (up to 2 attempts)
        if (failedChannels.length > 0 && this.isCloning) {
            this.log(`Retrying ${failedChannels.length} failed channels...`, 'info');
            const maxRetries = 2;
            let stillFailed = [...failedChannels];

            for (let attempt = 1; attempt <= maxRetries && stillFailed.length > 0 && this.isCloning; attempt++) {
                this.log(`Retry attempt ${attempt}/${maxRetries} for ${stillFailed.length} channels...`, 'info', 1);
                await this.delay(2000);
                const nextFailed = [];

                for (const item of stillFailed) {
                    if (!this.isCloning) break;
                    try {
                        const { data: result } = await this._tryCreateChannel(destGuildId, item.payload);
                        if (result) {
                            if (item.isCategory) roleIdMap[item.channel.id] = result.id;
                            this.log(`Cloned (retry): ${item.channel.name}`, 'done', 1);
                        } else {
                            nextFailed.push(item);
                        }
                    } catch (e) {
                        nextFailed.push(item);
                    }
                    await this.delay(400);
                }

                stillFailed = nextFailed;
            }

            if (stillFailed.length > 0) {
                for (const item of stillFailed) {
                    this.log(`Gave up on: ${item.channel.name}`, 'warn', 1);
                }
            }
        }
    }

    // ---------- Clone emojis ----------

    async cloneEmojis(sourceGuildId, destGuildId) {
        const emojis = await this.get(`/guilds/${sourceGuildId}/emojis`);
        if (!emojis || emojis.length === 0) {
            this.log('No emojis to clone', 'info', 1);
            return;
        }

        this.log(`Cloning ${emojis.length} emojis...`, 'info');
        let cloned = 0;
        let failed = 0;

        for (const emoji of emojis) {
            if (!this.isCloning) break;
            try {
                const ext = emoji.animated ? 'gif' : 'png';
                const url = `https://cdn.discordapp.com/emojis/${emoji.id}.${ext}?size=128`;
                const resp = await fetch(url);
                if (!resp.ok) {
                    this.log(`Skipped emoji: ${emoji.name} (download failed)`, 'warn', 1);
                    failed++;
                    this._tick();
                    continue;
                }
                const blob = await resp.blob();
                const base64 = await new Promise(resolve => {
                    const reader = new FileReader();
                    reader.onload = () => resolve(reader.result);
                    reader.readAsDataURL(blob);
                });

                const { data: newEmoji, status } = await this.postRaw(`/guilds/${destGuildId}/emojis`, {
                    name: emoji.name,
                    image: base64
                });

                if (newEmoji) {
                    this.log(`Cloned emoji: ${emoji.name}`, 'done', 1);
                    cloned++;
                } else if (status === 400 || status === 403) {
                    const remaining = emojis.length - cloned - failed - 1;
                    this.log(`Emoji limit reached! ${remaining} skipped.`, 'warn');
                    for (let i = 0; i < remaining + 1; i++) this._tick();
                    break;
                } else {
                    this.log(`Failed emoji: ${emoji.name}`, 'warn', 1);
                    failed++;
                }
            } catch (e) {
                this.log(`Skipped emoji: ${emoji.name} (${e.message})`, 'warn', 1);
                failed++;
            }
            this._tick();
            await this.delay(400);
        }

        if (cloned > 0) {
            this.log(`Cloned ${cloned}/${emojis.length} emojis.`, 'done');
        }
        if (failed > 0) {
            this.log(`${failed} emojis failed.`, 'warn');
        }
    }

    // ---------- Main entry ----------

    async startCloning(sourceId, destId) {
        this.isCloning = true;
        this._progressCurrent = 0;

        const logs = document.getElementById('logs');
        const startBtn = document.getElementById('start-btn');
        const progressWrap = document.getElementById('progress-wrap');
        const progressFill = document.getElementById('progress-fill');
        const progressText = document.getElementById('progress-text');

        if (logs) logs.innerHTML = '';
        if (startBtn) startBtn.disabled = true;
        if (progressWrap) progressWrap.style.display = 'block';
        if (progressFill) progressFill.style.width = '0%';
        if (progressText) progressText.textContent = 'Vorbereitung läuft...';

        this.log('Starte Klonvorgang...', 'info');

        const sourceDetails = await this.getServerDetails(sourceId);
        if (!sourceDetails) {
            this.log('Quell-Server nicht erreichbar. Token oder ID prüfen.', 'fail');
            if (startBtn) startBtn.disabled = false;
            if (progressWrap) progressWrap.style.display = 'none';
            this.isCloning = false;
            return;
        }

        this.log(`Server gefunden: "${sourceDetails.name}"`, 'info');

        // Estimate total steps
        const [srcChannels, srcRoles, srcEmojis] = await Promise.all([
            this.get(`/guilds/${sourceId}/channels`),
            this.get(`/guilds/${sourceId}/roles`),
            this.get(`/guilds/${sourceId}/emojis`)
        ]);
        const totalChannels = srcChannels ? srcChannels.length : 0;
        const totalRoles    = srcRoles ? srcRoles.filter(r => r.name !== '@everyone').length : 0;
        const totalEmojis   = srcEmojis ? srcEmojis.length : 0;
        this._progressTotal = totalRoles + totalChannels + totalEmojis + 2;

        if (this.onProgress) this.onProgress(0, this._progressTotal);

        // Cleanup destination
        this.log('Bereinige Ziel-Server...', 'info');
        if (progressText) progressText.textContent = 'Lösche Kanäle, Rollen & Emojis...';

        await this.deleteAllChannels(destId);
        if (progressFill) progressFill.style.width = '10%';
        await this.deleteAllRoles(destId);
        if (progressFill) progressFill.style.width = '20%';
        await this.deleteAllEmojis(destId);
        if (progressFill) progressFill.style.width = '25%';

        if (!this.isCloning) {
            this.log('Vorgang abgebrochen.', 'warn');
            if (startBtn) startBtn.disabled = false;
            if (progressWrap) progressWrap.style.display = 'none';
            this.isCloning = false;
            return;
        }
        this.log('Ziel-Server bereinigt.', 'done');

        // Clone roles
        this.log('Klone Rollen...', 'info');
        if (progressText) progressText.textContent = 'Klone Rollen...';
        const { roleIdMap, sourceEveryoneId, everyoneDestId } = await this.cloneRoles(sourceId, destId);
        if (progressFill) progressFill.style.width = '45%';
        if (!this.isCloning) { this._stopEarly(startBtn, progressWrap); return; }
        this.log('Rollen geklont.', 'done');

        // Clone channels
        this.log('Klone Kanäle...', 'info');
        if (progressText) progressText.textContent = 'Klone Kanäle...';
        await this.cloneChannels(sourceId, destId, roleIdMap || {}, sourceEveryoneId, everyoneDestId);
        if (progressFill) progressFill.style.width = '70%';
        if (!this.isCloning) { this._stopEarly(startBtn, progressWrap); return; }
        this.log('Kanäle geklont.', 'done');

        // Clone server name + icon
        this.log('Kopiere Server-Info...', 'info');
        if (progressText) progressText.textContent = 'Kopiere Name & Icon...';
        try {
            const patchData = { name: sourceDetails.name };
            if (sourceDetails.icon) patchData.icon = sourceDetails.icon;
            const ok = await this.patch(`/guilds/${destId}`, patchData);
            if (ok) {
                this.log(`Server-Name: ${sourceDetails.name}`, 'done');
                if (sourceDetails.icon) this.log('Server-Icon kopiert.', 'done');
            } else {
                this.log('Server-Info konnte nicht kopiert werden.', 'fail');
            }
        } catch (e) {
            this.log('Server-Info konnte nicht kopiert werden.', 'fail');
        }
        if (progressFill) progressFill.style.width = '80%';
        if (!this.isCloning) { this._stopEarly(startBtn, progressWrap); return; }

        // Clone emojis
        this.log('Klone Emojis...', 'info');
        if (progressText) progressText.textContent = 'Klone Emojis...';
        await this.cloneEmojis(sourceId, destId);
        if (progressFill) progressFill.style.width = '95%';
        if (!this.isCloning) { this._stopEarly(startBtn, progressWrap); return; }

        this._tick();

        // Increment counter
        try {
            await fetch('../../counter.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=server'
            });
        } catch (_) {}

        if (progressFill) progressFill.style.width = '100%';
        if (progressText) progressText.textContent = 'Klonen abgeschlossen!';
        this.log('Klonen erfolgreich abgeschlossen!', 'done');
        this.log(`Quelle: ${sourceId} → Ziel: ${destId}`, 'info');

        this.isCloning = false;
        if (startBtn) {
            startBtn.disabled = false;
            startBtn.innerHTML = '<i class="fas fa-check"></i> Abgeschlossen';
        }
        const icon = document.getElementById('progress-icon');
        if (icon) {
            icon.className = 'fas fa-check-circle';
            icon.style.color = '#55efc4';
        }
    }

    stopCloning() {
        if (this.currentOperation) clearTimeout(this.currentOperation);
        this.isCloning = false;
        this.log('Abbrechen angefordert...', 'warn');
        const startBtn = document.getElementById('start-btn');
        if (startBtn) startBtn.disabled = false;
    }

    _stopEarly(startBtn, progressWrap) {
        this.log('Vorgang abgebrochen.', 'warn');
        this.isCloning = false;
        if (startBtn) startBtn.disabled = false;
        if (progressWrap) progressWrap.style.display = 'none';
    }
}

let clonerInstance = null;