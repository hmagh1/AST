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
        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('LocalSonar') {  // Mets le nom correct de ton Sonar ici
                    sh "docker exec ${CONTAINER} ./vendor/bin/phpunit --coverage-clover build/logs/clover.xml || true"
                    sh "docker exec ${CONTAINER} sonar-scanner -Dsonar.projectKey=astreinte -Dsonar.php.coverage.reportPaths=build/logs/clover.xml"
                }
            }
        }
        stage('Static Analysis (PHPStan)') {
            steps {
                sh "mkdir -p var/tests"
                sh "docker exec ${CONTAINER} vendor/bin/phpstan analyse --error-format=checkstyle > var/tests/phpstan.xml || true"
            }
        }
        stage('Tests') {
            steps {
                sh "mkdir -p var/tests"
                sh "docker exec ${CONTAINER} ./vendor/bin/phpunit --log-junit var/tests/junit.xml"
            }
        }
        stage('Deploy to Azure VM') {
            when {
                branch 'main' // déploie seulement sur main
            }
            steps {
                sshagent(['jenkins-azure-key']) {
                    sh '''
                    ssh -o StrictHostKeyChecking=no azureuser@4.178.177.191 '
                        cd ~/AST && git pull origin main && docker-compose down && docker-compose up -d --build
                    '
                    '''
                }
            }
        }
    }
    post {
        always {
            sh "docker cp ${CONTAINER}:/var/www/html/var/tests/junit.xml ./var/tests/junit.xml || true"
            junit 'var/tests/*.xml'
            // recordIssues tools: [phpStan(pattern: 'var/tests/phpstan.xml')]
        }
    }
}
