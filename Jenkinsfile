pipeline {
    agent any
    environment {
        PROJECT_DIR = '/var/www/html'
        CONTAINER = 'astreinte-php'
        COVERAGE_FILE = 'build/logs/clover.xml'
        MIN_COVERAGE = 75
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

        stage('Run Tests & Coverage') {
            steps {
                sh "docker exec ${CONTAINER} mkdir -p build/logs"
                sh "docker exec ${CONTAINER} ./vendor/bin/phpunit --coverage-clover ${COVERAGE_FILE} --coverage-filter=src/Entity"
            }
        }

        // --- Correction pour Sonar, transformer les paths absolus en relatifs
        stage('Fix Coverage Paths') {
            steps {
                sh '''
                docker exec ${CONTAINER} php -r "
                \$dom = new DOMDocument();
                \$dom->load('${COVERAGE_FILE}');
                foreach (\$dom->getElementsByTagName('file') as \$file) {
                    \$name = \$file->getAttribute('name');
                    if (strpos(\$name, '/var/www/html/') === 0) {
                        \$relative = substr(\$name, strlen('/var/www/html/'));
                        \$file->setAttribute('name', \$relative);
                    }
                }
                \$dom->save('${COVERAGE_FILE}');
                "
                '''
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('LocalSonar') {
                    sh "docker exec ${CONTAINER} sonar-scanner -Dsonar.projectKey=astreinte -Dsonar.php.coverage.reportPaths=${COVERAGE_FILE}"
                }
            }
        }

        stage('Check Coverage Threshold') {
            steps {
                catchError(buildResult: 'FAILURE', stageResult: 'FAILURE') {
                    script {
                        def coverage = sh(
                            script: """
                            docker exec ${CONTAINER} php -r '
                                \$xml = simplexml_load_file("${COVERAGE_FILE}");
                                \$covered = 0;
                                \$statements = 0;
                                foreach (\$xml->xpath("//file") as \$file) {
                                    if (strpos((string)\$file["name"], "src/Entity/") !== false) {
                                        \$metrics = \$file->metrics;
                                        \$covered += (int)\$metrics["@coveredstatements"];
                                        \$statements += (int)\$metrics["@statements"];
                                    }
                                }
                                \$rate = \$statements > 0 ? (\$covered / \$statements) * 100 : 0;
                                echo round(\$rate, 2);
                            '
                            """,
                            returnStdout: true
                        ).trim()

                        echo "✅ Couverture des tests (src/Entity uniquement) : ${coverage}%"

                        if (coverage.isNumber() && coverage.toFloat() < MIN_COVERAGE.toFloat()) {
                            error("❌ Couverture trop faible (${coverage}%) — Minimum requis : ${MIN_COVERAGE}%")
                        }
                    }
                }
            }
        }

        stage('Static Analysis (PHPStan)') {
            steps {
                sh "docker exec ${CONTAINER} mkdir -p var/tests"
                sh "docker exec ${CONTAINER} vendor/bin/phpstan analyse --error-format=checkstyle > var/tests/phpstan.xml || true"
            }
        }

        stage('JUnit Report') {
            steps {
                sh "docker exec ${CONTAINER} mkdir -p var/tests"
                sh "docker exec ${CONTAINER} ./vendor/bin/phpunit --log-junit var/tests/junit.xml"
            }
        }
    }

    post {
        always {
            sh "docker cp ${CONTAINER}:/var/www/html/var/tests/junit.xml ./var/tests/junit.xml || true"
            junit 'var/tests/*.xml'
        }
    }
}
