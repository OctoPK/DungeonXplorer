<?php
require_once __DIR__ . '/../models/Database.php';

class AdminController {
	public function index() {
		$db = Database::getConnection();
		$chapters = $db->query('SELECT * FROM Chapter')->fetchAll();
		$items = $db->query('SELECT * FROM Items')->fetchAll();
		$monsters = $db->query('SELECT * FROM Monster')->fetchAll();
		require __DIR__ . '/../views/admin/dashboard.php';
	}
}
