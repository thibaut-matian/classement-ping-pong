// Charger les joueurs au démarrage
document.addEventListener('DOMContentLoaded', function() {
    loadPlayers();
});

// Charger la liste des joueurs
function loadPlayers() {
    fetch('api.php?action=getPlayers')
        .then(response => response.json())
        .then(data => {
            displayPlayers(data);
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

// Afficher les joueurs
function displayPlayers(players) {
    const playersList = document.getElementById('playersList');
    playersList.innerHTML = '';
    
    if (players.length === 0) {
        playersList.innerHTML = '<div class="glass-card" style="text-align: center; padding: 40px;"><p>Aucun joueur dans le classement. Ajoutez-en un !</p></div>';
        return;
    }
    
    players.forEach((player, index) => {
        const rank = index + 1;
        let rankClass = '';
        if (rank === 1) rankClass = 'gold';
        else if (rank === 2) rankClass = 'silver';
        else if (rank === 3) rankClass = 'bronze';
        
        const winRate = player.wins + player.losses > 0 
            ? ((player.wins / (player.wins + player.losses)) * 100).toFixed(0)
            : 0;
        
        const playerCard = document.createElement('div');
        playerCard.className = 'player-card';
        playerCard.innerHTML = `
            <div class="rank ${rankClass}">${rank}</div>
            <div class="player-info">
                <div class="player-name">${escapeHtml(player.name)}</div>
                <div class="player-stats">
                    <span>🏆 ${player.wins}V</span>
                    <span>❌ ${player.losses}D</span>
                    <span>📊 ${winRate}%</span>
                </div>
            </div>
            <div class="player-points">${player.points} pts</div>
            <div class="player-actions">
                <button class="btn-icon btn-edit" onclick="editPlayer(${player.id})" title="Modifier">✏️</button>
                <button class="btn-icon btn-delete" onclick="deletePlayer(${player.id})" title="Supprimer">🗑️</button>
            </div>
        `;
        playersList.appendChild(playerCard);
    });
}

// Afficher le modal d'ajout
function showAddPlayerModal() {
    document.getElementById('modalTitle').textContent = 'Ajouter un joueur';
    document.getElementById('playerForm').reset();
    document.getElementById('playerId').value = '';
    document.getElementById('playerModal').style.display = 'block';
}

// Modifier un joueur
function editPlayer(id) {
    fetch(`api.php?action=getPlayer&id=${id}`)
        .then(response => response.json())
        .then(player => {
            document.getElementById('modalTitle').textContent = 'Modifier le joueur';
            document.getElementById('playerId').value = player.id;
            document.getElementById('playerName').value = player.name;
            document.getElementById('playerPoints').value = player.points;
            document.getElementById('playerWins').value = player.wins;
            document.getElementById('playerLosses').value = player.losses;
            document.getElementById('playerModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement du joueur');
        });
}

// Supprimer un joueur
function deletePlayer(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?')) {
        fetch('api.php?action=deletePlayer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadPlayers();
            } else {
                alert('Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}

// Fermer le modal
function closeModal() {
    document.getElementById('playerModal').style.display = 'none';
}

// Fermer le modal en cliquant à l'extérieur
window.onclick = function(event) {
    const modal = document.getElementById('playerModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Soumettre le formulaire
document.getElementById('playerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        id: formData.get('id'),
        name: formData.get('name'),
        points: parseInt(formData.get('points')),
        wins: parseInt(formData.get('wins')),
        losses: parseInt(formData.get('losses'))
    };
    
    const action = data.id ? 'updatePlayer' : 'addPlayer';
    
    fetch(`api.php?action=${action}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            closeModal();
            loadPlayers();
        } else {
            alert('Erreur lors de l\'enregistrement');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'enregistrement');
    });
});

// Échapper le HTML pour éviter les injections XSS
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
