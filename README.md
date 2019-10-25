# scimify
scimify is a PHP application that supports both SCIM 1.1 and SCIM 2.0 servers with operations for /Users, /Groups and /ServiceProviderConfig endpoints. This application was created based in order to test SCIM capabilities with Okta SCIM enabled applications.

## Requirements
* An Okta account, called an _organization_ (you can sign up for a free [integrator organization](https://www.okta.com/integrate/signup/))
* A local web server that runs PHP 7.0 with MySQLi extension and mod_rewrite module
* [ngrok](https://ngrok.com/) in order to inspect the requests and responses

## Installation
* Download scimify and upload it in the document root of your local web server
* Create a MySQL database and add the configuration details in resources/config.php
* Use `ngrok http <local web server port>` (eg. `ngrok http 80`) to put the web server online and link the ngrok URL with Okta

## Documentation
scimify works by saving the user's and group's attributes and schemas separately, returning it completely and correctly in the response. The body received from Okta is parsed as defined by SCIM specifications available [here](http://www.simplecloud.info/#Resources).

You can find below the deployment guides for SCIM 1.1 and SCIM 2.0 using an integrator organization:
* [Deploying scimify with SCIM 1.1 templates](https://github.com/dragosgaftoneanu/scimify/wiki/Deploying-scimify-with-SCIM-1.1-templates)
* [Deploying scimify with SCIM 2.0 templates](https://github.com/dragosgaftoneanu/scimify/wiki/Deploying-scimify-with-SCIM-2.0-templates)

If you would like to find out more details about the methods used, check out the following wiki articles:
* [Methods ~ database queries](https://github.com/dragosgaftoneanu/scimify/wiki/Methods-~-database-queries)
* [Methods ~ SCIM queries](https://github.com/dragosgaftoneanu/scimify/wiki/Methods-~-SCIM-queries)

## Bugs?
If you find a bug in the SCIM server, please open an issue in this repository and it will be further investigated.
