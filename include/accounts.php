<?php

require_once(LIB_PATH.DS.'database.php');

class User {

	protected static  $tblname = "tbluseraccount";



	function dbfields () {

		global $mydb;

		return $mydb->getfieldsononetable(self::$tblname);

	}

	function listofuser(){

		global $mydb;

		$mydb->setQuery("SELECT * FROM ".self::$tblname);

		return $cur;

	}

 

	function find_user($id="",$user_name=""){

		global $mydb;

		$mydb->setQuery("SELECT * FROM ".self::$tblname." 

			WHERE USERID = {$id} OR U_USERNAME = '{$user_name}'");

		$cur = $mydb->executeQuery();

		$row_count = $mydb->num_rows($cur);

		return $row_count;

	}

	public static function userAuthentication($U_USERNAME, $password) {
		global $mydb;
		$mydb->setQuery("SELECT * FROM `tbluseraccount` WHERE `U_USERNAME` = '" . $U_USERNAME . "'");
		$cur = $mydb->executeQuery();
	
		if ($cur == false) {
			die(mysql_error());
		}
	
		$row_count = $mydb->num_rows($cur);
		if ($row_count == 1) {
			$user_found = $mydb->loadSingleResult();
	
			if (password_verify($password, $user_found->U_PASS)) {
				$_SESSION['USERID'] = $user_found->USERID;
				$_SESSION['U_NAME'] = $user_found->U_NAME;
				$_SESSION['U_USERNAME'] = $user_found->U_USERNAME;
				$_SESSION['U_ROLE'] = $user_found->U_ROLE;
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	static function checkUsernameExists($username) {
        global $mydb;
        $mydb->setQuery("SELECT * FROM `tbluseraccount` WHERE `U_USERNAME` = '". $username ."'");
        $cur = $mydb->executeQuery();
        return $mydb->num_rows($cur) > 0;
    }

	function single_user($id=""){

			global $mydb;

			$mydb->setQuery("SELECT * FROM ".self::$tblname." 

				Where USERID= '{$id}' LIMIT 1");

			$cur = $mydb->loadSingleResult();

			return $cur;

	}

	/*---Instantiation of Object dynamically---*/

	static function instantiate($record) {

		$object = new self;



		foreach($record as $attribute=>$value){

		  if($object->has_attribute($attribute)) {

		    $object->$attribute = $value;

		  }

		} 

		return $object;

	}

	

	

	/*--Cleaning the raw data before submitting to Database--*/

	private function has_attribute($attribute) {

	  // We don't care about the value, we just want to know if the key exists

	  // Will return true or false

	  return array_key_exists($attribute, $this->attributes());

	}



	protected function attributes() { 

		// return an array of attribute names and their values

	  global $mydb;

	  $attributes = array();

	  foreach($this->dbfields() as $field) {

	    if(property_exists($this, $field)) {

			$attributes[$field] = $this->$field;

		}

	  }

	  return $attributes;

	}

	

	protected function sanitized_attributes() {

	  global $mydb;

	  $clean_attributes = array();

	  // sanitize the values before submitting

	  // Note: does not alter the actual value of each attribute

	  foreach($this->attributes() as $key => $value){

	    $clean_attributes[$key] = $mydb->escape_value($value);

	  }

	  return $clean_attributes;

	}

	

	

	/*--Create,Update and Delete methods--*/

	public function save() {

	  // A new record won't have an id yet.

	  return isset($this->id) ? $this->update() : $this->create();

	}

	public static function checkUserIdExists($userId) {
		global $mydb;
		$mydb->setQuery("SELECT * FROM `tbluseraccount` WHERE `USERID` = '" . $mydb->escape_value($userId) . "'");
		$cur = $mydb->executeQuery();
		return $mydb->num_rows($cur) > 0;
	}

	public function create() {

		global $mydb;

		// Don't forget your SQL syntax and good habits:

		// - INSERT INTO table (key, key) VALUES ('value', 'value')

		// - single-quotes around all values

		// - escape all values to prevent SQL injection

		$attributes = $this->sanitized_attributes();

		$sql = "INSERT INTO ".self::$tblname." (";

		$sql .= join(", ", array_keys($attributes));

		$sql .= ") VALUES ('";

		$sql .= join("', '", array_values($attributes));

		$sql .= "')";

	echo $mydb->setQuery($sql);

	

	 if($mydb->executeQuery()) {

	    $this->id = $mydb->insert_id();

	    return true;

	  } else {

	    return false;

	  }

	}



	public function update($id=0) {
		global $mydb;
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
			// Skip empty password
			if ($key == 'U_PASS' && empty($value)) {
				continue;
			}
			$attribute_pairs[] = "{$key}='{$value}'";
		}
		
		// If there are no attributes to update, return true (no change needed)
		if (empty($attribute_pairs)) {
			return true;
		}
	
		$sql = "UPDATE ".self::$tblname." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE USERID=". $id;
		$mydb->setQuery($sql);
		return $mydb->executeQuery();
	}



	public function delete($id=0) {

		global $mydb;

		  $sql = "DELETE FROM ".self::$tblname;

		  $sql .= " WHERE USERID=". $id;

		  $sql .= " LIMIT 1 ";

		  $mydb->setQuery($sql);

		  

			if(!$mydb->executeQuery()) return false; 	

	

	}	





}

?>