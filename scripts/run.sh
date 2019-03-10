#!/bin/sh

set -x

function gracefulShutdown {
  echo "Shutting down ..."
  /usr/bin/searchd -c "$SPHINX_CONFIG" --stop
  kill "$php_pid"
}

function indexOnce {
  if [ -d "/src" ]; then
    indexer --all --config "$SPHINX_CONFIG"
  fi
}

trap gracefulShutdown SIGINT SIGTERM

indexOnce

/usr/bin/searchd -c "$SPHINX_CONFIG" &

php-fpm & php_pid="$!"

wait
