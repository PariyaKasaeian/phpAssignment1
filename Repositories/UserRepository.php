<?php

namespace src\Repositories;

require_once 'Repository.php';
require_once __DIR__ . '/../Models/User.php';

use src\Models\User;

//use PDO; // only needed for commented PDO example at the bottom.
//use PDOException;

class UserRepository extends Repository {

	/**
	 * @param string $id
	 * @return array|false
	 */
	public function getUserById(string $id): User|false {
		$sqlStatement = $this->mysqlConnection->prepare("SELECT id, password_digest, email, name FROM users WHERE id = ?");
		$sqlStatement->bind_param('i', $id);
		$sqlStatement->execute();
		$resultSet = $sqlStatement->get_result();
		if ($resultSet->num_rows === 1) {
			return (new User($resultSet->fetch_assoc()));
		}
		return false;
	}

	/**
	 * @param string $email
	 * @return array|false
	 */
	public function getUserVulnerable(string $email): User|false {
		$sqlStatement = "SELECT id, password_digest, email, name FROM users WHERE email=\"$email\";";
		$resultSet = $this->mysqlConnection->query($sqlStatement); # https://www.php.net/manual/en/mysqli.query.php
		if ($resultSet->num_rows === 1) {
			return (new User($resultSet->fetch_assoc()));
		}
		return false;
	}

	/**
	 * @param string $email
	 * @return array|false
	 */
	public function getUserSecure(string $email): User|false {
		$sqlStatement = $this->mysqlConnection->prepare("SELECT id, password_digest, email, name FROM users WHERE email = ?;");
		$sqlStatement->bind_param('s', $email);
		$success = $sqlStatement->execute();
		if ($success) {
			$resultSet = $sqlStatement->get_result();
			$userData = $resultSet->fetch_assoc();
			return new User($userData);
		} else {
			return false;
		}
	}

	/**
	 * @param string $email
	 * @param string $name
	 * @param string $bcryptPasswordDigest
	 * @return bool
	 *
	 * ~ Do not interact with a database in this fashion. For SQL injection demo purposes only. ~
	 */
	public function saveUserVulnerable(string $email, string $name, string $bcryptPasswordDigest): bool {
		if ($this->mysqlConnection->connect_error) {
			die("Connection failed: " . $this->mysqlConnection->connect_error);
		}
		$sqlStatement = "INSERT INTO users VALUES(NULL, \"$bcryptPasswordDigest\", \"$email\", \"$name\");";
		return $this->mysqlConnection->multi_query($sqlStatement);
	}

	/**
	 * @param string $email
	 * @param string $name
	 * @param string $bcryptPasswordDigest
	 * @return bool true on success, false on failure
	 */
	public function saveUserSecure(string $email, string $name, string $bcryptPasswordDigest): bool {
		if ($this->mysqlConnection->connect_error) {
			die("Connection failed: " . $this->mysqlConnection->connect_error);
		}
		$sqlStatement = $this->mysqlConnection->prepare("INSERT INTO users VALUES(NULL, ?, ?, ?)");
		$sqlStatement->bind_param('sss', $bcryptPasswordDigest, $email, $name);
		return $sqlStatement->execute();
	}

	/**
	 * @param string $email
	 * @param string $name
	 * @param string $bcryptPasswordDigest
	 * @return bool
	 * @throws PDOException
	 *
	 * For anyone that wants a PDO example.
	 */
	/*public function saveUserSecurePDO(string $email, string $name, string $bcryptPasswordDigest): bool {
		$characterSet = 'utf8mb4';
		$host = '127.0.0.1';
		$databaseName = 'new_co';
		$databaseUser = 'root';
		$databasePassword = '';

		$dataSourceName = "mysql:host=$host;dbname=$databaseName;charset=$characterSet"; # DSN contains the info required to make a connection to the DB.

		$pdo = new PDO($dataSourceName, $databaseUser, $databasePassword); # see https://www.php.net/manual/en/pdo.construct.php
		$pdoStatement = $pdo->prepare("INSERT INTO users VALUES (NULL, :bcryptPasswordDigest, :email, :name);");
		$pdoStatement->bindParam(':bcryptPasswordDigest', $bcryptPasswordDigest);
		$pdoStatement->bindParam(':email', $email);
		$pdoStatement->bindParam(':name', $name);
		return $pdoStatement->execute();
	}*/

}
