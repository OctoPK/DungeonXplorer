<?php
require_once __DIR__ . '/../models/Database.php';

class ItemsController {
	public function index() {
		$db = Database::getConnection();
		$items = $db->query('SELECT * FROM Items')->fetchAll();
		require __DIR__ . '/../views/admin/items_list.php';
	}

	public function add() {
		require __DIR__ . '/../views/admin/item_add.php';
	}

	public function store() {
		$db = Database::getConnection();
		$name = $_POST['name'] ?? '';
		$description = $_POST['description'] ?? '';
		$item_type = $_POST['item_type'] ?? '';
		$stmt = $db->prepare('INSERT INTO Items (name, description, item_type) VALUES (?, ?, ?)');
		$stmt->execute([$name, $description, $item_type]);
		header('Location: /admin/items');
		exit();
	}

	public function edit($id) {
		$db = Database::getConnection();
		$stmt = $db->prepare('SELECT * FROM Items WHERE id = ?');
		$stmt->execute([$id]);
		$item = $stmt->fetch();
		require __DIR__ . '/../views/admin/item_edit.php';
	}

	public function update($id) {
		$db = Database::getConnection();
		$name = $_POST['name'] ?? '';
		$description = $_POST['description'] ?? '';
		$item_type = $_POST['item_type'] ?? '';
		$stmt = $db->prepare('UPDATE Items SET name = ?, description = ?, item_type = ? WHERE id = ?');
		$stmt->execute([$name, $description, $item_type, $id]);
		header('Location: /admin/items');
		exit();
	}

	public function delete($id) {
		$db = Database::getConnection();
		$stmt = $db->prepare('DELETE FROM Items WHERE id = ?');
		$stmt->execute([$id]);
		header('Location: /admin/items');
		exit();
	}
}
