pipeline {
    agent any
    environment {
        PROJECT_DIR = '/var/www/html'
        CONTAINER = 'astreinte-php'
    }
    stages {
        stage('Prepare .env') {
            steps {
                sh "docker exec ${CONTAINER} cp .env.test .env"
            }
        }
        stage('Install dependencies') {
            steps {
                sh "docker exec ${CONTAINER} composer install --no-interaction --prefer-dist --no-scripts"
            }
        }
        stage('Run Symfony auto-scripts') {
            steps {
                sh "docker exec ${CONTAINER} bash -c 'export COMPOSER_PROCESS_TIMEOUT=900 && composer run-script auto-scripts'"
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
            steps {
                sh "docker exec ${CONTAINER} php bin/console doctrine:migrations:migrate --no-interaction"
            }
        }
        stage('Static Analysis (PHPStan)') {
            steps {
                sh "mkdir -p var/tests"
                sh "docker exec ${CONTAINER} vendor/bin/phpstan analyse --error-format=checkstyle > var/tests/phpstan.xml"
            }
        }
        stage('Tests') {
            steps {
                sh "mkdir -p var/tests"
                sh "docker exec ${CONTAINER} ./vendor/bin/phpunit --log-junit var/tests/junit.xml"
            }
        }
    }
    post {
        always {
            junit 'var/tests/*.xml'
            // recordIssues tools: [phpStan(pattern: 'var/tests/phpstan.xml')]
        }
    }
}
