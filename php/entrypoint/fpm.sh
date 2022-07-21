#!/bin/sh
supervisord -c /etc/supervisor/supervisord.conf

exec docker-php-entrypoint "$@"