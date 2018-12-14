# commands
help:                                                                           ## shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

init: composer-install setup                                                   ## initialize or update project

composer-install:                                                              ## install dependencies
	composer install

setup:                                                                         ## setup
	bin/console talkly:setup

phpunit:                                                                        ## run phpunit tests
	vendor/bin/phpunit

update-snapshots:                                                               ## updates phpunit snapshots
	vendor/bin/phpunit -d --update-snapshots

phpstan:                                                                        ## run static code analyser
	phpstan analyse src -l max

php-cs-check:																	## run cs fixer (dry-run)
	PHP_CS_FIXER_FUTURE_MODE=1 php-cs-fixer fix --allow-risky=yes --diff --dry-run

php-cs-fix:																		## run cs fixer
	PHP_CS_FIXER_FUTURE_MODE=1 php-cs-fixer fix --allow-risky=yes

security:                                                                       ## check dependencies for security issues
	security-checker security:check

deptrac:                                                                        ## check your architecture constraints
	deptrac analyse --formatter-graphviz=0

dev-check: phpstan php-cs-fix security phpunit deptrac                          ## run dev checks

.PHONY: phpstan phpunit php-cs-check php-cs-fix help dev-check
