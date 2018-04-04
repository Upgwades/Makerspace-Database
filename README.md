# Makerspace-Database

A Database solution for the Florida Polytechnic 3d Makerspace.

This repo exist to serve as a template/inspiration for a solution to managing 3d printer farms.

A live running demo of this code can be seen at [here](http://radmakerspace.com/) though you must be a student at Florida Polytechnic University to use it. Screenshots of the site can be seen below.

## Screenshots

Standard login page using google's material design template.

![alt text](https://raw.githubusercontent.com/Upgwades/Makerspace-Database/master/Screenshots/login_page.jpg)

Login dashboard for users where project request and status can be found as well as request editing.

![alt text](https://raw.githubusercontent.com/Upgwades/Makerspace-Database/master/Screenshots/home_page.jpg)

The edit project modal that allows users to append parts or fix request details.

![alt text](https://raw.githubusercontent.com/Upgwades/Makerspace-Database/master/Screenshots/edit_project_modal.jpg)

Project request page where user inputs project details and files

![alt text](https://raw.githubusercontent.com/Upgwades/Makerspace-Database/master/Screenshots/request_page.jpg)

Admin view of all requests

![alt text](https://raw.githubusercontent.com/Upgwades/Makerspace-Database/master/Screenshots/admin_view_request_page.jpg)

Admin view of a single project, includes numerous features to make edits, log progress change status etc.

![alt text](https://raw.githubusercontent.com/Upgwades/Makerspace-Database/master/Screenshots/admin_single_project.jpg)

Admin tools for the database

![alt text](https://raw.githubusercontent.com/Upgwades/Makerspace-Database/master/Screenshots/admin_tools.jpg)

Stats for the database that help in budgeting estimating general use of the lab

![alt text](https://raw.githubusercontent.com/Upgwades/Makerspace-Database/master/Screenshots/stats_page_1.jpg)

Stats continued

![alt text](https://raw.githubusercontent.com/Upgwades/Makerspace-Database/master/Screenshots/stats_page_2.jpg)

Resource page for students to consult when using the services of the Makerspace

![alt text](https://raw.githubusercontent.com/Upgwades/Makerspace-Database/master/Screenshots/resources_page.jpg)


## Software Used

* MySQL - powers the backend, not uploaded in this repo for security reasons
* PHP - self contained scripts as well as embedded code in the html, mostly used for sending queries
* Apache - server that runs everything locally, not uploaded in this repo
* JS/JQuery/AJAX - covers some of the blindspots of PHP, meant to eventually replace all PHP
* AWS (EC2, S3) - handles hosting and file storage


## Possible Changes

* Remove all PHP, replace with JavaScript
* Use NodeJS/JS frameworks
* Add missing features (for instance password recovery is absent as intended to be a demo)
* Integrate with octoprint for remote printing 

## Authors

* **Will Irwin** - *Everything* - [Upgwades](https://github.com/Upgwades)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Stackoverflow and was very helpful
* Bootstrap templates
* Google Material Design templates
