#!/bin/sh

SPHINX_CONFIG=/scripts/sphinx.conf

set -x

function gracefulShutdown {
  echo "Shutting down ..."
  /usr/bin/searchd -c "$SPHINX_CONFIG" --stop
  kill "$php_pid"
}

trap gracefulShutdown SIGINT SIGTERM

/usr/bin/searchd -c "$SPHINX_CONFIG" &
php-fpm & php_pid="$!"

wait
