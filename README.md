# Recommended Initial Setup

## Prerequisites

  1. Install a *AMP stack
* Mac: [MAMP](http://www.mamp.info/en/downloads/)
* Windows: [WAMP](http://www.wampserver.com/en/) or [XAMPP](https://www.apachefriends.org/download.html)
* Linux: Follow [this guide](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu)
  2. Download [OctoberCMS](https://octobercms.com/)
  3. Create an `octobercms` database
    * Go to either [localhost:8888](http://localhost:8888) or [localhost](http://localhost) and open phpMyAdmin, if you used MAMP, WAMP, or XAMPP
    * Click the "Databases" tab
    * Under the "Create a database" section, create a database named `octobercms`. Don't worry about setting the collation.

## Setup

  1. Before cloning this repository, go to your server root and create a `delphinium` folder.
* Your server root will be `www` under WAMP or `htdocs` under MAMP/XAMPP
  2.  Copy the installation files from OctoberCMS into `/delphinium`
  3. Depending on your setup, either go to [localhost:8888/delphinium/install.php](http://localhost:8888/delphinium/install.php) or [localhost/delphinium/install.php](http://localhost/delphinium/install.php) then follow the installation wizard.
  4. After getting to the database section, set the database as `octobercms` then set the MySQL login information. If this is a fresh install, you'll most likely have these settings:
    * Port: 3306
    * Username: root
    * Password: root
  5. Go to the Administrator section and setup your admin information.
    * **Do not forget this!** There's no way to retrieve your admin login information if you lose it.
  6. Finish the installation then try going to `/delphinium/backend` and login with your admin account to make sure everything is setup.
  7. Now go to `/delphinium/plugins` and clone the repository

    ```bash
    cd /path/to/delphinium/plugins
    git clone https://github.com/ProjectDelphinium/delphinium.git
    ```
  8. After that, you'll need to install the plugin. Open a command line and do the following:

    ```bash
    cd /path/to/delphinium
    php artisan october:up
    ```

Congratulations! Your app should be up and running.

## Migration From Previous Instructions

If you followed the previous installation instructions, this will ensure you end up with the repository in the correct place.

  1. Delete `/path/to/delphinium/.git`
  2. Go to `/path/to/delphinium/plugins` and clone the repository

  ```bash
  cd /path/to/delphinium/plugins
  git clone https://github.com/ProjectDelphinium/delphinium.git
  ```

Now you should be setup and correctly synced up.

## Troubleshooting

**When I see the OctoberCMS demo page, the style is all messed up and there's a bunch of 404 errors.**

Your mod_rewrite module is probably disabled. Look for your apache installation (under WAMP it will be `/path/to/wamp/bin/apache/apacheX.X.X`) then under the `conf` folder you'll find `httpd.conf`. Search for this line:  `#LoadModule rewrite_module modules/mod_rewrite.so` then remove the `#`. Restart your server.

**I'm getting an error saying it can't connect to the database.**

Check under `app/config/database.php` and make sure the MySQL database information is correct.

**When I try to run the `php artisan october:up` command, it says it cannot find php.**

Make sure the directory that contains `php.exe` is in your PATH variable.

**Right after I open the app, I see an exception saying that `octobercms.cache` does not exist.**

You'll need to create a `cache` table. Open phpMyAdmin and go to the `octobercms` database. Click the "Operations" tab then under the "Create table" section create a `cache` table with 3 columns. The 3 columns are:

  1. key
    * Type: VARCHAR
    * Length: ~500 (doesn't matter too much)
    * Index: UNIQUE
  2. value
    * Type: TEXT
  3.   expiration
    * Type: INT

##### After updating my repository I get a database error 

 ` {"error":{"type":"Illuminate\Database\QueryException","message":"SQLSTATE[42S02]: Base table or view not found: 1146 Table 'myTable' doesn't exist....`

Run `php artisan october:up`. This will create the table you are missing in your local OctoberCMS app

##### When I try to use Roots I get the following error:
`Class 'GuzzleHttp\Client' not found`

You need to go to the octoberCms backend and install the plugin October.Drivers

## Enabling development mode

1. Go to The Canvas website (uvu.instructure.com). Go to the DEV_MGMT course and select “Dev Delphinium” from the left menu. Click “Accept” (or log in, or whatever) when you get the message.
2. Copy your userId and Token somewhere safe.
3. Update your app with the latest code from GitHub.
4. Run the following command from your cmd line: 
* php artisan october:up
5. In your backend app go to Greenhouse/Dev. Add a new Dev Configuration (enter your UserId, Token, CourseId, Domain, and  select “Enable for dev mode”). Save your changes
6. Now in your development environment whenever you are going to request data from Raspberry you’ll need to drop the “dev/Dev” component in your page first.  To configure it just select the “Dev Configuration” you just created from the drop down. Then drop any other component you are going to be working on (this only applies for components that make data calls to Raspberry)
