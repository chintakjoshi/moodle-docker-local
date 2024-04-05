# Steps to install moodle 

1) Create a docker network for MoodleDB and Moodle
```
docker network create moodle-network
```

2) Setup Database (MariaDB) for Moodle within netwerk
*PS - The following commands are only for linux users*
```
docker run -d --name moodle-db \
--network moodle-network \
-e MYSQL_ROOT_PASSWORD=password \
-e MYSQL_DATABASE=moodle \
-e MYSQL_USER=moodle \
-e MYSQL_PASSWORD=password \
mariadb
```

3) Install Moodle Image to docker - 
```
docker build --no-cache -t moodle .
```

4) Run Moodle Container with Network - 
```
docker run -d --name moodle \
--network moodle-network \
-e MOODLE_DB_HOST=moodle-db \
-e MOODLE_DB_NAME=moodle \
-e MOODLE_DB_USER=moodle \
-e MOODLE_DB_PASSWORD=password \
-p 8080:80 \
moodle
```

The Default username and password for the Moodle Web App will - ```admin and password``` respectively.

The repository was cloned from [https://github.com/ubc/moodle-docker/tree/master](https://github.com/ubc/moodle-docker/tree/master).