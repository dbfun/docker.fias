#!/bin/sh

set -x

function gracefulShutdown {
  echo "Shutting down ..."
  /usr/bin/searchd -c "$SPHINX_CONFIG" --stop
  kill "$php_pid"
}

trap gracefulShutdown SIGINT SIGTERM

/scripts/reindex.sh

/usr/bin/searchd -c "$SPHINX_CONFIG"

php-fpm & php_pid="$!"

wait
