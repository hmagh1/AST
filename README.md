# Astreinte API

## Overview

A RESTful API built with Symfony 5.4 to manage astreinte operations: administrators, on-call staff (astreignables), HR (DRH), main logs (main courantes), service records, and schedules.

- **Technology stack:** PHP 7.4, Symfony 5.4, MySQL, Docker, Jenkins CI/CD
- **100% Dockerized** — no need to install PHP/MySQL/Composer locally!
- Includes unit/integration tests, SonarQube analysis, and automatic deployment (see below).

---

## ⚡ Quick Start (with Docker)

**1. Prerequisite:**  
- [Install Docker](https://www.docker.com/get-started) (Desktop for Windows/Mac/Linux).

**2. Clone the repository**
```bash
git clone <repo-url> astreinte-back
cd astreinte-back
```

**3. Start all services (PHP + Apache + MySQL)**
```bash
docker compose up -d
```
- Le backend Symfony tourne sur [http://localhost:8001](http://localhost:8001)  
- La base de données MySQL est exposée sur `localhost:3306` (voir `.env`)

**4. Installer les dépendances PHP, créer la base, appliquer les migrations**  
Tout s’exécute dans le conteneur :
```bash
docker exec astreinte-php composer install
docker exec astreinte-php php bin/console doctrine:database:create --env=dev
docker exec astreinte-php php bin/console doctrine:migrations:migrate --env=dev
```
**5. (Optionnel) Charger des données de test**
```bash
docker exec astreinte-php php bin/console doctrine:fixtures:load --env=dev
```

---

## Utilisation **SANS** dépendances locales

- **Aucune version de PHP, Composer, MySQL n’est requise sur votre PC.**
- Vous exécutez toutes les commandes dans le conteneur Docker via :
  ```bash
  docker exec astreinte-php <commande>
  ```
- **Peu importe votre version locale de PHP/MySQL** : seule celle dans Docker est utilisée (voir `Dockerfile`).

---

## 📦 API Endpoints

Pour voir toutes les routes :
```bash
docker exec astreinte-php php bin/console debug:router
```
| Entity         | Base URL             | Example Endpoint         |
|----------------|----------------------|--------------------------|
| Admins         | `/api/admins`        | `GET /api/admins`        |
| Astreignables  | `/api/astreignables` | `POST /api/astreignables`|
| DRHs           | `/api/drhs`          | `GET /api/drhs/1`        |
| Main Courantes | `/api/maincourantes` | `DELETE /api/maincourantes/1` |
| Plannings      | `/api/plannings`     | `PUT /api/plannings/1`   |
| Services       | `/api/services`      | `GET /api/services`      |
| Exchanges      | `/api/exchanges`     | `POST /api/exchanges`    |
| PlanningAst    | `/api/planning_astreinte` | `GET /api/planning_astreinte` |

---

## 🏗️ Project Structure

| Folder                         | Purpose                                |
|--------------------------------|----------------------------------------|
| `src/Entity/`                  | Doctrine entities (business objects)   |
| `src/Controller/`              | API controllers                        |
| `src/Repository/`              | Doctrine repositories (DB access)      |
| `src/Command/`                 | Symfony console commands               |
| `config/`                      | App configuration                      |
| `tests/AllUnitTests.php`       | Unit tests                             |
| `tests/AllIntegrationTests.php`| Integration tests                      |

---

## 🧪 Testing

**Unit tests :**
```bash
docker exec astreinte-php ./vendor/bin/phpunit --testsuite=Unit
```

**Integration tests :**
```bash
docker exec astreinte-php php bin/console doctrine:database:create --env=test --if-not-exists
docker exec astreinte-php php bin/console doctrine:schema:update --force --env=test
docker exec astreinte-php ./vendor/bin/phpunit --testsuite=Integration
```

---

## 🔁 CI/CD (Jenkins)

La pipeline Jenkins effectue automatiquement :
- L’installation des dépendances
- Le lint (YAML)
- La validation du schéma Doctrine
- Les migrations
- Les tests unitaires & d’intégration
- L’analyse statique (PHPStan)
- La couverture de code (SonarQube)
- Le déploiement automatique (voir section suivante)

---

## 🚀 Déploiement (Production)

> **CI/CD peut être configuré pour déployer automatiquement le conteneur Docker sur Azure (ou tout autre cloud)** si tous les tests passent.

**Exemple de pipeline de déploiement** (à adapter pour Azure VM, App Service, etc.) :  
Le Jenkinsfile inclut une étape “deploy” qui push l’image Docker ou déclenche un script de déploiement distant.

---

## 🌍 CORS

Géré via le bundle **nelmio/cors-bundle** (voir `config/packages/nelmio_cors.yaml`) :

```yaml
nelmio_cors:
    defaults:
        origin_regex: true
        allow_origin: ['%env(CORS_ALLOW_ORIGIN)%']
        allow_methods: ['GET', 'OPTIONS', 'POST', 'PUT', 'PATCH', 'DELETE']
        allow_headers: ['Content-Type', 'Authorization']
        expose_headers: ['Link']
        max_age: 3600
    paths:
        '^/': null
```

---

## 🛠️ Variables d’environnement (par défaut dans `.env`)

```
KERNEL_CLASS=App\Kernel
APP_SECRET=$ecretf0rt3st
SYMFONY_DEPRECATIONS_HELPER=999999
PANTHER_APP_ENV=panther
PANTHER_ERROR_SCREENSHOT_DIR=./var/error-screenshots
DATABASE_URL=mysql://astreinte:astreinte@db:3306/astreinte
```
À personnaliser dans `.env` ou `.env.local` pour les environnements prod/staging.

---

## 🔄 Migration test → prod

Pour copier les données validées du test vers la prod :

```bash
docker exec astreinte-php php bin/console app:import-tested-data
```
Commande : `src/Command/ImportTestedDataCommand.php`

---

## ❓ FAQ

- **Q : Le projet marche-t-il si j’ai PHP 8 (ou une autre version) sur mon PC ?**  
  **R : Oui !** Grâce à Docker, seule la version définie dans le projet (PHP 7.4) sera utilisée dans tous les cas.

- **Q : Je n’ai pas MySQL ou Composer localement, c’est grave ?**  
  **R : Non, rien à installer localement, Docker gère tout pour vous.**

---

## License

Proprietary – for internal use only.

---
