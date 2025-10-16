<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lancer un match aléatoire - Ping Pong</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .player-photo {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        margin-bottom: 10px;
        background: #eee;
    }
    </style>
</head>
<body>
    <div class="background-animation"></div>
    <div class="container">
        <header class="glass-card">
            <h1>🎲 Lancer un match aléatoire</h1>
            <p class="subtitle">Sélectionnez deux joueurs au hasard pour un match !</p>
            <a href="index.php" style="color:#fff;text-decoration:underline;">← Retour au classement</a>
        </header>
        <div class="glass-card" style="text-align:center;">
            <button class="btn-add" id="randomMatchBtn">Lancer un match aléatoire</button>
            <div id="matchResults" style="margin-top:40px;"></div>
        </div>
    </div>
    <script>
    let players = [];
    // Charger les joueurs
    fetch('api.php?action=getPlayers')
        .then(response => response.json())
        .then(data => {
            players = data;
        });

    document.getElementById('randomMatchBtn').onclick = function() {
    lancerPlusieursMatchs(5); // Lancer 5 matchs en même temps (10 joueurs = 5 matchs 1vs1)
    };

    // Fonction pour échapper le HTML (copiée de script.js)
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Déclarer le vainqueur (affiche un choix)
    function declareWinner(id1, id2) {
        const p1 = players.find(p => p.id == id1);
        const p2 = players.find(p => p.id == id2);
        document.getElementById('matchResult').innerHTML = `
            <div style="display:flex;justify-content:center;align-items:center;gap:40px;">
                <button class="btn-submit" onclick="saveMatch(${p1.id},${p2.id},${p1.id})">${escapeHtml(p1.name)} gagne</button>
                <span style="font-size:2rem;">ou</span>
                <button class="btn-submit" onclick="saveMatch(${p1.id},${p2.id},${p2.id})">${escapeHtml(p2.name)} gagne</button>
            </div>
        `;
    }

    // Nouvelle fonction pour lancer un match (réutilisable)
    // Lancer plusieurs matchs en même temps (par défaut 2)
    function lancerPlusieursMatchs(nbMatchs) {
        if (players.length < 2) {
            document.getElementById('matchResults').innerHTML = '<p>Pas assez de joueurs pour lancer un match.</p>';
            return;
        }
        // Créer une copie de la liste pour éviter de tirer deux fois le même joueur dans un match
        let matchs = [];
        for (let i = 0; i < nbMatchs; i++) {
            if (joueursDispo.length < 2) break;
            // Tirer deux joueurs différents
            let idx1 = Math.floor(Math.random() * joueursDispo.length);
            let player1 = joueursDispo.splice(idx1, 1)[0];
            let idx2 = Math.floor(Math.random() * joueursDispo.length);
            let player2 = joueursDispo.splice(idx2, 1)[0];
            matchs.push([player1, player2]);
        }
        let html = '';
        matchs.forEach((pair, i) => {
            const photo1 = pair[0].photo ? pair[0].photo : 'default-avatar.png';
            const photo2 = pair[1].photo ? pair[1].photo : 'default-avatar.png';
            html += `<div class="glass-card" style="margin-bottom:30px;">
                <div style="display:flex;justify-content:center;align-items:center;gap:40px;">
                    <div style="text-align:center;">
                        <img src="${photo1}" alt="photo ${escapeHtml(pair[0].name)}" class="player-photo" onerror="this.src='default-avatar.png'">
                        <div class="player-name" style="font-size:2rem;">${escapeHtml(pair[0].name)}</div>
                        <div class="player-points">${pair[0].points} pts</div>
                    </div>
                    <span style="font-size:2.5rem;">VS</span>
                    <div style="text-align:center;">
                        <img src="${photo2}" alt="photo ${escapeHtml(pair[1].name)}" class="player-photo" onerror="this.src='default-avatar.png'">
                        <div class="player-name" style="font-size:2rem;">${escapeHtml(pair[1].name)}</div>
                        <div class="player-points">${pair[1].points} pts</div>
                    </div>
                </div>
                <div style="margin-top:30px;display:flex;justify-content:center;gap:20px;">
                    <button class="btn-submit" onclick="declareWinnerMulti(${pair[0].id},${pair[1].id},${pair[0].id},${i})">${escapeHtml(pair[0].name)} gagne</button>
                    <button class="btn-submit" onclick="declareWinnerMulti(${pair[0].id},${pair[1].id},${pair[1].id},${i})">${escapeHtml(pair[1].name)} gagne</button>
                </div>
                <div id="resultMatch${i}" style="margin-top:15px;"></div>
            </div>`;
        });
        if (matchs.length === 0) {
            html = '<p>Pas assez de joueurs pour lancer plusieurs matchs.</p>';
        }
        html += '<button class="btn-add" style="margin-top:20px;" onclick="lancerPlusieursMatchs(' + nbMatchs + ')">Lancer de nouveaux matchs</button>';
        document.getElementById('matchResults').innerHTML = html;
    }

    // Déclarer le vainqueur pour un match parmi plusieurs
    function declareWinnerMulti(id1, id2, winnerId, matchIndex) {
        fetch('api.php?action=saveMatch', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id1, id2, winnerId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetch('api.php?action=getPlayers')
                    .then(response => response.json())
                    .then(data => { players = data; });
                document.getElementById('resultMatch'+matchIndex).innerHTML = '<span style="color:#4CAF50;">Résultat enregistré !</span>';
            } else {
                document.getElementById('resultMatch'+matchIndex).innerHTML = '<span style="color:#f44336;">Erreur lors de l\'enregistrement du match.</span>';
            }
        });
    }

    // Sauvegarder le résultat du match
    function saveMatch(id1, id2, winnerId) {
        fetch('api.php?action=saveMatch', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id1, id2, winnerId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recharger les joueurs pour les prochains matchs
                fetch('api.php?action=getPlayers')
                    .then(response => response.json())
                    .then(data => {
                        players = data;
                        document.getElementById('matchResult').innerHTML = '<p>Résultat enregistré !</p>' +
                            '<button class="btn-add" style="margin-top:20px;" onclick="lancerMatch()">Lancer un autre match</button>';
                    });
            } else {
                document.getElementById('matchResult').innerHTML = '<p>Erreur lors de l\'enregistrement du match.</p>';
            }
        });
    }
    </script>
</body>
</html>
