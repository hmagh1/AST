pipeline {
    agent any

    environment {
        PROJECT_DIR = '/var/www/html'    // Dossier dans le conteneur PHP (pas sur l'hôte !)
        CONTAINER   = 'astreinte-php'    // Le nom du conteneur PHP de docker-compose
    }

    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))
        timestamps()
    }

    stages {
        stage('Prepare .env') {
            steps {
                // Copie le fichier .env.test sur .env si tu veux des tests séparés
                sh "docker exec ${CONTAINER} cp .env.test .env"
            }
        }
        stage('Install dependencies') {
            steps {
                sh "docker exec ${CONTAINER} composer install --no-interaction --prefer-dist"
            }
        }
        stage('Lint YAML') {
            steps {
                sh "docker exec ${CONTAINER} php bin/console lint:yaml config/"
            }
        }
        stage('Doctrine Schema Validate') {
            steps {
                sh "docker exec ${CONTAINER} php bin/console doctrine:schema:validate --skip-sync -vvv"
            }
        }
        stage('Database migrations') {
            when {
                expression {
                    // Vérifie si le dossier migrations existe dans le conteneur
                    sh(script: "docker exec ${CONTAINER} test -d ${PROJECT_DIR}/migrations", returnStatus: true) == 0
                }
            }
            steps {
                sh "docker exec ${CONTAINER} php bin/console doctrine:migrations:migrate --no-interaction"
            }
        }
        stage('Static Analysis (PHPStan)') {
            steps {
                // PHPStan doit être installé dans ton projet (require-dev)
                sh "docker exec ${CONTAINER} vendor/bin/phpstan analyse --error-format=checkstyle > var/tests/phpstan.xml || true"
            }
        }
        stage('Tests') {
            steps {
                sh "docker exec ${CONTAINER} ./vendor/bin/phpunit --log-junit var/tests/junit.xml"
            }
        }
    }
    post {
        always {
            // Rapports JUnit
            junit 'var/tests/*.xml'
            // (Optionnel) Si tu utilises le plugin Warnings Next Generation avec PHPStan
            recordIssues tools: [phpStan(pattern: 'var/tests/phpstan.xml')]
        }
        failure {
            mail to: 'tonmail@domaine.com',
                 subject: "Le build Jenkins a échoué : ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                 body: "Vérifier Jenkins : ${env.BUILD_URL}"
        }
    }
}
