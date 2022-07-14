<?php

namespace src\Repositories;

require_once 'Repository.php';
require_once __DIR__ . '/../Models/Manager.php';

use src\Models\Manager;

class ManagerRepository extends Repository {

	/**
	 * @param int $user_id
	 * @return array
	 */
	public function getManagerForUser(int $user_id): array {
		$sqlStatement = $this->mysqlConnection->prepare("SELECT id, courseName, checked, author_id FROM manager WHERE author_id = ?");
		$sqlStatement->bind_param('i', $user_id);
		$sqlStatement->execute();
		$resultSet = $sqlStatement->get_result();

		$courses = [];
		while ($row = $resultSet->fetch_assoc()) {
			$courses[] = new Manager($row);
		}

		return $courses;
	}

	/**
	 * @param string $courseName
	 * @param bool $checked
	 * @param int $user_id
	 * @return bool
	 */
	public function saveCourse(string $courseName, bool $checked, int $user_id): bool {
		$sqlStatement = $this->mysqlConnection->prepare("INSERT INTO manager VALUES(NULL, ?, ?, ?)");
		$sqlStatement->bind_param('sbi', $courseName, $checked, $user_id);
		return $sqlStatement->execute();
	}

	public function updateCourse(string $originalCourseName, string $updatedName) {
		$sqlStatement = $this->mysqlConnection->prepare("UPDATE manager SET courseName=? WHERE courseName=?");
		$sqlStatement->bind_param('ss', $updatedName, $originalCourseName);
		return $sqlStatement->execute();
	}

	public function deleteCourse(string $courseName) {
		$sqlStatement = $this->mysqlConnection->prepare("DELETE FROM manager WHERE courseName=?");
		$sqlStatement->bind_param('s', $courseName);
		return $sqlStatement->execute();
	}

	public function updateStatus(bool $checked, string $courseName) {
		$sqlStatement = $this->mysqlConnection->prepare("UPDATE manager SET checked=? WHERE courseName=?");
		$sqlStatement->bind_param('bs', $checked, $courseName);
		return $sqlStatement->execute();
	}

}
