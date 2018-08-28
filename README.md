# A Codeigniter API Manager #

## What is this repository for? ##

You can easily to manage own codeigniter api library.

## Table of Contents ##

* Installation
* Usage
* FAQ

## Installation ##
Download this library and put into the codeigniter project.

### config ###
We had import the libraries to ``config/config.js``:

#### Libraries ####
* session (options)
* getpost // help you manger get and post data
* date // date library

#### Helper ####
* url // control the url path
* cookie (options)

#### Config ####
* global // basic setting
* routes_api // setting api config
* code_error // error message
* code_success // success message

#### Model ####
* mod_config // language and response model

```
/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
*/
$autoload['libraries'] = array('session', 'getpost', 'date', 'mongo_db');

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
*/
$autoload['helper'] = array('url', 'cookie');

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
*/
$autoload['config'] = array('global', 'routes_api', 'code_error', 'code_success');

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
*/
$autoload['model'] = array('mod_config');

```

### routes ###
Second, you can add the routes about the api authenticate.
Now you can catch the api data to api controller.

```
$route['api/(:any)/(:any)'] = 'api/authenticate/$1/$2';
```

## Usage ##

## FAQ ##

## Change Log ##