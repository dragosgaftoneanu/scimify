<?php
class scimify
{	
	function __construct()
	{
		$scim11 = new SCIM11();
		
		/* SCIM 1.1 logic */
		if($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v1\/Users\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->getUser(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]))-1], file_get_contents('php://input'));
		}elseif($_SERVER['REQUEST_METHOD'] == "POST" && preg_match('/^(.*)\/scim\/v1\/Users$/', explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim11->createUser(file_get_contents('php://input'));
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v1\/Users$/', explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim11->listUsers($_GET);
		}elseif($_SERVER['REQUEST_METHOD'] == "PUT" && preg_match('/^(.*)\/scim\/v1\/Users\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->putUser(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
		}elseif($_SERVER['REQUEST_METHOD'] == "PATCH" && preg_match('/^(.*)\/scim\/v1\/Users\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->patchUser(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);	
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v1\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->getGroup(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]))-1], file_get_contents('php://input'));
		}elseif($_SERVER['REQUEST_METHOD'] == "POST" && preg_match('/^(.*)\/scim\/v1\/Groups$/', explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim11->createGroup(file_get_contents('php://input'));
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v1\/Groups$/', explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim11->listGroups($_GET);
		}elseif($_SERVER['REQUEST_METHOD'] == "PUT" && preg_match('/^(.*)\/scim\/v1\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->putGroup(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
		}elseif($_SERVER['REQUEST_METHOD'] == "PATCH" && preg_match('/^(.*)\/scim\/v1\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->patchGroup(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
		}elseif($_SERVER['REQUEST_METHOD'] == "DELETE" && preg_match('/^(.*)\/scim\/v1\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->deleteGroup(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);		
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v1\/ServiceProviderConfig$/', explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim11->showServiceProviderConfig();
		}
	}
}