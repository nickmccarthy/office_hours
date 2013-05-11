<?php
class user
{
	private $table_name = "Users";

	function __construct($uid = -1)
	{
		$this->uid = $uid;
	}

	// Manually set user data
	function set_data($fn, $ln, $email, $pw)
	{
		$this->first_name = $fn;
		$this->last_name = $ln;
		$this->email = $email;
		$this->create_hash($pw);
	}

	private function create_hash($pw)
	{
		$this->salt = md5(uniqid(rand(), true));
		$this->hash = hash('sha256', $this->salt.$pw);
	}

	// Look up user data in db
	function lookup_data($db)
	{
		$query = "
		SELECT *
		FROM $this->table_name
		WHERE uid = \"$this->uid\"
		LIMIT 1";

		$result = $db->query($query);

		if ($result->num_rows == 1)
		{
			$user = $result->fetch_assoc();

			$this->email = $user["email"];
			$this->first_name = $user["first_name"];
			$this->last_name = $user["last_name"];
			$this->salt = $user["salt"];
			$this->hash = $user["hash"];
		}
	}

	function check_password($pw)
	{
		return hash('sha256', $this->salt.$pw) == $this->hash;
	}

	function check_and_set_password($db, $oldpw, $newpw)
	{
		if ($this->check_password($oldpw))
		{
			$this->create_hash($newpw);

			$query = "
			UPDATE $this->table_name
			SET hash=\"$this->hash\", salt=\"$this->salt\"
			WHERE uid=\"$this->uid\""; 

			$db->query($query);

			return $db->errno == 0;
		}

		return false;
	}

	// requires a call of set data before
	function add($db)
	{
		$query = "
		INSERT INTO $this->table_name (first_name, last_name, email, hash, salt)
		VALUES (\"$this->first_name\",\"$this->last_name\",\"$this->email\",\"$this->hash\",\"$this->salt\")";
		
		$db->query($query);
		$this->uid = $db->insert_id;
	}

	function get_classes($db)
	{
		return teaches::get_classes_by_uid($db, $this->uid);
	}

	static function exists($db, $email)
	{
		$query = "
		SELECT uid
		FROM Users
		WHERE email = \"$email\"
		LIMIT 1";

		$result = $db->query($query);
		if ($result->num_rows == 1)
		{
			$row = $result->fetch_assoc();
			$user = new user($row["uid"]);
			$user->lookup_data($db);
			return $user;
		}

		return false;
	}

}

?>