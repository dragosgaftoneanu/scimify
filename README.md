# scimify
scimify is a PHP application that supports both SCIM 1.1 and SCIM 2.0 servers with operations for /Users, /Groups and /ServiceProviderConfig endpoints. This application was created in order to test SCIM capabilities with Okta SCIM enabled applications. Please note that SCIM with On Premises Provisioning (OPP) is not supported.

:information_source: **Disclaimer:** This SCIM server was built in order to simulate and troubleshoot different SCIM use-cases and not to be used in production.

## Requirements
* An Okta account, called an _organization_ (you can sign up for a free [developer organization](https://developer.okta.com/signup/))
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
