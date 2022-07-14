<?php

namespace src\Repositories;

use mysqli;

/**
 * An example of a base class to reduce database connectivity configuration for each repository subclass.
 */
class Repository {

	protected mysqli $mysqlConnection;

	private string $hostname;
	private string $username;
	private string $databaseName;
	private string $databasePassword;

	public function __construct() {
		// Note: in a real application we'd want to use environment variables to store credentials and any other environment specific data.

		$this->hostname = 'lcpbq9az4jklobvq.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
		$this->username = 'ycw5zq7u3ql4n4s6';
		$this->databaseName = 'tv2vnd5z3ftji0yr';
		$this->databasePassword = 'nuphhqqtecuecux2';

		$this->mysqlConnection = new mysqli($this->hostname, $this->username, $this->databasePassword, $this->databaseName);
		if ($this->mysqlConnection->connect_error) {
			die('Connection failed: ' . $this->mysqlConnection->connect_error);
		}
	}

}