setup car4sure.local for windows
Download powertoys on microsoft
after installing  then open Host file editor
then step up domain to 127.0.0.1 and host car4sure.local
add another host with domain ::1 and host name  car4sure.local
 if using xampp run apache edit this file on this path
"C:\xampp2\apache\conf\extra\httpd-vhosts.conf"
add this changes
<VirtualHost *:80>
    ServerName car4sure.local
    DocumentRoot C:/xampp2/htdocs/car4sure/public    
    <Directory C/xampp2/htdocs/car4sure/public>
        DirectoryIndex index.php
        AllowOverride All
        Require all granted
    </Directory>


 </VirtualHost>
last step
 web app should open on car4sure/admin


Software Usaga
for Frontend i used Twig for layout and styling of the app
-A powerful and flexible templating engine for PHP, involves setting up the environment and working with its syntax to create clean, organized templates good fpr frontend
for Restful API i used Javascript
-RESTful APIs often use JSON for data exchange, and JavaScript natively supports JSON, making it seamless to parse and manipulate data
for backend i used a closed-source framework developed by SceneVoid
- SceneVoid PHP frameworks come with pre-written libraries and tools for common tasks like routing, authentication, database handling, and form validation
-Follows the Model-View-Controller (MVC) design pattern, which separates logic, presentation, and data management, leading to clean and maintainable code.
-Provides protection against common vulnerabilities
-Makes it easier to build scalable applications by organizing code into reusable modules.
-Support caching mechanisms, speeding up application load times
