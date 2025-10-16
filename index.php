<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement Ping Pong</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="background-animation"></div>
    
    <div class="container">
        <header class="glass-card">
            <h1>🏓 Classement Ping Pong</h1>
            <p class="subtitle">Tableau de classement des joueurs</p>
            <a href="match.php" class="btn-add" style="display:inline-block;margin-top:18px;font-size:1rem;padding:10px 30px;">🎲 Lancer un match aléatoire</a>
        </header>

        <div class="controls glass-card">
            <button class="btn-add" onclick="showAddPlayerModal()">
                <span>+</span> Ajouter un joueur
            </button>
        </div>

        <div class="ranking-container">
            <div id="playersList" class="players-list"></div>
        </div>
    </div>

    <!-- Modal pour ajouter/modifier un joueur -->
    <div id="playerModal" class="modal">
        <div class="modal-content glass-card">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Ajouter un joueur</h2>
            <form id="playerForm">
                <input type="hidden" id="playerId" name="id">
                <div class="form-group">
                    <label for="playerName">Nom du joueur</label>
                    <input type="text" id="playerName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="playerPoints">Points</label>
                    <input type="number" id="playerPoints" name="points" value="0" required>
                </div>
                <div class="form-group">
                    <label for="playerWins">Victoires</label>
                    <input type="number" id="playerWins" name="wins" value="0" required>
                </div>
                <div class="form-group">
                    <label for="playerLosses">Défaites</label>
                    <input type="number" id="playerLosses" name="losses" value="0" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Enregistrer</button>
                    <button type="button" class="btn-cancel" onclick="closeModal()">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
