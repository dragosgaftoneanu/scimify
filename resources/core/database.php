<?php
/**
 * scimify
 * Author: Dragos Gaftoneanu <dragos.gaftoneanu@okta.com>
 * 
 * Disclaimer: This SCIM server was built in order to simulate and troubleshoot different SCIM use-cases and not to be used in production. The script is provided AS IS without warranty of any kind. Okta disclaims all implied warranties including, without limitation, any implied warranties of fitness for a particular purpose. We highly recommend testing scripts in a preview environment if possible.
 */
class Database
{
	private $conn;
	
	function __construct()
	{
		$this->conn = mysqli_connect(SCIMIFY_DB_SERVER, SCIMIFY_DB_USERNAME, SCIMIFY_DB_PASSWORD, SCIMIFY_DB_NAME) or die("Error: Advanced SCIM Server could not connect to the provided database.");
		
		$this->conn->query("CREATE TABLE IF NOT EXISTS `memberships` (`ID` int(11) NOT NULL AUTO_INCREMENT, `userID` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `groupID` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, PRIMARY KEY (`ID`)) ENGINE=MyISAM DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;");
		$this->conn->query("CREATE TABLE IF NOT EXISTS `resources` (`ID` varchar(36) COLLATE utf8_unicode_ci NOT NULL, `created` int(11) NOT NULL, `lastUpdated` int(11) NOT NULL, `scimType` text COLLATE utf8_unicode_ci NOT NULL, `type` int(11) NOT NULL, PRIMARY KEY (`ID`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		$this->conn->query("CREATE TABLE IF NOT EXISTS `resource_attributes` (`ID` int(11) NOT NULL AUTO_INCREMENT, `resourceID` varchar(36) COLLATE utf8_unicode_ci NOT NULL, `attribute` text COLLATE utf8_unicode_ci NOT NULL, `value` text COLLATE utf8_unicode_ci NOT NULL, PRIMARY KEY (`ID`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		$this->conn->query("CREATE TABLE IF NOT EXISTS `resource_schemas` (`ID` int(11) NOT NULL AUTO_INCREMENT, `resourceID` varchar(36) COLLATE utf8_unicode_ci NOT NULL, `value` text COLLATE utf8_unicode_ci NOT NULL, PRIMARY KEY (`ID`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
	}
	
	public function createResource($scimType, $type)
	{
		$resourceID = $this->gen_uuid();
		$time = time();
		
		$stmt = $this->conn->prepare("INSERT INTO resources(ID, created, lastUpdated, scimType, type) VALUES (?, ?, ?, ?, ?)");
		$stmt->bind_param("siisi", $resourceID, $time, $time, $scimType, $type);
		$stmt->execute();
		$stmt->close();
		
		return $resourceID;
	}
	
	public function deleteResource($resourceID)
	{
		$stmt = $this->conn->prepare("DELETE FROM resources where ID=?");
		$stmt->bind_param("s", $resourceID);
		$stmt->execute();
		$stmt->close();
		
		return;
	}
	
	public function listResources($scimType, $type, $options, $countAll=false)
	{
		$query = "SELECT DISTINCT resources.ID from resources, resource_attributes WHERE resources.scimType=? and resources.type=? AND resources.ID=resource_attributes.resourceID ";
		
		$startIndex = 0;
		$count=100;
		
		if((int) $options['count'] > 0)
			$count = (int) $options['count'];
		
		if((int) $options['startIndex'] > 0)
			$startIndex = (int) $options['startIndex'];
		
		/* This is implemented only for resource discovery */
		if($options['filter'] != "")
		{
			$isInvalid = 0;
			$filter = explode(" ", urldecode($options['filter']));
			if(count($filter) > 3)
				$isInvalid = 1;
			
			if($filter[1] != "eq" || ($filter[0] != "userName" && $filter[0] != "displayName"))
				$isInvalid = 1;
			
			if($isInvalid == 0)
				$query .= " AND resource_attributes.attribute='userName' AND resource_attributes.value=? ";
			
		}
		
		if($countAll == false)
			$query .= " LIMIT " . ($startIndex > 0 ? $startIndex -1 : $startIndex) . ",$count";
		
		$stmt = $this->conn->prepare($query);
		
		if($options['filter'] != "" && $isInvalid == 0)
		{
			$stmt->bind_param("sis", $scimType, $type, $filter[2]);
		}else
			$stmt->bind_param("si", $scimType, $type);
		
		$stmt->execute();
		$result = $stmt->get_result();
		
		while($f = $result->fetch_assoc())
			$attributes[] = $f['ID'];
		
		$stmt->close();
		
		if($countAll == false)
			return $attributes;
		else
			return count($attributes);
	}
	
	public function addResourceAttribute($resourceID, $attribute, $value)
	{
		$stmt = $this->conn->prepare("INSERT INTO resource_attributes(resourceID, attribute, value) VALUES (?, ?, ?)");
		$stmt->bind_param("sss", $resourceID, $attribute, $value);
		$stmt->execute();
		$stmt->close();
		
		return;
	}
		
	public function getResourceAttributes($resourceID)
	{
		$attributes = array();
		
		$stmt = $this->conn->prepare("SELECT attribute, value FROM resource_attributes WHERE resourceID=?");
		$stmt->bind_param("s", $resourceID);
		$stmt->execute();
		$result = $stmt->get_result();
		while($f = $result->fetch_assoc())
			$attributes[$f['attribute']] = json_decode($f['value']);
		$stmt->close();
		
		return $attributes;
	}
	
	public function deleteResourceAttribute($resourceID, $attribute)
	{
		if(stristr($attribute, "."))
		{
			$attribute = explode(".", $attribute);
			
			$stmt = $this->conn->prepare("SELECT value FROM resource_attributes WHERE resourceID=? AND attribute=?");
			$stmt->bind_param("ss", $resourceID, $attribute[0]);
			$stmt->execute();
			$result = $stmt->get_result();
			$value = $result->fetch_assoc()['value'];
			$stmt->close();
			
			unset($result[$attribute[1]]);
			
			$stmt = $this->conn->prepare("UPDATE resource_attributes SET value=? WHERE resourceID=? AND attribute=?");
			$stmt->bind_param("sss", $result, $resourceID, $attribute);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
		}else{
			$stmt = $this->conn->prepare("DELETE FROM resource_attributes WHERE resourceID=? AND attribute=?");
			$stmt->bind_param("ss", $resourceID, $attribute);
			$stmt->execute();
			$stmt->close();
			
			$stmt = $this->conn->prepare("SELECT ID FROM resource_schemas WHERE resourceID=? AND value=?");
			$stmt->bind_param("ss", $resourceID, $attribute);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			
			if($result->num_rows > 0)
			{
				$stmt = $this->conn->prepare("DELETE FROM resource_schemas WHERE resourceID=? AND value=?");
				$stmt->bind_param("ss", $resourceID, $attribute);
				$stmt->execute();
				$stmt->close();
			}
		}
		
		return;
	}
	
	public function deleteResourceAttributes($resourceID)
	{
		$stmt = $this->conn->prepare("DELETE FROM resource_attributes WHERE resourceID=?");
		$stmt->bind_param("s", $resourceID);
		$stmt->execute();
		$stmt->close();
		
		return;
	}
	
	public function addResourceSchema($resourceID, $value)
	{
		if($patch == false)
		{
			$stmt = $this->conn->prepare("INSERT INTO resource_schemas(resourceID, value) VALUES (?, ?)");
			$stmt->bind_param("ss", $resourceID, $value);
			$stmt->execute();
			$stmt->close();
		}
		
		return;
	}
	
	public function getResourceSchemas($resourceID)
	{
		$schemas = array();
		
		$stmt = $this->conn->prepare("SELECT value FROM resource_schemas WHERE resourceID=?");
		$stmt->bind_param("s", $resourceID);
		$stmt->execute();
		$result = $stmt->get_result();
		while($f = $result->fetch_assoc())
			$schemas[] = $f['value'];
		$stmt->close();
		return $schemas;
	}
	
	public function deleteResourceSchema($resourceID, $schema)
	{
		$stmt = $this->conn->prepare("DELETE FROM resource_schemas WHERE resourceID=? and value=?");
		$stmt->bind_param("ss", $resourceID, $schema);
		$stmt->execute();
		$stmt->close();
		
		return;
	}
	
	public function deleteResourceSchemas($resourceID)
	{
		$stmt = $this->conn->prepare("DELETE FROM resource_schemas WHERE resourceID=?");
		$stmt->bind_param("s", $resourceID);
		$stmt->execute();
		$stmt->close();
		
		return;
	}
	
	public function userExists($userNameOrID, $scimType)
	{
		$userName = json_encode($userNameOrID);
		
		$stmt = $this->conn->prepare("SELECT DISTINCT resource_attributes.ID FROM resource_attributes, resources WHERE resource_attributes.attribute='userName' and resource_attributes.value=? and resource_attributes.resourceID = resources.ID AND resources.scimType=? AND resources.type=0");
		$stmt->bind_param("ss", $userName, $scimType);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if($result->num_rows == 0)
		{
			$stmt = $this->conn->prepare("SELECT * FROM resources WHERE ID=? AND type=0 AND scimType=?");
			$stmt->bind_param("ss", $userNameOrID, $scimType);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();	
			return $result->num_rows;
		}else
			return $result->num_rows;
	}
	
	public function getUserID($username, $scimType)
	{
		$attributes = array();
		$username = '"' . $username . '"';
		
		$stmt = $this->conn->prepare("SELECT DISTINCT resource_attributes.resourceID FROM resource_attributes, resources WHERE resource_attributes.attribute='userName' and resource_attributes.value=? and resource_attributes.resourceID = resources.ID AND resources.scimType=? and resources.type=0");
		$stmt->bind_param("ss", $username, $scimType);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$f = $result->fetch_assoc();
		$stmt->close();
		return $f['resourceID'];
	}
	
	public function groupExists($groupNameOrID, $scimType)
	{
		$groupName = json_encode($groupNameOrID);
		
		$stmt = $this->conn->prepare("SELECT DISTINCT resource_attributes.ID FROM resource_attributes, resources WHERE resource_attributes.attribute='displayName' and resource_attributes.value=? and resource_attributes.resourceID = resources.ID AND resources.scimType=? AND resources.type=1");
		$stmt->bind_param("ss", $groupName, $scimType);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if($result->num_rows == 0)
		{
			$stmt = $this->conn->prepare("SELECT * FROM resources WHERE ID=? AND type=1 AND scimType=?");
			$stmt->bind_param("ss", $groupNameOrID, $scimType);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();	
			return $result->num_rows;
		}else
			return $result->num_rows;
	}
	
	public function getGroupID($group, $scimType)
	{
		$attributes = array();
		$group = json_encode($group);
		
		$stmt = $this->conn->prepare("SELECT DISTINCT resource_attributes.resourceID FROM resource_attributes, resources WHERE resource_attributes.attribute='displayName' and resource_attributes.value=? and resource_attributes.resourceID = resources.ID AND resources.scimType=? AND resources.type=1");
		$stmt->bind_param("ss", $group, $scimType);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$f = $result->fetch_assoc();
		$stmt->close();
		return $f['resourceID'];
	}
	
	public function addGroupMember($groupID, $userID)
	{
		$stmt = $this->conn->prepare("SELECT * FROM memberships WHERE groupID=? AND userID=?");
		$stmt->bind_param("ss", $groupID, $userID);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if($result->num_rows == 0)
		{
			$stmt = $this->conn->prepare("INSERT INTO memberships(groupID, userID) VALUES (?, ?)");
			$stmt->bind_param("ss", $groupID, $userID);
			$stmt->execute();
			$stmt->close();
		}
		
		return;
	}
	
	public function getGroupMembers($groupID)
	{
		$attributes = array();
		
		$stmt = $this->conn->prepare("SELECT userID from memberships where groupID=?");
		$stmt->bind_param("s", $groupID);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if($result->num_rows > 0)
			while($f = $result->fetch_assoc())
				$attributes[] = $f['userID'];
		
		$stmt->close();
		
		return $attributes;
	}
	
	public function getGroupMemberships($userID)
	{
		$attributes = array();
		
		$stmt = $this->conn->prepare("SELECT groupID from memberships where userID=?");
		$stmt->bind_param("s", $userID);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if($result->num_rows > 0)
			while($f = $result->fetch_assoc())
				$attributes[] = $f['groupID'];
			
		$stmt->close();
		
		return $attributes;
	}
	
	public function deleteGroupMembership($groupID, $userID)
	{
		$stmt = $this->conn->prepare("DELETE FROM memberships WHERE groupID=? and userID=?");
		$stmt->bind_param("ss", $groupID, $userID);
		$stmt->execute();
		$stmt->close();
		
		return;
	}
	
	public function deleteAllGroupMembership($groupID)
	{
		$stmt = $this->conn->prepare("DELETE FROM memberships WHERE groupID=?");
		$stmt->bind_param("s", $groupID);
		$stmt->execute();
		$stmt->close();
		
		return;
	}
	
	public function updateTimestamp($resourceID)
	{
		$stmt = $this->conn->prepare("UPDATE resources SET lastUpdated=" . time() . " WHERE ID=?");
		$stmt->bind_param("s", $resourceID);
		$stmt->execute();
		$result = $stmt->get_result();
		
		return;
	}
	
	public function getMetadata($resourceID)
	{
		$attributes = array();
		
		$stmt = $this->conn->prepare("SELECT created, lastUpdated FROM resources WHERE ID=?");
		$stmt->bind_param("s", $resourceID);
		$stmt->execute();
		$result = $stmt->get_result();
		$attributes = $result->fetch_assoc();
		$stmt->close();
		return $attributes;		
	}
	
	public function gen_uuid() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}
}