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


Quick Installation
------------------

```bash

$ git clone git@github.com:adamelso/rapid-transit.git
$ cd rapid-transit
$ composer install
$ bin/console doctrine:mongodb:schema:create
$ bin/console fos:user:create transitadmin --super-admin # you will be asked to set an email and password
$ bin/console server run

```

You can now access the project at `http://127.0.0.1:8000` and sign in with the `transitadmin` user you just created.


Notes
-----

 * This project uses the new Symfony 3 directory structure.
 * This project uses the Sylius Resource Bundle.


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
 * Add Vagrant box
