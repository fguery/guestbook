default: install

composer-install:
	docker run --rm --interactive --tty \
		--volume $(shell pwd)/app:/app \
		composer install --ignore-platform-reqs --no-scripts --no-dev --optimize-autoloader

composer-update:
	docker run --rm --interactive --tty \
		--volume $(shell pwd)/app:/app \
	composer update --ignore-platform-reqs --no-scripts --no-dev --optimize-autoloader

composer-update-test:
	docker run --rm --interactive --tty \
		--volume $(shell pwd)/app:/app \
	composer update --ignore-platform-reqs

install: composer-install

install-db:
	docker exec bark_php ./console.php initialise

install-tests: composer-update-test

run-tests:
	docker exec -e POSTGRES_DATABASE="bark_tests" -e PHP_IDE_CONFIG="serverName=bark_php" bark_php ./console.php initialise
	docker exec -e POSTGRES_DATABASE="bark_tests" -e PHP_IDE_CONFIG="serverName=bark_php" bark_php vendor/bin/phpunit tests

start:
	docker-compose up
stop:
	docker-compose down
