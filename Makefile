install:
	composer.phar install

console:
	composer.phar exec --verbose psysh

lint:
	composer.phar exec --verbose phpcs -- --standard=PSR12 bin src tests
# composer.phar exec --verbose phpstan -- --level=8 analyse bin src tests

lint-fix:
	composer.phar exec --verbose phpcbf -- --standard=PSR12 src tests

test:
	composer.phar exec --verbose phpunit tests

test-coverage:
	composer.phar exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml