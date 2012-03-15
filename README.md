`/build` contains a Phing buildfile that will install (or update) a working WordPress development install.  You will need to install [Phing](http://www.phing.info/):

`$ pear install -f --alldeps phing/phing`

To run:

    cd /path/to/workingcopy
    cd build
    phing

Optionally, you can just grab `/build/src/wp-config.php` for your current dev environment.  The `wp-config.php` file contains standardized settings for your WordPress development environment.