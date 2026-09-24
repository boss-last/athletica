# 🏃 Athletica - Plateforme Sport Connecté

[![Tests & Code Quality](https://github.com/boss-last/athletica/actions/workflows/tests.yml/badge.svg)](https://github.com/boss-last/athletica/actions/workflows/tests.yml)
![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.3-blue)
![Flutter](https://img.shields.io/badge/Flutter-3.44-purple)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-green)
![License](https://img.shields.io/badge/License-MIT-yellow)

## 📖 Description

**Athletica** est une plateforme complète de suivi sportif qui permet aux athlètes de :
- 📊 Suivre leurs performances avec des statistiques détaillées
- 🗺️ Visualiser leurs parcours GPS sur carte interactive
- 🔗 Synchroniser automatiquement leurs activités Strava
- 🎯 Se fixer des objectifs et participer à des challenges
- 🏅 Gagner des badges et progresser
- 📱 Accéder à leur compte depuis l'app mobile

---

## 🛠️ Stack Technique

| Catégorie | Technologies |
|-----------|--------------|
| **Backend** | Laravel 12, PHP 8.3 |
| **Base de données** | PostgreSQL (Supabase) |
| **Frontend Web** | Blade, Tailwind CSS, Chart.js, Leaflet.js |
| **Mobile** | Flutter 3.44, Dart |
| **API** | REST (51 routes), Laravel Sanctum |
| **Intégrations** | Strava OAuth, Stripe, Brevo SMTP |
| **Tests** | PHPUnit (50 tests) |
| **CI/CD** | GitHub Actions |

---

## ✨ Fonctionnalités

### 🔐 Authentification
- Inscription avec email
- Connexion par **code à 6 chiffres** envoyé par email
- Vérification email
- Réinitialisation mot de passe

### 📊 Dashboard
- Statistiques en temps réel (activités, km, heures, calories)
- Graphique répartition par sport (Chart.js)
- Objectifs avec barres de progression
- Activités récentes cliquables

### 🏃 Activités
- Liste paginée avec CRUD complet
- Formulaire de création manuelle
- Import fichier GPX
- **Carte GPS interactive** (Leaflet.js + OpenStreetMap)
- Graphiques FC / Vitesse / Altitude
- Likes et commentaires

### 🎯 Objectifs & Challenges
- Création d'objectifs personnalisés
- Suivi de progression en temps réel
- Challenges communautaires
- Classement 🥇🥈🥉

### 👤 Profil & Social
- Upload avatar
- Statistiques et records personnels
- Badges débloqués
- Réseau (abonnés/abonnements)
- Notifications

### 🔗 Intégrations
- **Strava** : Synchronisation OAuth automatique
- **Stripe** : Abonnements Premium
- **Brevo** : Emails transactionnels

### 👑 Admin
- Dashboard administrateur
- Gestion utilisateurs (CRUD)
- Gestion badges, challenges
- Notifications groupées
- Export CSV

---

## 📊 Statistiques du Projet

| Élément | Nombre |
|---------|--------|
| Contrôleurs | 18 |
| Modèles | 17 |
| Routes | 51 |
| Vues Blade | 21 + 8 admin |
| Écrans Flutter | 11 |
| Tables DB | 19 |
| Tests | 50 (100% passés) |

---

## 🚀 Installation

### Prérequis
- PHP 8.3+
- Composer
- PostgreSQL (via Supabase)
- Node.js 18+

### Étapes

```bash
# 1. Cloner le projet
git clone https://github.com/boss-last/athletica.git
cd athletica

# 2. Installer les dépendances
composer install
npm install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Configurer .env (Supabase, Strava, Stripe...)

# 5. Générer les assets
npm run build

# 6. Lancer le serveur
php artisan serve
🧪 Tests
bash
php artisan test
Résultat :

text
Tests:    50 passed (113 assertions)
Duration: 2.05s
Le CI/CD GitHub Actions lance automatiquement les tests à chaque push :

✅ PHPUnit Tests : 50 tests

✅ Code Style : Laravel Pint

📚 Documentation API
Documentation Swagger disponible après lancement :

text
http://127.0.0.1:8000/api/documentation

🗄️ Base de Données
19 tables sur Supabase :

users, athlete_profiles, platform_connections, activities, activity_gps_points, activity_comments, activity_likes, user_stats, goals, challenges, challenge_participants, follows, badges, user_badges, training_plans, training_sessions, fitness_metrics, notifications, personal_access_tokens

🔐 Sécurité
✅ Authentification Sanctum

✅ Protection CSRF sur tous les formulaires

✅ Validation côté serveur (Requests)

✅ Upload sécurisé (validation type/taille)

✅ OAuth 2.0 pour Strava

✅ Code de connexion à 6 chiffres

✅ HTTPS en production

📱 Application Mobile
Application Flutter disponible :

Android : APK (47.7 MB)

Web : flutter run -d chrome

🚀 Déploiement
Backend (Laravel)
bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
Mobile (Flutter)
bash
flutter build apk --release
flutter build appbundle --release
📄 Licence
Ce projet est sous licence MIT. Voir LICENSE pour plus de détails.

👨‍💻 Auteur
Christian Amara Bamba

📧 romansreimsroland@gmail.com
📍 Abidjan, Côte d'Ivoire 🇨🇮
