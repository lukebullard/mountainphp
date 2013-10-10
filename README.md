mountainphp
===========

Mountain PHP Micro-Framework

###Install
1. Copy the ".htaccess", "index.php", "application", and "system"
files and folders into a folder to your webserver.

2. Open .htaccess and change /mountainv2/ to the path relative to your webserver's root
that you installed Mountain to. For instance, if you want to access Mountain at http://localhost/
in your browser, your replace /mountainv2/ with /   If you want to access it at http://localhost/mywebsite/mountain/,
change it to /mywebsite/mountain/

3. In .htaccess, replace the /mountainv2/index.php with the same thing that you replaced with in Step 2, with index.php
at the end. For example, /index.php or /mywebsite/mountain/index.php.

4. Open system/system.conf.php and replace the "baseURL" => "/mountainv2/" line with "baseURL" => "*what/you/changed/in/step/2*"

5. To configure the MySQLi module, go to application/config/mysqli.conf.php and replace the appropriate values in the array.

6. Modify the application/pages/sample/index.page.php file, and open up your new website!


####Tested On:
Mountain v2 has been tested on Apache 2.x with Windows 7 via XAMPP. It is undergoing tests on Apache 2.4 on CentOS and Ubuntu at the moment.
Mountain v2 required PHP5.2 at minimum to run. Most third-party modules will require PHP5.3 or 5.4 to run, so we recommend PHP5.4.

###Further Documentation
Is on it's way!

Coded by Luke Bullard