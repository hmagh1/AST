pipeline {
    agent any
    environment {
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
                sh 'composer install --no-interaction --prefer-dist'
            }
        }
        stage('Tests') {
            steps {
                sh 'composer run-script test'
            }
        }
    }
    post {
        always {
            junit allowEmptyResults: true, testResults: 'var/tests/junit.xml'
        }
        failure {
            mail to: 'tonmail@domaine.com',
                 subject: "Le build Jenkins a échoué : ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                 body: "Vérifiez le build Jenkins ici : ${env.BUILD_URL}"
        }
    }
}
