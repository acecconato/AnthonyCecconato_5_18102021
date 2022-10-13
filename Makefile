HOST ?= localhost
PORT ?= 8001

DB_HOST ?= localhost
DB_NAME ?= anthonyc5
DB_USER ?= root
DB_PASSWORD ?= root

PHP ?= php

PACKAGE_MANAGER_LOCK_FILE ?= yarn.lock
PACKAGE_MANAGER ?= yarn

MAILER_SENDER ?= email@localhost.com

ifeq ($(PACKAGE_MANAGER), npm)
	PACKAGE_MANAGER_LOCK_FILE = package-lock.json
endif

install: vendor node_modules build-dev # Install all dependencies for a development environment
.PHONY: install

clean:
	rm -rf vendor node_modules
.PHONY: clean

build-dev:
	$(PACKAGE_MANAGER) run dev
.PHONY: build-dev

build-prod:
	$(PACKAGE_MANAGER) run build
.PHONY: build-prod

run: ## Run a local instance of the webapp
	php -S $(HOST):$(PORT) -t public/ -d --display_errors=1
.PHONY: run

prepare: .env
	sed -i -e 's/.*DB_HOST.*/DB_HOST=$(DB_HOST)/' .env
	sed -i -e 's/.*DB_NAME.*/DB_NAME=$(DB_NAME)/' .env
	sed -i -e 's/.*DB_USER.*/DB_USER=$(DB_USER)/' .env
	sed -i -e 's/.*DB_PASSWORD.*/DB_PASSWORD=$(DB_PASSWORD)/' .env
	sed -i -e 's/.*MAILER_SENDER.*/MAILER_SENDER=$(MAILER_SENDER)/' .env
	rm .env-e
	make drop-database
	make create-database
	make fixtures
.PHONY: prepare

create-database: # Create the database
	mysql -u $(DB_USER) -p$(DB_PASSWORD) -e 'CREATE DATABASE IF NOT EXISTS $(DB_NAME)'
	mysql -u $(DB_USER) -p$(DB_PASSWORD) $(DB_NAME) < sql/structure.sql
.PHONY: database-create

drop-database: # Drop the database
	mysql -u $(DB_USER) -p$(DB_PASSWORD) -e 'DROP DATABASE IF EXISTS $(DB_NAME)'
.PHONY: database-drop

fixtures: # Generate demo fixtures
	$(PHP) ./src/Fixture/generate.php
.PHONY: database-fixtures

.env: .env.example
	cp .env.example .env

vendor: composer.lock
	composer install --optimize-autoloader

node_modules: $(PACKAGE_MANAGER_LOCK_FILE)
	$(PACKAGE_MANAGER) install


