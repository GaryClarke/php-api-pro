## PHP API Pro
[GaryClarkeTech](https://garyclarke.tech)
---

This is the repository which accompanies [PHP API Pro](https://www.garyclarke.tech/p/php-api-pro). A comprehensive step-by-step video course guiding you through the process of creating awesome APIs in PHP.

### Setup

In the course, I use [Docker](https://www.garyclarke.tech/p/learn-docker-and-php) to create my development environment and created custom images especially for this course. 
It is a very simple setup consisting of PHP, Nginx, and MariaDB (MySQL) containers. Spin it up with these two commands.

```shell
docker compose up -d
docker compose exec app composer install
```
This will give you the exact same setup as me with the exact same versions of all the dependencies. This will make it much easier for me to help you, should you encounter problems.

You should now also be able to check the API at `localhost:8080/healthcheck`

When you see me use `{{jet-fu.com}}` in Postman, that is simply a Postman environment variable which resolves to `localhost:8080`. 

### Branches
Each individual lesson has a corresponding branch in this repo. There is a link to the branch in the lesson text e.g.

Branch: [https://github.com/GaryClarke/php-api-pro/tree/3-phpstan-composer-script](https://github.com/GaryClarke/php-api-pro/tree/3-phpstan-composer-script)

Happy Coding!

