<?php
require_once __DIR__ . '/../models/Database.php';

class ChapitreController {
	public function index() {
		$db = Database::getConnection();
		$chapters = $db->query('SELECT * FROM Chapter')->fetchAll();
		require __DIR__ . '/../views/admin/chapitres_list.php';
	}

	public function add() {
		require __DIR__ . '/../views/admin/chapitre_add.php';
	}

	public function store() {
		$db = Database::getConnection();
		$content = $_POST['content'] ?? '';
		$image = $_POST['image'] ?? '';
		$stmt = $db->prepare('INSERT INTO Chapter (content, image) VALUES (?, ?)');
		$stmt->execute([$content, $image]);
		header('Location: /admin/chapitres');
		exit();
	}

	public function edit($id) {
		$db = Database::getConnection();
		$stmt = $db->prepare('SELECT * FROM Chapter WHERE id = ?');
		$stmt->execute([$id]);
		$chapter = $stmt->fetch();
		require __DIR__ . '/../views/admin/chapitre_edit.php';
	}

	public function update($id) {
		$db = Database::getConnection();
		$content = $_POST['content'] ?? '';
		$image = $_POST['image'] ?? '';
		$stmt = $db->prepare('UPDATE Chapter SET content = ?, image = ? WHERE id = ?');
		$stmt->execute([$content, $image, $id]);
		header('Location: /admin/chapitres');
		exit();
	}

	public function delete($id) {
		$db = Database::getConnection();
		$stmt = $db->prepare('DELETE FROM Chapter WHERE id = ?');
		$stmt->execute([$id]);
		header('Location: /admin/chapitres');
		exit();
	}
}
