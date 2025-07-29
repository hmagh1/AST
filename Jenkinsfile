pipeline {
    agent {
        dockerfile {
            filename 'Dockerfile'      // Le nom du Dockerfile à la racine
            dir '.'                    // Répertoire de build context (ici racine)
        }
    }
    environment {
        COMPOSER_CACHE_DIR = "${WORKSPACE}/.composer"
        SYMFONY_ENV = 'test'
    }
    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))
        timestamps()
    }
    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }
        stage('Prepare env file') {
            steps {
                sh 'cp .env.test .env'
            }
        }
        stage('Install dependencies') {
            steps {
                sh 'composer install --no-interaction --prefer-dist'
            }
        }
        stage('Lint YAML') {
            steps {
                sh 'php bin/console lint:yaml config/'
            }
        }
        stage('Doctrine Schema Validate') {
            steps {
                sh 'php bin/console doctrine:schema:validate --skip-sync -vvv'
            }
        }
        stage('Database migrations') {
            when { expression { fileExists('migrations') } }
            steps {
                sh 'php bin/console doctrine:migrations:migrate --no-interaction'
            }
        }
        stage('Static Analysis') {
            steps {
                // Ajoute PHPStan si tu l’utilises (sinon tu peux commenter)
                sh 'vendor/bin/phpstan analyse --error-format=checkstyle > var/tests/phpstan.xml || true'
            }
        }
        stage('Tests') {
            steps {
                sh './vendor/bin/phpunit --log-junit var/tests/junit.xml'
            }
        }
    }
    post {
        always {
            junit 'var/tests/*.xml'
            // Analyse statique (Warnings Next Generation)
            recordIssues tools: [phpStan(pattern: 'var/tests/phpstan.xml')]
        }
        failure {
            mail to: 'tonmail@domaine.com',
                subject: "Le build Jenkins a échoué : ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: "Vérifier Jenkins : ${env.BUILD_URL}"
        }
    }
}
