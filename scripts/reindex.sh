#!/bin/sh

if [ -d "/src" ]; then
  rm -f "$SQLITE_DB"
  php /scripts/app/collectdata.php
  indexer --all --config "$SPHINX_CONFIG"
fi
