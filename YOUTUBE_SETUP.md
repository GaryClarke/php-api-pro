## YouTube Setup

These instructions are for students who are following the YouTube edit of the course.

### Branches

The YouTube edit joins the course partway through. The good news is that exact start point has its own branch named `youtube-start`. Checkout that branch first..
```shell
git checkout youtube-start
```

### Docker

I created Docker images especially for the course so that you'd have everything you need without having to install software yourself..except for Docker of course.

Get up and running with this..
```shell
docker compose up -d
```

The API will now be reachable at `localhost:8080/healthcheck` 

`{{jet-fu.com}}` is just my environment variable which resolves to `localhost:8080`

---

Then install all the composer dependencies. In order to ensure you have all the same versions as me, run composer __install__, not update. 
```shell
docker compose exec app composer install
```

---

### Connect to the database

Credentials for connecting to the DB can be found in the .env file...
```.dotenv
DSN=pdo-mysql://user:secret@db:3306/flights-api?charset=utf8mb4
```

..and also in the docker-compose.yaml file
```yaml
db:
    # ...
    environment:
        MARIADB_ROOT_PASSWORD: secret
        MARIADB_DATABASE: flights-api
        MARIADB_USER: user
        MARIADB_PASSWORD: secret
    ports:
        - "3306:3306"
```

You can use those credentials to connect your DB admin tool of choice. I use [TablePlus](https://tableplus.com/) in the video.

![DB connection creds](doc/flights-api.png "Connecting to the database")

You now have all the configuration you need to follow along.

---

### Making API Requests
The endpoint which you need to follow along with the reservations pagination is:
`localhost:8080/flights/JF1001-20250101/reservations`

The endpoint which you need to follow along with the flights sorting is:
`localhost:8080/flights`

### Enroll in the Full Course
The YouTube edit is only a sample section of the full course. You can enroll in the full course here:
[https://www.garyclarke.tech/p/php-api-pro](https://www.garyclarke.tech/p/php-api-pro) 

The full course includes:
* API Fundamentals
* REST Operations
* API Design and Documentation
* Error Handling
* Response Content
* Performance and Optimization
* API Security
* API Versioning
* API Testing
* Consuming APIs
* Other API Types

Get a 20% discount using this coupon code: GCTREPO20

Happy Coding!

