[CrowdRockApp](http://www.crowdrockapp.com)
by [Series 79 of FIELD Course LLC - Harvard University Business School Class of 2013](http://www.harvard.edu)

----
Architect: Michael Lungo <mike@michaellungo.com>

## Finished in 4th Place in Class

Requirements:

	php
	apache
	mysql


Repository Notes:

  Add this to your `~/.gitconfig` file

    [branch]
      autosetuprebase = always


  Standard working procedure:

  1. Always maintain fresh copy of `master`

    git fetch origin

  2. Create a new branch per (features/chores/bugs)

    git branch features/<feature_name>
    git checkout features/<feature_name>

  3. On Commit messages

    Always add the PivotalTracker Story ID in the commit message [#ID]
    $ git commit -m "This is my update [#41703835]"

  4. Push the `feature` branch to github

    git push origin features/<feature_name>

  5. Login to github, and make a `pull request` from the repository to
     master

  6. If the merge will be automatically merge with out issues, confirm
     the merge

  7. If the merge will not do clean merge, checkout the repository to
     local machine and do a merge and push it directly to master


Setup local development

    # Installing the requirements

	1. Checkout application

	2. Install apache and mysql

	3. Add following line to /etc/hosts file to create an alias of localhost with a domain name:
	
	127.0.0.1 crowdrock.localhost

	4. Create a virtualhost config for apache to point to the application directory for crowdrock local domain. My config is as follows. Change the documentroot according to your checkout location.

	#=================
	NameVirtualHost crowdrock.localhost:80

	<VirtualHost crowdrock.localhost:80>
	ServerAdmin webmaster@localhost

	DocumentRoot /var/www/CrowdRock
	<Directory />
	Options FollowSymLinks
	AllowOverride All
	</Directory>
	<Directory /var/www/CrowdRock/>
	Options Indexes FollowSymLinks MultiViews
	AllowOverride All
	Order allow,deny
	allow from all
	</Directory>

	ErrorLog /var/log/apache2/error.log

	# Possible values include: debug, info, notice, warn, error, crit,
	# alert, emerg.
	LogLevel warn

	CustomLog /var/log/apache2/access.log combined
	</VirtualHost>
	#==================

	5. Create a database crowdrock_development and then run the 2 sql files mentioned in step 6. Database credential is set as root/root in config. You can use that settings to your mysql or change the config from application/config/database.php:
	
	$active_group = 'default';
	$active_record = TRUE;

	$db['default']['hostname'] = 'localhost';
	$db['default']['username'] = 'root';
	$db['default']['password'] = 'root';
	$db['default']['database'] = 'crowdrock_development';
	$db['default']['dbdriver'] = 'mysql';
	$db['default']['dbprefix'] = '';
	$db['default']['pconnect'] = TRUE;
	$db['default']['db_debug'] = TRUE;
	$db['default']['cache_on'] = FALSE;
	$db['default']['cachedir'] = '';
	$db['default']['char_set'] = 'utf8';
	$db['default']['dbcollat'] = 'utf8_general_ci';
	$db['default']['swap_pre'] = '';
	$db['default']['autoinit'] = TRUE;
	$db['default']['stricton'] = FALSE;

	6. Run 2 sql files to create the db structure:
	
	approot/application/sql/crowd-rock.sql
	approot/application/sql/ion_auth.sql
	