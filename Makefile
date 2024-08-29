include .env

DOCKER_COMPOSE ?= docker compose
DOCKER_CMD ?= exec

default: install

# Files to make
.env:
	cp .env.dist .env

docker-compose.override.yml:
	cp docker-compose.yml docker-compose.override.yml

## dev		: Set up development files, such as ".env" and "docker-compose.override.yml".
.PHONY: dev
dev: .env docker-compose.override.yml
	@echo Ensured docker-compose override.

## help		: Print commands help.
.PHONY: help
help : Makefile
	@sed -n 's/^##//p' $<

## build		: Build the site, without installing it. It also runs "dev" and "up".
.PHONY: build build/composer
build: dev up build/composer
build/composer:
	@echo "Building $(PROJECT_NAME) project development environment."
	$(DOCKER_COMPOSE) $(DOCKER_CMD) web bash -c "composer install"

## install	: Install the target site. It also runs "build".
.PHONY: install
install: build
	@$(DOCKER_COMPOSE) $(DOCKER_CMD) web ./vendor/bin/run drupal:site-install
	@$(DOCKER_COMPOSE) $(DOCKER_CMD) web bash -c "drush uli"

## build-docker	: (Re)-build the Docker image and start up the containers.
.PHONY: build-docker
build-docker:
	@echo "Building $(PROJECT_NAME) Docker image..."
	$(DOCKER_COMPOSE) build --no-cache web
	$(DOCKER_COMPOSE) up -d

## phpunit	: Runs a specific phpunit test. Example: make phpunit MyTest
.PHONY: phpunit
phpunit:
	$(DOCKER_COMPOSE) $(DOCKER_CMD) -e XDEBUG_SESSION=1 -u 33 -T web phpunit --filter $(filter-out $@,$(MAKECMDGOALS))

## up		: Start up containers.
.PHONY: up
up:
	@echo "Starting up containers for $(PROJECT_NAME)..."
	@$(DOCKER_COMPOSE) up -d --remove-orphans

## shell		: Access `web` container via shell.
.PHONY: shell
shell:
	@$(DOCKER_COMPOSE) $(DOCKER_CMD) web bash

# https://stackoverflow.com/a/6273809/1826109
%:
	@:
