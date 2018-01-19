.PHONY: lint test

all:
	@echo "Please choose a task."

lint:
	composer validate
	find . -name '*.yml' -not -path './vendor/*' -not -path './vendor-bin/*' -not -path './src/Resources/public/vendor/*' | xargs yaml-lint
	find . \( -name '*.xml' -or -name '*.xlf' \) \
		-not -path './vendor/*' -not -path './vendor-bin/*' -not -path './src/Resources/public/vendor/*' \
        | xargs -I'{}' xmllint --encode UTF-8 --output '{}' --format '{}'
	vendor/bin/php-cs-fixer fix --verbose
	git diff --exit-code

phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon -l 7 src tests

test:
	vendor/bin/phpunit -c phpunit.xml.dist --coverage-clover build/logs/clover.xml
