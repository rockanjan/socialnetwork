# Social Network Using Image (SNUI)
This is a simple social network application done as part of a course project. It allows registration, creating albums, adding photos and k-means clustering of the images into different albums.

## Installation:
	### Setting up the database:
	The scripts directory include the SQL scripts that should be run in POSTGRES. 
	This will create the database "socialnetwork" and create the schema required by the system. 
	After running the scripts, the following tables should be installed.

	* person
	* album
	* photo
	* photolike
	* friendrequest
	* friendship
	* comment

	Setting up the web:
	-------------------
	The base folder should be added into the public webroot folder of a webserver (for example, /var/www). 
	The folder for the base directory should be named "sn". So the base directory for the project would be /var/www/sn
	The other setup required is providing the username/password for SNUI to access the database in POSTGRES.	
## Usage:

	After the installation, the system is ready to use. 
	Once the website loads, the user can register into the system and after logging in, can start using the functionalities of the system.
	
	
