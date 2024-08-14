include .env

DOCKER_COMPOSE ?= docker-compose
DOCKER_CMD ?= exec

# Files to make
.env:
	cp .env.dist .env

docker-compose.override.yml:
	cp docker-compose.override.example.yml docker-compose.override.yml

## help		: Print commands help.
.PHONY: help
help : Makefile
	@sed -n 's/^##//p' $<

# Build tasks for development.
## build		: Build the development environment.
.PHONY: build build/composer
build: .env dev up build/composer
build/composer:
	@echo "Building $(PROJECT_NAME) project development environment."
	$(DOCKER_COMPOSE) $(DOCKER_CMD) web bash -c "composer install"

## install	: Install Drupal.
.PHONY: install
install: build
	@$(DOCKER_COMPOSE) $(DOCKER_CMD) web ./vendor/bin/run drupal:site-install
	@$(DOCKER_COMPOSE) $(DOCKER_CMD) web bash -c "drush uli"

.PHONY: dev
dev: docker-compose.override.yml
	@echo Ensured docker-compose override.

## up		: Start up containers.
.PHONY: up
up:
	@echo "Starting up containers for $(PROJECT_NAME)..."
	@$(DOCKER_COMPOSE) up -d --remove-orphans

## shell		: Access `web` container via shell.
##		  You can optionally pass an argument with a service name to open a shell on the specified container
.PHONY: shell
shell:
	docker exec -ti -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(shell docker ps --filter name='$(PROJECT_NAME)-$(or $(filter-out $@,$(MAKECMDGOALS)), 'web')' --format "{{ .ID }}") bash

# Collect library code under ./dist.
.PHONY: dist
dist:
	rm -rf ./dist
	mkdir -p ./dist
	curl -s --output dist/artifacts.zip --location --header "PRIVATE-TOKEN: $(GITLAB_ACCESS_TOKEN)" https://git.fpfis.tech.ec.europa.eu/api/v4/projects/$(GITLAB_PROJECT_ID)/jobs/$(GITLAB_JOB_ID)/artifacts
	unzip dist/artifacts.zip
	rm dist/artifacts.zip
	cp ./node_modules/react/umd/react.production.min.js ./dist
	cp ./node_modules/react-dom/umd/react-dom.production.min.js ./dist

# https://stackoverflow.com/a/6273809/1826109
%:
	@:
