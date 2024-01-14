
## About Project

Project backend is based on Laravel. On frontend side is used VUE.
It is simple ToDo application with features like:
- Create Project.
- Assign tasks to project
- Create Task
- Delete Task
- Update Task
- Complete Task
- Sorting Tasks by priority


## Project Setup


Simplest way to set up project is to use docker. 
- Unzip archive
- Move to unziped folder (I use **WSL**, the folder should be unziped inside folder like: \\wsl.localhost\Ubuntu-22.04\home\<user-name>)
- Run command: `docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v "$(pwd):/var/www/html" \
  -w /var/www/html \
  laravelsail/php82-composer:latest \
  composer install --ignore-platform-reqs`
- create `.env` file from `.env.example`
- Run: `./vendor/bin/sail php artisan key:generate`
- Run: `./vendor/bin/sail npm install`
- Run: `./vendor/bin/sail npm run build`
- Run: `./vendor/bin/sail php artisan storage:link`
- Run: `./vendor/bin/sail php artisan migrate --seed`







