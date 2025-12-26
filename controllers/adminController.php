<?php
require_once __DIR__ . '/../models/Database.php';

class AdminController {
	public function index() {
		$db = Database::getConnection();
		$stmtCheckAdmin = $db->prepare('SELECT role FROM Users WHERE id = :id and role = "admin"');
		$stmtCheckAdmin->execute(['id' => $_SESSION['user_id']]);
		$isAdmin = $stmtCheckAdmin->fetch();
		if (!$isAdmin) {
			header('Location: /DungeonXplorer/home');
			exit;
		}
		$chapters = $db->query('SELECT * FROM Chapter')->fetchAll();
		$items = $db->query('SELECT * FROM Items')->fetchAll();
		$monsters = $db->query('SELECT * FROM Monster')->fetchAll();
		require __DIR__ . '/../views/admin/dashboard.php';
	}
}
