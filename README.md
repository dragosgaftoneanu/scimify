# scimify
scimify is a PHP application that supports both SCIM 1.1 and SCIM 2.0 servers with operations for /Users, /Groups and /ServiceProviderConfig endpoints. This application was created in order to test SCIM capabilities with Okta SCIM enabled applications.

:information_source: **Disclaimer:** This is not an official Okta product and, as such, does not qualify for Okta Support. If you have a questions about scimify, please check the Frequently Asked Questions page available [here](https://github.com/dragosgaftoneanu/scimify/wiki/Frequently-Asked-Questions). If you don't find an answer or, if you discover a bug in the SCIM server, please open an issue in this repository.

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

You can find below the deployment guides for SCIM 1.1 and SCIM 2.0 using an integrator organization:
* [Deploying scimify with SCIM 1.1 templates](https://github.com/dragosgaftoneanu/scimify/wiki/Deploying-scimify-with-SCIM-1.1-templates)
* [Deploying scimify with SCIM 2.0 templates](https://github.com/dragosgaftoneanu/scimify/wiki/Deploying-scimify-with-SCIM-2.0-templates)

If you would like to find out more details about the methods used, check out the following wiki articles:
* [Methods ~ database queries](https://github.com/dragosgaftoneanu/scimify/wiki/Methods-~-database-queries)
* [Methods ~ SCIM queries](https://github.com/dragosgaftoneanu/scimify/wiki/Methods-~-SCIM-queries)

## Runscope
When submitting your application to OIN Manager, it's required to provide Runscope results of your SCIM server. For reference, you can find below the Runscope results for scimify:
* [Okta SCIM 1.1 Spec Test](https://www.runscope.com/radar/kunxznp7attx/865d90f6-a44b-45c9-9540-e10237cbee32/history/cc2a55e7-8689-45ce-a5d3-a883f5455d8e)
* [Okta SCIM 1.1 CRUD Test](https://www.runscope.com/radar/kunxznp7attx/3be61ab6-411b-48ca-9fe5-9a5cb4e4b196/history/01fe504b-c8a4-42df-b54d-c366a8f22e4b)
* [Okta SCIM 2.0 Spec Test](https://www.runscope.com/radar/kunxznp7attx/bf55d1b0-b6cc-4729-bd83-7cae09b5c87e/history/8ef1ab21-0036-4002-a1e0-915b34607100)
* [Okta SCIM 2.0 CRUD Test](https://www.runscope.com/radar/kunxznp7attx/3be61ab6-411b-48ca-9fe5-9a5cb4e4b196/history/237264e3-94a6-448b-a90c-06f16612c7f5)