#!/usr/bin/env bash

# Install dev dependencies
cd /data
composer install --prefer-dist --no-interaction

# Run unit tests
cd /data/protected/tests
../../vendor/bin/phpunit unit/
