Rapid Transit
=============

Deployment web app built on top of Symfony 2.6. Not continuous yet, but that's the plan. ;)

_Rapid Transit_ is the name for the web-app.
_Transit_ is the name of the collection of packages that can be used in other projects (coming soon).


Requirements
------------

 * PHP 5.4+
 * MongoDB
 * Ruby
 * Bundler
 * Capistrano

You must also be running Apache / Nginx / PHP-FPM etc, as the user that will have access
to the SSH deployment keys.


Installation
------------

You


Roadmap
-------

 * Add Doctrine ORM support. Currently only Doctrine MongoDB ODM is supported.
 * Add continuous deployment functionality.
 * Use message queues to queue deployments.
 * Add support for multiple deployment servers and deployment pipelines per project.
 * Get a list of commit changes that are yet to be deployed.
 * Allow Capistrano multistaging.
 * Allow repositories to be cloned from anywhere, not just GitHub.
 * Allow hooking into a continuous integration service, ie. Travis and Scrutinizer.
 * Split out the Web Bundle into components and bundle. (Transit)
 * Create a standard edition that can create a new installation using `composer create-project`. (Rapid Transit)
 * Use Sass and Compass to generate stylesheets.
 * Use Bower to install Javascript libraries.
 * Write Behat stories first! ;)
