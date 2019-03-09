FROM php:7.3-fpm-alpine

RUN apk add mysql-client
# Sphinx
RUN wget http://sphinxsearch.com/files/sphinx-3.1.1-612d99f-linux-amd64-musl.tar.gz -O /tmp/sphinx.tar.gz && \
    cd /tmp/ && \
    tar -xzf /tmp/sphinx.tar.gz && \
    mv /tmp/sphinx-3.1.1/bin/* /usr/bin/ && \
    mkdir -p /var/sphinx/ /var/lib/sphinx/log/ /var/lib/sphinx/data/ && \
    # прибираемся
    rm -rf /tmp/*

ADD scripts /scripts

WORKDIR /scripts

CMD ["/scripts/run.sh"]
