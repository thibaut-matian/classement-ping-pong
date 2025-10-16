<?php
header('Content-Type: application/json');

// Fichier de base de données JSON
$dbFile = 'players.json';

// Initialiser le fichier s'il n'existe pas
if (!file_exists($dbFile)) {
    file_put_contents($dbFile, json_encode([]));
}

// Lire les données
function readData() {
    global $dbFile;
    $data = file_get_contents($dbFile);
    return json_decode($data, true);
}

// Écrire les données
function writeData($data) {
    global $dbFile;
    file_put_contents($dbFile, json_encode($data, JSON_PRETTY_PRINT));
}

// Trier les joueurs par points décroissants
function sortPlayers($players) {
    usort($players, function($a, $b) {
        if ($a['points'] == $b['points']) {
            return $b['wins'] - $a['wins'];
        }
        return $b['points'] - $a['points'];
    });
    return $players;
}

// Récupérer l'action
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'getPlayers':
        $players = readData();
        $players = sortPlayers($players);
        echo json_encode($players);
        break;
    
    case 'getPlayer':
        $id = $_GET['id'] ?? 0;
        $players = readData();
        foreach ($players as $player) {
            if ($player['id'] == $id) {
                echo json_encode($player);
                exit;
            }
        }
        echo json_encode(['error' => 'Joueur non trouvé']);
        break;
    
    case 'addPlayer':
        $input = json_decode(file_get_contents('php://input'), true);
        $players = readData();
        
        // Générer un nouvel ID
        $maxId = 0;
        foreach ($players as $player) {
            if ($player['id'] > $maxId) {
                $maxId = $player['id'];
            }
        }
        
        $newPlayer = [
            'id' => $maxId + 1,
            'name' => htmlspecialchars($input['name'] ?? ''),
            'points' => intval($input['points'] ?? 0),
            'wins' => intval($input['wins'] ?? 0),
            'losses' => intval($input['losses'] ?? 0)
        ];
        
        $players[] = $newPlayer;
        writeData($players);
        
        echo json_encode(['success' => true, 'player' => $newPlayer]);
        break;
    
    case 'updatePlayer':
        $input = json_decode(file_get_contents('php://input'), true);
        $players = readData();
        
        $updated = false;
        foreach ($players as &$player) {
            if ($player['id'] == $input['id']) {
                $player['name'] = htmlspecialchars($input['name'] ?? $player['name']);
                $player['points'] = intval($input['points'] ?? $player['points']);
                $player['wins'] = intval($input['wins'] ?? $player['wins']);
                $player['losses'] = intval($input['losses'] ?? $player['losses']);
                $updated = true;
                break;
            }
        }
        
        if ($updated) {
            writeData($players);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Joueur non trouvé']);
        }
        break;
    
    case 'deletePlayer':
        $input = json_decode(file_get_contents('php://input'), true);
        $players = readData();
        
        $players = array_filter($players, function($player) use ($input) {
            return $player['id'] != $input['id'];
        });
        
        $players = array_values($players); // Réindexer le tableau
        writeData($players);
        
        echo json_encode(['success' => true]);
        break;
    
    case 'saveMatch':
        $input = json_decode(file_get_contents('php://input'), true);
        $id1 = intval($input['id1'] ?? 0);
        $id2 = intval($input['id2'] ?? 0);
        $winnerId = intval($input['winnerId'] ?? 0);
        $players = readData();
        $found1 = $found2 = false;
        foreach ($players as &$player) {
            if ($player['id'] == $id1 || $player['id'] == $id2) {
                if ($player['id'] == $winnerId) {
                    $player['wins'] = intval($player['wins']) + 1;
                    $player['points'] = intval($player['points']) + 20;
                } else {
                    $player['losses'] = intval($player['losses']) + 1;
                    $player['points'] = max(0, intval($player['points']) - 10);
                }
                if ($player['id'] == $id1) $found1 = true;
                if ($player['id'] == $id2) $found2 = true;
            }
        }
        unset($player);
        if ($found1 && $found2) {
            writeData($players);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Joueur non trouvé']);
        }
        break;
    default:
        echo json_encode(['error' => 'Action non reconnue']);
        break;
}
?>
