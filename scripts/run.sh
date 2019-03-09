#!/bin/sh

SPHINX_CONFIG=/scripts/sphinx.conf

set -x

function gracefulShutdown {
  echo "Shutting down ..."
  /usr/bin/searchd -c "$SPHINX_CONFIG" --stop
  kill "$php_pid"
}

function index {
  ls /var/sphinx/fias_main.*
  if [ $? -ne 0 ]; then
    indexer --all --config "$SPHINX_CONFIG"
  fi
}

trap gracefulShutdown SIGINT SIGTERM

index

/usr/bin/searchd -c "$SPHINX_CONFIG" &
php-fpm & php_pid="$!"

wait
