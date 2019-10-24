# scimify
scimify is a PHP application that supports both SCIM 1.1 and SCIM 2.0 servers with operations for /Users, /Groups and /ServiceProviderConfig endpoints. This application was created based in order to test SCIM capabilities with Okta SCIM enabled applications.

:information_source: **Disclaimer:** This SCIM application was built in order to troubleshoot different SCIM use-cases and not to be used in production. The script is provided AS IS without warranty of any kind. Okta disclaims all implied warranties including, without limitation, any implied warranties of fitness for a particular purpose. We highly recommend testing scripts in a preview environment if possible.

## Requirements
* An Okta account, called an _organization_ (you can sign up for a free [developer organization](https://developer.okta.com/signup/))
* A local web server that runs PHP 7.0 with MySQLi extension and mod_rewrite module
* [ngrok](https://ngrok.com/) in order to inspect the requests and responses

## Installation
* Download scimify and upload it in the document root of your local web server
* Create a MySQL database and add the configuration details in resources/config.php
* Use `ngrok http <local web server port>` (eg. `ngrok http 80`) to put the web server online and link the ngrok URL with Okta

## Documentation
If you would like to find out more details about the methods used, check out the following wiki articles:
* [Methods ~ database queries](https://github.com/dragosgaftoneanu/scimify/wiki/Methods-~-database-queries)
* [Methods ~ SCIM queries](https://github.com/dragosgaftoneanu/scimify/wiki/Methods-~-SCIM-queries)

## Bugs?
If you find a bug in the SCIM server, please open an issue in this repository and it will be further investigated.
