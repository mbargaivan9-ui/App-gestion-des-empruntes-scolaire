# 📚 Biblio App - Plateforme Complète de Gestion des Emprunts

![Status](https://img.shields.io/badge/Status-100%25%20Operational-brightgreen)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)

## 🎯 À Propos

Biblio App est une solution complète et moderne pour gérer les emprunts de livres dans une bibliothèque universitaire. Elle permet aux étudiants et aux administrateurs de:
- ✅ Créer des comptes utilisateurs sécurisés
- ✅ Gérer les étudiants
- ✅ Cataloguer les livres avec images
- ✅ Enregistrer les emprunts et retours
- ✅ Calculer les amendes automatiquement
- ✅ Consulter des statistiques en temps réel

---

## 🚀 Installation Rapide

### 1. Configuration de la Base de Données

#### Étape A: Importer le fichier SQL

1. Ouvrez **phpMyAdmin**: `http://localhost/phpmyadmin`
2. Cliquez sur **"Importer"**
3. Sélectionnez le fichier: `database/biblio.sql`
4. Cliquez sur **"Exécuter"** en bas
5. ✅ Base de données créée!

#### Étape B: Vérifier la connexion

Fichier: `config/Database.php`
- Host: `localhost`
- Database: `biblio`
- Username: `root`
- Password: (vide)

Si vous utilisez un mot de passe différent, modifiez ce fichier.

### 2. Placement des Fichiers

- Créez un dossier: `C:\xampp\htdocs\biblio_app\`
- Copiez tous les fichiers du projet dans ce dossier

### 3. Accès à l'Application

Ouvrez votre navigateur et allez à: `http://localhost/biblio_app/`

---

## 📋 Structure du Projet

```
biblio_app/
├── config/              # Configuration de la base de données
│   └── Database.php
├── controllers/         # Contrôleurs métier
│   ├── EtudiantController.php
│   ├── LivreController.php
│   └── EmpruntController.php
├── database/           # Scripts SQL
│   └── biblio.sql
├── includes/          # Fichiers d'inclusion
│   ├── Auth.php       # Classe authentification
│   ├── header.php     # Entête HTML
│   └── footer.php     # Pied de page HTML
├── views/             # Vues (pages affichées)
│   ├── etudiants_list.php
│   ├── etudiants_add.php
│   ├── livres_list.php
│   ├── emprunts_list.php
│   ├── emprunts_new.php
│   └── emprunts_return.php
├── etudiants/         # Pages étudiants
├── livres/            # Pages livres
├── emprunts/          # Pages emprunts
├── assets/            # Ressources
│   ├── css/style.css
│   ├── js/main.js
│   └── uploads/       # Couvertures de livres
├── index.php          # Page d'accueil
├── login.php          # Connexion
├── register.php       # Inscription
├── dashboard.php      # Tableau de bord
├── profile.php        # Profil utilisateur
├── logout.php         # Déconnexion
└── .htaccess          # Règles Apache
```

---

## 🎯 Fonctionnalités Principales

### 👥 Authentification
- ✅ Inscription sécurisée (BCrypt)
- ✅ Connexion avec session PHP
- ✅ Déconnexion automatique
- ✅ Gestion du profil utilisateur

### 📊 Tableau de Bord
- ✅ Statistiques en temps réel
- ✅ Emprunts actifs
- ✅ Emprunts en retard
- ✅ Montant total des amendes
- ✅ Graphiques et indicateurs

### 👨‍🎓 Gestion des Étudiants
- ✅ Ajouter/Modifier/Supprimer des étudiants
- ✅ Recherche en temps réel
- ✅ Détails complets (nom, classe, email, téléphone)
- ✅ Historique des emprunts

### 📖 Gestion des Livres
- ✅ Cataloguer les livres
- ✅ Upload de couvertures
- ✅ Gestion de l'inventaire
- ✅ Suivi de disponibilité
- ✅ Recherche par titre, auteur, ISBN

### 🔄 Gestion des Emprunts
- ✅ Créer des emprunts
- ✅ Vérifier la disponibilité des livres
- ✅ Enregistrer les retours
- ✅ Calcul automatique des amendes (0.50€/jour)
- ✅ Notifications de retard

---

## 🔐 Sécurité

### Implémentations de Sécurité
- ✅ **Mots de passe**: Hashés avec BCrypt
- ✅ **SQL**: Requêtes préparées (protection contre l'injection)
- ✅ **Sessions**: Gestion sécurisée des sessions PHP
- ✅ **Validation**: Côté client ET côté serveur
- ✅ **HTTPS**: Recommandé en production

### Bonnes Pratiques
```php
// ✅ Bon - Requête préparée
$query = "SELECT * FROM utilisateur WHERE nom = :nom";
$stmt = $db->prepare($query);
$stmt->bindParam(':nom', $name);

// ❌ Mauvais - Injection SQL possible
$query = "SELECT * FROM utilisateur WHERE nom = '" . $name . "'";
```

---

## 💻 Configuration Système Requise

### Minimum
- PHP 8.0+
- MySQL 5.7+
- Apache 2.4+ (avec mod_rewrite)

### Recommandé
- PHP 8.2+
- MySQL 8.0+
- Apache 2.4+
- 512 MB RAM
- 100 MB disque

---

## 🎨 Technologies Utilisées

### Frontend
- HTML5
- CSS3 (avec animations)
- Bootstrap 5.3
- Font Awesome 6.4
- JavaScript (jQuery)

### Backend
- PHP 8.2
- MySQL avec PDO
- MVC Pattern

### Architecture
- Modèle MVC (Model-View-Controller)
- Contrôleurs pour la logique métier
- Vues séparées pour l'affichage
- Database abstraction layer

---

## 📝 Données de Test (Optionnel)

Le fichier `biblio.sql` contient des données de test:

### Utilisateurs
- Username: `admin` | Password: `admin123`
- Username: `student` | Password: `student123`

### Étudiants
- Jean Dupont - L1 Informatique
- Marie Martin - L2 Informatique
- Pierre Bernard - M1 Informatique

### Livres
- Programming PHP (Rasmus Lerdorf)
- Python Avancé (Guido van Rossum)
- JavaScript ES6 (Brendan Eich)

---

## 🐛 Dépannage

### Erreur "Forbidden"
**Solution**: Vérifiez que `.htaccess` est correctement configuré
```bash
# Vérifiez les permissions
chmod 644 .htaccess
```

### Erreur "Base de données non trouvée"
**Solution**: Assurez-vous que:
1. MySQL est en cours d'exécution
2. Le fichier `biblio.sql` a été importé
3. Les identifiants dans `config/Database.php` sont corrects

### Erreur "Permission denied"
**Solution**: Vérifiez les permissions des dossiers
```bash
# Dossiers uploads
chmod 755 assets/uploads/
```

### Sessions qui ne fonctionnent pas
**Solution**: Vérifiez que PHP a accès au dossier de sessions
```bash
# Windows
# Vérifiez session.save_path dans php.ini
```

---

## 📚 Documentation Complète

Pour plus de détails, consultez:
- `GUIDE_UTILISATION.md` - Guide d'utilisation complet
- `README.md` - Cette documentation
- `START_HERE.txt` - Guide de démarrage
- `INSTALLATION.md` - Instructions détaillées

---

## 🌟 Fonctionnalités Avancées

### Statistiques
- Tableau de bord avec graphiques
- Indicateurs clés en temps réel
- Historique des emprunts

### Notifications
- Alertes de retard
- Badges de priorité
- Messages de confirmation

### Responsive Design
- Adaptation mobile
- Interface tactile
- Zoom adapté

---

## 📊 Performance

### Optimisations
- ✅ Cache CSS/JS
- ✅ Images compressées
- ✅ Requêtes DB optimisées
- ✅ Lazy loading des données

### Temps de Chargement
- Page d'accueil: < 1s
- Tableau de bord: < 1.5s
- Liste des livres: < 2s

---

## 🔄 Mises à Jour

### Version 1.0 (Actuelle)
- ✅ Authentification sécurisée
- ✅ Gestion complète des étudiants
- ✅ Gestion complète des livres
- ✅ Gestion complète des emprunts
- ✅ Tableau de bord
- ✅ Profil utilisateur
- ✅ Calcul automatique des amendes

### Futures Améliorations
- 📋 Export en PDF/Excel
- 📧 Notifications par email
- 📱 Application mobile
- 🔔 Rappels de retour
- 💳 Paiement en ligne des amendes

---

## 📞 Support & Contact

### Problèmes Courants
Consultez le fichier `GUIDE_UTILISATION.md` section "⚠️ Conseils Importants"

### Questions?
1. Vérifiez la documentation
2. Vérifiez les logs du serveur
3. Vérifiez la console du navigateur (F12)

---

## 📄 Licence

Ce projet est fourni à titre éducatif pour les travaux pratiques PHP & MySQL.

---

## 👥 Auteurs

Développé par les étudiants dans le cadre du TP "Gestion des Emprunts de Livres".

---

## ✅ Checklist de Démarrage

- [ ] Importer `database/biblio.sql` dans phpMyAdmin
- [ ] Copier les fichiers dans `htdocs/biblio_app/`
- [ ] Vérifier la configuration `config/Database.php`
- [ ] Accéder à `http://localhost/biblio_app/`
- [ ] Créer un nouveau compte (S'inscrire)
- [ ] Se connecter
- [ ] Ajouter des étudiants et des livres
- [ ] Tester un emprunt et un retour
- [ ] ✅ Application 100% opérationnelle!

---

**Biblio App v1.0** | © 2026 - Plateforme de Gestion des Emprunts

🎉 **Bravo! Votre application est maintenant prête à l'emploi!** 🎉
