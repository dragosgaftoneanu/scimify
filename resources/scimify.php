<?php
/**
 * scimify
 * Author: Dragos Gaftoneanu <dragos.gaftoneanu@okta.com>
 * 
 * Disclaimer: This SCIM server was built in order to simulate and troubleshoot different SCIM use-cases and not to be used in production. The script is provided AS IS without warranty of any kind. Okta disclaims all implied warranties including, without limitation, any implied warranties of fitness for a particular purpose. We highly recommend testing scripts in a preview environment if possible.
 */
class scimify
{	
	function __construct()
	{
		$scim11 = new SCIM11();
		$scim20 = new SCIM20();
		
		if($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v1\/Users\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->getUser(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1], file_get_contents('php://input'));
		}elseif($_SERVER['REQUEST_METHOD'] == "POST" && preg_match('/^(.*)\/scim\/v1\/Users$/', @explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim11->createUser(file_get_contents('php://input'));
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v1\/Users$/', @explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim11->listUsers($_GET);
		}elseif($_SERVER['REQUEST_METHOD'] == "PUT" && preg_match('/^(.*)\/scim\/v1\/Users\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->putUser(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
		}elseif($_SERVER['REQUEST_METHOD'] == "PATCH" && preg_match('/^(.*)\/scim\/v1\/Users\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->patchUser(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);	
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v1\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->getGroup(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1], file_get_contents('php://input'));
		}elseif($_SERVER['REQUEST_METHOD'] == "POST" && preg_match('/^(.*)\/scim\/v1\/Groups$/', @explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim11->createGroup(file_get_contents('php://input'));
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v1\/Groups$/', @explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim11->listGroups($_GET);
		}elseif($_SERVER['REQUEST_METHOD'] == "PUT" && preg_match('/^(.*)\/scim\/v1\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->putGroup(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
		}elseif($_SERVER['REQUEST_METHOD'] == "PATCH" && preg_match('/^(.*)\/scim\/v1\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->patchGroup(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
		}elseif($_SERVER['REQUEST_METHOD'] == "DELETE" && preg_match('/^(.*)\/scim\/v1\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim11->deleteGroup(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);		
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v1\/ServiceProviderConfigs?$/', @explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim11->showServiceProviderConfig();
			//
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v2\/Users\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim20->getUser(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1], file_get_contents('php://input'));
		}elseif($_SERVER['REQUEST_METHOD'] == "POST" && preg_match('/^(.*)\/scim\/v2\/Users$/', @explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim20->createUser(file_get_contents('php://input'));
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v2\/Users$/', @explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim20->listUsers($_GET);
		}elseif($_SERVER['REQUEST_METHOD'] == "PUT" && preg_match('/^(.*)\/scim\/v2\/Users\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim20->putUser(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
		}elseif($_SERVER['REQUEST_METHOD'] == "PATCH" && preg_match('/^(.*)\/scim\/v2\/Users\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim20->patchUser(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);	
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v2\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim20->getGroup(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1], file_get_contents('php://input'));
		}elseif($_SERVER['REQUEST_METHOD'] == "POST" && preg_match('/^(.*)\/scim\/v2\/Groups$/', @explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim20->createGroup(file_get_contents('php://input'));
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v2\/Groups$/', @explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim20->listGroups($_GET);
		}elseif($_SERVER['REQUEST_METHOD'] == "PUT" && preg_match('/^(.*)\/scim\/v2\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim20->putGroup(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
		}elseif($_SERVER['REQUEST_METHOD'] == "PATCH" && preg_match('/^(.*)\/scim\/v2\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim20->patchGroup(file_get_contents('php://input'), explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);
		}elseif($_SERVER['REQUEST_METHOD'] == "DELETE" && preg_match('/^(.*)\/scim\/v2\/Groups\/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/', @explode("?", $_SERVER['REQUEST_URI'])[0]))
		{
			$scim20->deleteGroup(explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]) [count(explode("/", @explode("?", $_SERVER['REQUEST_URI'])[0]))-1]);		
		}elseif($_SERVER['REQUEST_METHOD'] == "GET" && preg_match('/^(.*)\/scim\/v2\/ServiceProviderConfigs?$/', @explode("?", $_SERVER['REQUEST_URI'])[0])){
			$scim20->showServiceProviderConfig();
		}
	}
}