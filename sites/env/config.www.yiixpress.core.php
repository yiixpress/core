<?php
/**
* Path to custom code folder under sites/<site owner>/<site> 
*/

defined('SITE_DIR') or define('SITE_DIR', 'client/site1');

/**
* The site's owner, support one user/organization to maintain many sites.
* This constant is currently used only by [SITE_OWNER]_sites table to group
* all sites belong to one owner.
* 
* It's suggested that the  site owner is part of the SITE_ID value
*/

defined ('SITE_OWNER') or define('SITE_OWNER', 'client');

/**
* The site id identify the site among other sites of the same owner.
* This value is used as table prefix ans as the session's cookie name
*/

defined ('SITE_ID') or define('SITE_ID', 'site1');

/***** STARTING DEFINITION FOR ALL DATABASES ******/
$dbs = array();
/* the default db will be mapped to Yii's db component */

$dbs['db']['connectionString'] = 'mysql:host=localhost;port=3306;dbname=yiixpress_core;';
$dbs['db']['username'] = 'root';
$dbs['db']['password'] = 'mysql';


/**
* It's recommended that you consider the 'partition' option for your database
* for purpose of performance turning and security. Yii allows you to have different
* db components pointing to the same database so that each component should be a
* partition of your database. One partition can hold only hardly-ever change table
* so you can cache query result. Other partican can hold critical / secure data
* require special credential to write.
*/