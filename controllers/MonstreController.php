<?php
require_once __DIR__ . '/../models/Database.php';

class MonstreController {
	public function index() {
		$db = Database::getConnection();
		$monsters = $db->query('SELECT * FROM Monster')->fetchAll();
		require __DIR__ . '/../views/admin/monstres_list.php';
	}

	public function add() {
		require __DIR__ . '/../views/admin/monstre_add.php';
	}

	public function store() {
		$db = Database::getConnection();
		$name = $_POST['name'] ?? '';
		$pv = $_POST['pv'] ?? 0;
		$mana = $_POST['mana'] ?? 0;
		$initiative = $_POST['initiative'] ?? 0;
		$strength = $_POST['strength'] ?? 0;
		$attack = $_POST['attack'] ?? '';
		$xp = $_POST['xp'] ?? 0;
		$stmt = $db->prepare('INSERT INTO Monster (name, pv, mana, initiative, strength, attack, xp) VALUES (?, ?, ?, ?, ?, ?, ?)');
		$stmt->execute([$name, $pv, $mana, $initiative, $strength, $attack, $xp]);
		$root = dirname($_SERVER['SCRIPT_NAME']);
		$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
		header('Location: ' . $root . '/admin/monstres');
		exit();
	}

	public function edit($id) {
		$db = Database::getConnection();
		$stmt = $db->prepare('SELECT * FROM Monster WHERE id = ?');
		$stmt->execute([$id]);
		$monster = $stmt->fetch();
		require __DIR__ . '/../views/admin/monstre_edit.php';
	}

	public function update($id) {
		$db = Database::getConnection();
		$name = $_POST['name'] ?? '';
		$pv = $_POST['pv'] ?? 0;
		$mana = $_POST['mana'] ?? 0;
		$initiative = $_POST['initiative'] ?? 0;
		$strength = $_POST['strength'] ?? 0;
		$attack = $_POST['attack'] ?? '';
		$xp = $_POST['xp'] ?? 0;
		$stmt = $db->prepare('UPDATE Monster SET name = ?, pv = ?, mana = ?, initiative = ?, strength = ?, attack = ?, xp = ? WHERE id = ?');
		$stmt->execute([$name, $pv, $mana, $initiative, $strength, $attack, $xp, $id]);
		$root = dirname($_SERVER['SCRIPT_NAME']);
		$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
		header('Location: ' . $root . '/admin/monstres');
		exit();
	}

	public function delete($id) {
		$db = Database::getConnection();
		$stmt = $db->prepare('DELETE FROM Monster WHERE id = ?');
		$stmt->execute([$id]);
		header('Location: /admin/monstres');
		exit();
	}
}
