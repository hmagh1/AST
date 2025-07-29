pipeline {
    agent any
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
            steps { checkout scm }
        }
        stage('Prepare env file') {
            steps {
                // Utilise .env.test pour les tests
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
        stage('Tests') {
            steps {
                // Génère un rapport JUnit utilisable par Jenkins
                sh './vendor/bin/phpunit --log-junit var/tests/junit.xml'
            }
        }
    }
    post {
        always {
            // Rapports de tests pour Jenkins (chemin adapté à ta structure)
            junit 'var/tests/*.xml'
        }
        failure {
            mail to: 'tonmail@domaine.com',
                 subject: "Le build Jenkins a échoué : ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                 body: "Vérifier Jenkins : ${env.BUILD_URL}"
        }
    }
}
