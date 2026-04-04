<?php
require 'database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0 || empty($_POST)) {
    header('Location: ../users.php');
    exit;
}

$name       = trim($_POST['name']       ?? '');
$enrollment = trim($_POST['enrollment'] ?? '');
$email      = trim($_POST['email']      ?? '');
$phone      = trim($_POST['phone']      ?? '');

$errors = [];

if ($name === '' || mb_strlen($name) > 80) {
    $errors[] = 'nome_invalido';
}

if ($enrollment === '' || mb_strlen($enrollment) > 14) {
    $errors[] = 'matricula_invalida';
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'email_invalido';
}

if ($phone !== '' && !preg_match('/^\d{10,11}$/', $phone)) {
    $errors[] = 'telefone_invalido';
}

if (!empty($errors)) {
    header('Location: ../editUser.php?id=' . $id . '&error=' . implode(',', $errors));
    exit;
}

Database::update('users', [
    'name'       => $name,
    'enrollment' => $enrollment,
    'email'      => $email,
    'phone'      => $phone,
], ['id', $id]);

header('Location: ../users.php');
