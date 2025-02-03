FROM wordpress:5.7.2-fpm-alpine
LABEL author="wayne@mowdirect.co.uk"

RUN rm -fR /usr/src/wordpress/*
COPY public/wordpress /usr/src/wordpress/blog
