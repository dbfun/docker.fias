FROM php:7.3-fpm-alpine

RUN apk add sphinx mysql-client && \
    mkdir /var/sphinx/

ADD scripts /scripts

WORKDIR /scripts

CMD ["/scripts/run.sh"]
