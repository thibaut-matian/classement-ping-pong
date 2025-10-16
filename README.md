# 🏓 Site de Classement Ping Pong

Un site web élégant pour gérer et afficher un classement de ping pong avec un design **Liquid Glass** chaleureux.

## ✨ Fonctionnalités

- 📊 **Classement évolutif** : Affichage des joueurs triés par points
- ➕ **Ajout de joueurs** : Interface simple pour ajouter de nouveaux joueurs
- ✏️ **Modification** : Édition des informations des joueurs
- 🗑️ **Suppression** : Retrait de joueurs du classement
- 🏆 **Statistiques** : Affichage des victoires, défaites et taux de victoire
- 🎨 **Design Liquid Glass** : Interface moderne avec effets de verre et animations

## 🛠️ Technologies utilisées

- **HTML5** : Structure de la page
- **CSS3** : Design Liquid Glass avec animations
- **JavaScript** : Interactivité et gestion des données
- **PHP** : API backend pour la persistance des données
- **JSON** : Stockage des données

## 📦 Installation

1. Placez tous les fichiers dans votre dossier `www/ping pong` de WAMP
2. Assurez-vous que WAMP est démarré
3. Accédez à `http://localhost/ping pong/` dans votre navigateur

## 🎯 Utilisation

1. **Ajouter un joueur** : Cliquez sur le bouton "+ Ajouter un joueur"
2. **Modifier un joueur** : Cliquez sur l'icône ✏️ sur la carte du joueur
3. **Supprimer un joueur** : Cliquez sur l'icône 🗑️ sur la carte du joueur
4. Le classement se met à jour automatiquement selon les points

## 📁 Structure des fichiers

```
ping pong/
├── index.php       # Page principale
├── style.css       # Styles Liquid Glass
├── script.js       # Logique JavaScript
├── api.php         # API PHP backend
├── players.json    # Base de données JSON
└── README.md       # Ce fichier
```

## 🎨 Personnalisation

Vous pouvez personnaliser les couleurs dans `style.css` en modifiant les variables CSS :

```css
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --warm-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
```

## 🚀 Fonctionnalités futures possibles

- Historique des matchs
- Graphiques de progression
- Système de tournois
- Authentification utilisateur
- Export des données

---

Créé avec ❤️ et du code
# classement-ping-pong
