<?php
// Panel admin. PENTING: file ini TIDAK berpassword.
// Lindungi dengan .htpasswd atau Cloudflare Access sebelum dipakai publik.
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videy Admin — Video Titles</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            background: radial-gradient(ellipse at 50% 45%, #171044 0%, #080716 48%, #020207 100%);
            color: #e8edf5; min-height: 100vh; padding: 20px;
        }
        .container { max-width: 600px; margin: 0 auto; }
        h1 {
            font-size: 1.6rem; margin-bottom: 24px; text-align: center;
            background: linear-gradient(120deg, #ff00c8, #7928ff, #00eaff);
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card {
            background: rgba(26, 34, 52, 0.9); border: 1px solid rgba(0, 245, 255, 0.25);
            border-radius: 14px; padding: 24px; margin-bottom: 24px;
            box-shadow: 0 0 12px rgba(0, 245, 255, 0.12);
        }
        label { display: block; font-size: 0.85rem; color: #9aa7bd; margin-bottom: 6px; }
        input[type="text"] {
            width: 100%; padding: 12px 14px; margin-bottom: 16px;
            background: rgba(11, 15, 23, 0.8); border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px; color: #e8edf5; font-size: 0.95rem; font-family: Arial, sans-serif;
        }
        input[type="text"]:focus { outline: none; border-color: #00eaff; box-shadow: 0 0 8px rgba(0, 245, 255, 0.3); }
        .btn {
            display: inline-block; padding: 12px 28px; border: none; border-radius: 999px;
            font-weight: 700; font-size: 0.9rem; cursor: pointer; letter-spacing: 1px;
            background: linear-gradient(110deg, #ff00c8 0%, #7928ff 48%, #00eaff 100%);
            color: #fff; transition: transform 0.15s ease, filter 0.15s ease;
        }
        .btn:hover { transform: translateY(-1px); filter: brightness(1.15); }
        .btn-sm {
            padding: 6px 14px; font-size: 0.8rem; border-radius: 6px; letter-spacing: 0;
            background: rgba(255, 0, 100, 0.2); border: 1px solid rgba(255, 0, 100, 0.4);
            color: #ff6b9d;
        }
        .btn-sm:hover { background: rgba(255, 0, 100, 0.35); }
        .msg { padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 0.85rem; display: none; }
        .msg.ok { display: block; background: rgba(0, 200, 100, 0.15); border: 1px solid rgba(0, 200, 100, 0.4); color: #4ade80; }
        .msg.err { display: block; background: rgba(255, 0, 100, 0.15); border: 1px solid rgba(255, 0, 100, 0.4); color: #ff6b9d; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 0.8rem; color: #9aa7bd; padding: 8px 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); }
        td { padding: 10px; font-size: 0.88rem; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        .empty { text-align: center; color: #9aa7bd; padding: 20px; font-size: 0.9rem; }
        .code-cell { font-family: monospace; color: #00eaff; }
        .title-cell { color: #e8edf5; }
        .link-cell a { color: #9aa7bd; font-size: 0.8rem; text-decoration: none; }
        .link-cell a:hover { color: #00eaff; text-decoration: underline; }
        .loading-list { text-align: center; color: #9aa7bd; padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>▶ Videy Admin</h1>
        <div class="card">
            <div class="msg" id="msg"></div>
            <label for="code">Video Code</label>
            <input type="text" id="code" placeholder="e.g. DC0WSbik1" autocomplete="off">
            <label for="title">Video Title</label>
            <input type="text" id="title" placeholder="e.g. My Awesome Video" autocomplete="off">
            <button class="btn" id="saveBtn">SAVE TITLE</button>
        </div>
        <div class="card">
            <label style="margin-bottom: 12px;">Saved Video Titles</label>
            <div id="listArea"><div class="loading-list">Loading...</div></div>
        </div>
    </div>
    <script>
        const API_URL = 'api.php';
        const msgEl = document.getElementById('msg');
        const codeEl = document.getElementById('code');
        const titleEl = document.getElementById('title');
        const saveBtn = document.getElementById('saveBtn');
        const listArea = document.getElementById('listArea');
        function showMsg(text, type) {
            msgEl.textContent = text;
            msgEl.className = 'msg ' + type;
            setTimeout(function() { msgEl.className = 'msg'; }, 3000);
        }
        async function loadList() {
            try {
                const resp = await fetch(API_URL);
                const data = await resp.json();
                renderList(data);
            } catch (e) {
                listArea.innerHTML = '<div class="empty">Could not load titles. Check that api.php is reachable.</div>';
            }
        }
        function renderList(data) {
            const entries = Object.entries(data || {});
            if (entries.length === 0) {
                listArea.innerHTML = '<div class="empty">No titles saved yet.</div>';
                return;
            }
            let html = '<table><thead><tr><th>Code</th><th>Title</th><th></th><th></th></tr></thead><tbody>';
            entries.forEach(function(entry) {
                const code = entry[0], title = entry[1];
                html += '<tr><td class="code-cell">' + escapeHtml(code) + '</td><td class="title-cell">' + escapeHtml(title) + '</td><td class="link-cell"><a href="index.php?mov=' + encodeURIComponent(code) + '" target="_blank">view</a></td><td><button class="btn-sm" data-del="' + escapeHtml(code) + '">Delete</button></td></tr>';
            });
            html += '</tbody></table>';
            listArea.innerHTML = html;
            listArea.querySelectorAll('[data-del]').forEach(function(btn) {
                btn.addEventListener('click', function() { deleteEntry(btn.dataset.del); });
            });
        }
        function escapeHtml(s) {
            const d = document.createElement('div');
            d.textContent = s;
            return d.innerHTML;
        }
        saveBtn.addEventListener('click', async function() {
            const code = codeEl.value.trim();
            const title = titleEl.value.trim();
            if (!code || !title) { showMsg('Please enter both a video code and a title.', 'err'); return; }
            saveBtn.disabled = true;
            saveBtn.textContent = 'SAVING...';
            try {
                const resp = await fetch(API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ code: code, title: title })
                });
                if (resp.ok) {
                    showMsg('Title saved successfully!', 'ok');
                    codeEl.value = '';
                    titleEl.value = '';
                    loadList();
                } else {
                    showMsg('Failed to save. Check folder data/ permission (755 or 775).', 'err');
                }
            } catch (e) {
                showMsg('Network error.', 'err');
            }
            saveBtn.disabled = false;
            saveBtn.textContent = 'SAVE TITLE';
        });
        async function deleteEntry(code) {
            try {
                const resp = await fetch(API_URL, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ code: code })
                });
                if (resp.ok) { showMsg('Deleted.', 'ok'); loadList(); }
            } catch (e) {
                showMsg('Delete failed.', 'err');
            }
        }
        loadList();
    </script>
</body>
</html>
