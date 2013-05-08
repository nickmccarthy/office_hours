<?php
class user
{
	private $table_name = "Users";

	function __construct($email)
	{
		$this->email = $email;
	}

	// Manually set user data
	function set_data($fn, $ln, $pw)
	{
		$this->fn = $fn;
		$this->ln = $ln;
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
		WHERE email = \"$this->email\"
		LIMIT 1";

		$result = $db->query($query);

		if ($result->num_rows == 1)
		{
			$user = $result->fetch_assoc();

			$this->uid = $user["uid"];
			$this->fn = $user["first_name"];
			$this->ln = $user["last_name"];
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

	function exists($db)
	{
		$query = "
		SELECT 1
		FROM $this->table_name
		WHERE email = \"$this->email\"";

		$result = $db->query($query);
		return $result->num_rows == 1;
	}

	// requires a call of set data before
	function add($db)
	{
		$query = "
		INSERT INTO $this->table_name (first_name, last_name, email, hash, salt)
		VALUES (\"$this->fn\",\"$this->ln\",\"$this->email\",\"$this->hash\",\"$this->salt\")";
		
		$db->query($query);
	}

	function get_classes($db)
	{
		return teaches::get_classes_by_uid($db, $this->uid);
	}

	function uid() {
		return $this->uid;
	}
	function first_name() {
		return $this->fn;
	}
	function last_name() {
		return $this->ln;
	}
}

?>