FROM mysql/mysql-server:8.0

ENV MYSQL_DATABASE=laravel-pokemon-scraping \
  MYSQL_USER=takuya \
  MYSQL_PASSWORD=takuya0777 \
  MYSQL_ROOT_PASSWORD=takuya0777 \
  TZ=Asia/Tokyo

COPY ./my.cnf /etc/my.cnf
RUN chmod 644 /etc/my.cnf