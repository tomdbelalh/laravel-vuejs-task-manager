## About Task Manager

This project is done as part of skill test in Laravel. So it has not done with lot of features or things that could be done, rather completing task CRUD and update display order value with drag feature

## Tools used

- To start with drag order update feature the front-end code started based on [Vuewjs Drag and Drop example (Todo app)](https://codepen.io/pasoevi/pen/ooOrpo) and then few css code edited and updated to match test project requirement
- Bootstrap (https://getbootstrap.com/) v 4.4.1 has been used
- Vue.js (https://vuejs.org/v2/guide/installation.html) as javascript framework
- Axios (https://github.com/axios/axios) for ajax type operation
- Laravel v 7.10 as main php framework

## Features

- CRUD a task (as anonimous or under a project)
- Drag the task list to update display order in the database [this part has scope of update]

## Installation

- Run 'composer update'
- Create a database and link to the project
- Migrate and run seed file (To migrate: 'php artisan migrate', To run seed files: 'php artisan db:seed')
- Run the project from browser and enjoy


## Comments/ Scope of update

- No auth is used to keep the work simple
- No template has been created for the front-end work spliting into template pages for the simplicity of the work
- All front-end code are done in the default welcome.blade.php to review the code easily
- 'priority' feature could be update and 'is done' feature could be done in the current system


