SYMFONY_ENV=dev
PHP_CMD=php
SYMFONY_CMD=$(PHP_CMD) bin/console --env=$(SYMFONY_ENV)
SYMFONY2_CMD=symfony
PHPUNIT=$(PHP_CMD) bin/phpunit
VENDOR_BIN=vendor/bin/
NODE_BIN=node_modules/.bin/
TEST_RESULT_PATH=tests/reports/

ts := $(shell /bin/date "+%s")

init:
	$(SYMFONY_CMD) doc:database:create --if-not-exists -vv && \
	$(SYMFONY_CMD) do:mi:mi --allow-no-migration -n -vv
	$(SYMFONY_CMD) do:fixtures:load -vv -n


migration:
	$(SYMFONY_CMD) do:mi:mi --allow-no-migration -n -vv
	$(SYMFONY_CMD) make:migration
	$(SYMFONY_CMD) do:mi:mi --allow-no-migration -n -vv

serve:
	$(SYMFONY2_CMD) serve --no-tls

test:
	mkdir $(TEST_RESULT_PATH) -p
	$(SYMFONY_CMD) debug:router --format=json -n > $(TEST_RESULT_PATH)routes.json
	mkdir $(TEST_RESULT_PATH)phpmd -p
	$(VENDOR_BIN)phpmd --ignore-violations-on-exit src/ html phpmd.xml.dist > $(TEST_RESULT_PATH)phpmd/index.html
	$(SYMFONY_CMD) doctrine:database:drop --force --env=dev
	$(SYMFONY_CMD) doctrine:database:create
	$(SYMFONY_CMD) doctrine:migrations:migrate --allow-no-migration -n -vv --quiet
	$(SYMFONY_CMD) doctrine:fixtures:load --no-interaction --env=test --group=app --group=test
	mkdir tests/reports/phpcs -p
	$(VENDOR_BIN)phpcs -s -q --runtime-set ignore_errors_on_exit true --report-checkstyle=$(TEST_RESULT_PATH)phpcs/checkstyle.xml.html --report-full=$(TEST_RESULT_PATH)phpcs/full.txt --report-summary=$(TEST_RESULT_PATH)phpcs/summary.txt src 1>&2; \
	$(VENDOR_BIN)phpcs -s -q --generator=html src > $(TEST_RESULT_PATH)phpcs/index.doc.html;
	$(VENDOR_BIN)phpcs -i -q && $(VENDOR_BIN)phpcs --version
	$(PHPUNIT)

security:
	wget https://get.sensiolabs.org/security-checker.phar -P bin/
	$(PHP_CMD) bin/security-checker.phar security:check
