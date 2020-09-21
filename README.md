# scimify
scimify is a PHP application that supports both SCIM 1.1 and SCIM 2.0 servers with operations for /Users, /Groups and /ServiceProviderConfig endpoints. This application was created in order to test SCIM capabilities with Okta SCIM enabled applications.

:information_source: **Disclaimer:** This is not an official Okta product and, as such, does not qualify for Okta Support. If you have a question about scimify, please check the Frequently Asked Questions page available [here](https://github.com/dragosgaftoneanu/scimify/wiki/Frequently-Asked-Questions). If you don't find an answer or, if you discover a bug in the SCIM server, please open an issue in this repository.

## Requirements
* An Okta preview account, called an _organization_ (you can sign up for a free [integrator organization](https://www.okta.com/integrate/signup/))
* A local web server that runs PHP 7.0 with MySQLi extension and mod_rewrite module
* [ngrok](https://ngrok.com/) in order to inspect the requests and responses

## Installation
* Download scimify and upload it in the document root of your local web server
* Create a MySQL database and add the configuration details in resources/config.php
* Use `ngrok http <local web server port>` (eg. `ngrok http 80`) to put the web server online and link the ngrok URL with Okta

## Documentation
scimify works by parsing the request received from the identity provider and storing the body as per the SCIM 1.1 and SCIM 2.0 standards available [here](http://www.simplecloud.info/#Resources).

If you would like to find out more details about the methods used, check out the following wiki articles:
* [Methods ~ database queries](https://github.com/dragosgaftoneanu/scimify/wiki/Methods-~-database-queries)
* [Methods ~ SCIM queries](https://github.com/dragosgaftoneanu/scimify/wiki/Methods-~-SCIM-queries)

## Examples of requests and responses
If you would like to check how the requests from Okta look like and how scimify responds to them, check out the following articles:
* [SCIM 1.1](https://developer.okta.com/docs/reference/scim/scim-11/)
* [SCIM 2.0](https://developer.okta.com/docs/reference/scim/scim-20/)
