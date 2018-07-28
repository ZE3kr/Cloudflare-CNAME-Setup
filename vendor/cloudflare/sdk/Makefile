THIS := $(realpath $(lastword $(MAKEFILE_LIST)))
HERE := $(shell dirname $(THIS))

.PHONY: all fix lint test

all: lint test

fix:
	php $(HERE)/vendor/bin/php-cs-fixer fix --config=$(HERE)/.php_cs

lint:
	php $(HERE)/vendor/bin/php-cs-fixer fix --config=$(HERE)/.php_cs --dry-run
	php $(HERE)/vendor/bin/phpmd src/ text cleancode,codesize,controversial,design,naming,unusedcode
	php $(HERE)/vendor/bin/phpmd tests/ text cleancode,codesize,controversial,design,naming,unusedcode

test:
	php $(HERE)/vendor/bin/phpunit --configuration $(HERE)/phpunit.xml
