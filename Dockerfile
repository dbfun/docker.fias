FROM php:7.3-fpm-alpine as fias-generic

# Для обновления версии Sphinx следует заменить ссылку для скачивания `SPHINX_TAR_GZ` и версию `SPHINX_VER`
ENV SPHINX_CONFIG=/scripts/etc/sphinx.conf \
    SPHINX_TAR_GZ=http://sphinxsearch.com/files/sphinx-3.1.1-612d99f-linux-amd64-musl.tar.gz \
    SPHINX_VER=3.1.1

# Установка Sphinx
RUN wget "$SPHINX_TAR_GZ" -O /tmp/sphinx.tar.gz && \
    cd /tmp/ && \
    tar -xzf /tmp/sphinx.tar.gz && \
    mv /tmp/sphinx-"$SPHINX_VER"/bin/* /usr/bin/ && \
    mkdir -p /var/sphinx/ /var/lib/sphinx/log/ /var/lib/sphinx/data/ && \
    # прибираемся
    rm -rf /tmp/*

# Дополнительные пакеты
RUN apk add mysql-client sqlite

ADD scripts /scripts

WORKDIR /scripts

# Добавление в образ данных
FROM fias-generic

ADD src-dist /src

RUN set -x && \
    indexer --all --config "$SPHINX_CONFIG" && \
    rm -rf /src

CMD ["/scripts/run.sh"]
