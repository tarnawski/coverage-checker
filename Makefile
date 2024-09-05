.DEFAULT_GOAL := default

# Return makefile directory path.
CWD := $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))

# Docker image name
IMAGE := 'coverage-checker'

# Directory containing composer binaries
BIN_DIR := 'vendor/bin'

# Build docker image.
build:
	docker build -t $(IMAGE) .

# Install library dependencies.
composer:
	docker run -it --rm --volume $(CWD):/app --workdir /app $(IMAGE) composer install

# PHP CodeSniffer. Detects coding standard violations. (https://github.com/squizlabs/PHP_CodeSniffer)
phpcs:
	docker run -it --rm --volume $(CWD):/app --workdir /app $(IMAGE) php $(BIN_DIR)/phpcs

# PHP CodeSniffer. Automatically correct coding standard violations. (https://github.com/squizlabs/PHP_CodeSniffer)
phpcbf:
	docker run -it --rm --volume $(CWD):/app --workdir /app $(IMAGE) php $(BIN_DIR)/phpcbf

# PHPStan. Finding errors in your code without actually running it. (https://github.com/phpstan/phpstan)
phpstan:
	docker run -it --rm --volume $(CWD):/app --workdir /app $(IMAGE) php $(BIN_DIR)/phpstan analyse

# PHPUnit. Running unit tests. (https://github.com/sebastianbergmann/phpunit)
phpunit:
	docker run -it --rm --volume $(CWD):/app --workdir /app $(IMAGE) php $(BIN_DIR)/phpunit

# PHPUnit. Running unit tests and generate code coverage metrics. (https://github.com/sebastianbergmann/phpunit)
coverage:
	docker run -it --rm --volume $(CWD):/app --workdir /app $(IMAGE) php -d xdebug.mode=coverage $(BIN_DIR)/phpunit --coverage-clover coverage.xml

# Coverage Checker. Print code coverage value.
print:
	docker run -it --rm --volume $(CWD):/app --workdir /app $(IMAGE) php bin/coverage-printer coverage.xml

# Coverage Checker. Check that the code coverage meets the expected value.
check:
	docker run -it --rm --volume $(CWD):/app --workdir /app $(IMAGE) php bin/coverage-checker coverage.xml 100

default: phpcs phpstan coverage print check