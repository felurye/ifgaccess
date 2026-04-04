<?php
require 'database.php';

if (empty($_POST)) {
    header('Location: ../createUser.php');
    exit;
}

$tag        = trim($_POST['tag']        ?? '');
$name       = trim($_POST['name']       ?? '');
$enrollment = trim($_POST['enrollment'] ?? '');
$email      = trim($_POST['email']      ?? '');
$phone      = trim($_POST['phone']      ?? '');

$errors = [];

if (!preg_match('/^[0-9A-Fa-f]{8}$/', $tag)) {
    $errors[] = 'tag_invalida';
}

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
    header('Location: ../createUser.php?error=' . implode(',', $errors));
    exit;
}

$existing = Database::query('SELECT id FROM users WHERE tag = ?', [strtoupper($tag)]);
if (!empty($existing)) {
    header('Location: ../createUser.php?error=tag_existente');
    exit;
}

Database::create('users', [
    'tag'        => strtoupper($tag),
    'name'       => $name,
    'enrollment' => $enrollment,
    'email'      => $email,
    'phone'      => $phone,
]);

header('Location: ../users.php');
