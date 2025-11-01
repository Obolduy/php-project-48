install:
	composer install

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src

test:
	composer exec --verbose phpunit

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit -- --coverage-clover build/logs/clover.xml --coverage-html build/coverage

gendiff:
	./bin/gendiff $(ARGS)