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