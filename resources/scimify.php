<?php
/**
 * scimify
 * Author: Dragos Gaftoneanu <dragos.gaftoneanu@okta.com>
 * 
 * Disclaimer: This SCIM server was built in order to simulate and troubleshoot different SCIM use-cases and not to be used in production. The script is provided AS IS 
 * without warranty of any kind. Okta disclaims all implied warranties including, without limitation, any implied warranties of fitness for a particular purpose. We highly
 * recommend testing scripts in a preview environment if possible.
 */
class scimify
{	
	function __construct()
	{
		$scim11 = new SCIM11();
		$scim20 = new SCIM20();
		
		/* SCIM 1.1 */
		if(preg_match('/^(.*)\/scim\/v1\/Users\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "GET")
				$scim11->getUser(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1], file_get_contents('php://input'));
			elseif($_SERVER['REQUEST_METHOD'] == "PUT")
				$scim11->putUser(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
			elseif($_SERVER['REQUEST_METHOD'] == "PATCH")
				$scim11->patchUser(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
			else
				$scim11->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v1\/Users$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "POST")
				$scim11->createUser(file_get_contents('php://input'));
			elseif($_SERVER['REQUEST_METHOD'] == "GET")
				$scim11->listUsers($_GET);
			else
				$scim11->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v1\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "GET")
				$scim11->getGroup(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1], file_get_contents('php://input'));
			elseif($_SERVER['REQUEST_METHOD'] == "PUT")
				$scim11->putGroup(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
			elseif($_SERVER['REQUEST_METHOD'] == "PATCH")
				$scim11->patchGroup(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
			elseif($_SERVER['REQUEST_METHOD'] == "DELETE")
				$scim11->deleteGroup(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
			else
				$scim11->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v1\/Groups$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "POST")
				$scim11->createGroup(file_get_contents('php://input'));
			elseif($_SERVER['REQUEST_METHOD'] == "GET")
				$scim11->listGroups($_GET);
			else
				$scim11->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v1\/ServiceProviderConfigs?$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "GET")
				$scim11->showServiceProviderConfig();
			else
				$scim11->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v1\/ResourceTypes?$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "GET")
				$scim11->throwError(400, "The requested endpoint is not available.");
			else
				$scim11->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v1\/Schemas?$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "GET")
				$scim11->throwError(400, "The requested endpoint is not available.");
			else
				$scim11->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v1\/Bulk?$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "POST")
				$scim11->throwError(400, "The requested endpoint is not available.");
			else
				$scim11->throwError(405, "The endpoint does not support the provided method.");
		}
		
		/* SCIM 2.0 */	
		if(preg_match('/^(.*)\/scim\/v2\/Users\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "GET")
				$scim20->getUser(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1], file_get_contents('php://input'));
			elseif($_SERVER['REQUEST_METHOD'] == "PUT")
				$scim20->putUser(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
			elseif($_SERVER['REQUEST_METHOD'] == "PATCH")
				$scim20->patchUser(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
			else
				$scim20->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v2\/Users$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "POST")
				$scim20->createUser(file_get_contents('php://input'));
			elseif($_SERVER['REQUEST_METHOD'] == "GET")
				$scim20->listUsers($_GET);
			else
				$scim20->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v2\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "GET")
				$scim20->getGroup(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1], file_get_contents('php://input'));
			elseif($_SERVER['REQUEST_METHOD'] == "PUT")
				$scim20->putGroup(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
			elseif($_SERVER['REQUEST_METHOD'] == "PATCH")
				$scim20->patchGroup(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
			elseif($_SERVER['REQUEST_METHOD'] == "DELETE")
				$scim20->deleteGroup(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
			else
				$scim20->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v2\/Groups$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "POST")
				$scim20->createGroup(file_get_contents('php://input'));
			elseif($_SERVER['REQUEST_METHOD'] == "GET")
				$scim20->listGroups($_GET);
			else
				$scim20->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v2\/ServiceProviderConfigs?$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "GET")
				$scim20->showServiceProviderConfig();
			else
				$scim20->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v2\/Me?$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if(in_array($_SERVER['REQUEST_METHOD'], array("GET", "POST", "PUT", "PATCH", "DELETE")))
				$scim20->throwError(400, "The requested endpoint is not available.");
			else
				$scim20->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v2\/ResourceTypes?$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "GET")
				$scim20->throwError(400, "The requested endpoint is not available.");
			else
				$scim20->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v2\/Schemas?$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "GET")
				$scim20->throwError(400, "The requested endpoint is not available.");
			else
				$scim20->throwError(405, "The endpoint does not support the provided method.");
		}elseif(preg_match('/^(.*)\/scim\/v2\/Bulk?$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			if($_SERVER['REQUEST_METHOD'] == "POST")
				$scim20->throwError(400, "The requested endpoint is not available.");
			else
				$scim20->throwError(405, "The endpoint does not support the provided method.");
		}
	}
}