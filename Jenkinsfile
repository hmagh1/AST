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
            steps {
                sh "docker exec ${CONTAINER} php bin/console doctrine:migrations:migrate --no-interaction"
            }
        }
        stage('Static Analysis (PHPStan)') {
            steps {
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
            junit 'var/tests/*.xml'
            // Si tu as Warnings Next Generation pour PHPStan :
            // recordIssues tools: [phpStan(pattern: 'var/tests/phpstan.xml')]
        }
    }
}
