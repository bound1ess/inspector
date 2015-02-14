PHPUNIT=vendor/bin/phpunit
PORT=8000

run-tests: ; $(PHPUNIT)
code-coverage: ; $(PHPUNIT) --coverage-html coverage/
coverage-server: ; php -S localhost:$(PORT) -t coverage/

first-example: ; bin/inspector inspect --src=examples/50/src --test=examples/50/tests
