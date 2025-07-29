pipeline {
    agent {
        label '' // vide = agent principal, ou remplace par 'docker' si tu as un label
    }
    environment {
        COMPOSER_CACHE_DIR = "${WORKSPACE}/.composer"
        SYMFONY_ENV = 'test'
        PROJECT_DIR = '/workspace'
    }
    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))
        timestamps()
    }
    stages {
        stage('Prepare') {
            steps {
                dir("${env.PROJECT_DIR}") {
                    sh 'cp .env.test .env || true'
                }
            }
        }
        stage('Install dependencies') {
            steps {
                dir("${env.PROJECT_DIR}") {
                    sh 'composer install --no-interaction --prefer-dist'
                }
            }
        }
        stage('Lint YAML') {
            steps {
                dir("${env.PROJECT_DIR}") {
                    sh 'php bin/console lint:yaml config/'
                }
            }
        }
        stage('Doctrine Schema Validate') {
            steps {
                dir("${env.PROJECT_DIR}") {
                    sh 'php bin/console doctrine:schema:validate --skip-sync -vvv'
                }
            }
        }
        stage('Database migrations') {
            when { expression { fileExists("${env.PROJECT_DIR}/migrations") } }
            steps {
                dir("${env.PROJECT_DIR}") {
                    sh 'php bin/console doctrine:migrations:migrate --no-interaction'
                }
            }
        }
        stage('Static Analysis') {
            steps {
                dir("${env.PROJECT_DIR}") {
                    sh 'vendor/bin/phpstan analyse --error-format=checkstyle > var/tests/phpstan.xml || true'
                }
            }
        }
        stage('Tests') {
            steps {
                dir("${env.PROJECT_DIR}") {
                    sh './vendor/bin/phpunit --log-junit var/tests/junit.xml'
                }
            }
        }
    }
    post {
        always {
            dir("${env.PROJECT_DIR}") {
                junit 'var/tests/*.xml'
                // Analyse statique (Warnings Next Generation)
                recordIssues tools: [phpStan(pattern: 'var/tests/phpstan.xml')]
            }
        }
        failure {
            mail to: 'tonmail@domaine.com',
                 subject: "Le build Jenkins a échoué : ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                 body: "Vérifier Jenkins : ${env.BUILD_URL}"
        }
    }
}
