composer:
	composer validate
	composer update --no-interaction --prefer-dist

phpstan:
	vendor/bin/phpstan analyse src/ tests/ --level 8 --no-progress --error-format github

cs:
	vendor/bin/phpcs src/ --standard=vendor/pd/coding-standard/src/PeckaCodingStandard/ruleset.xml
	vendor/bin/phpcs src/ --standard=vendor/pd/coding-standard/src/PeckaCodingStandardStrict/ruleset.xml

run-tests:
	vendor/bin/tester -C tests/
