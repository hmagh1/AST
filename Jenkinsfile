pipeline {
    agent any
    environment {
        // Variables d'environnement si besoin
        COMPOSER_CACHE_DIR = "${WORKSPACE}/.composer"
    }
    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }
        stage('Install dependencies') {
            steps {
                sh 'composer install --no-interaction'
            }
        }
        stage('Lint') {
            steps {
                sh 'composer run-script lint' // Si tu as un script lint dans composer.json
            }
        }
        stage('Tests') {
            steps {
                sh 'composer run-script test' // Idem pour tes tests
            }
        }
        // Ajoute d’autres étapes (build front, deploy, etc.) si tu veux
    }
    post {
        always {
            junit '**/var/tests/**/*.xml' // Change selon où PHPUnit sort les reports
        }
        failure {
            mail to: 'tonmail@domaine.com',
                subject: "Le build Jenkins a échoué : ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: "Vérifier Jenkins : ${env.BUILD_URL}"
        }
    }
}
