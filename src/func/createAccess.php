<?php
require 'database.php';
require 'tag.php';
date_default_timezone_set('America/Sao_Paulo');

$tag  = isset($_POST['tagResult']) ? trim($_POST['tagResult']) : '';
$room = isset($_POST['room'])      ? trim($_POST['room'])      : '';

if ($tag === '' || $room === '') {
    http_response_code(400);
    echo 'NOK';
    exit;
}

saveTag($tag);

$dateNow = date('Y-m-d H:i:s');

$userResult = Database::query('SELECT * FROM users WHERE tag = ?', [$tag]);

if (empty($userResult)) {
    echo 'NOK';
    exit;
}

$userId = $userResult[0]['id'];

$checkinAnotherUser = Database::query(
    'SELECT id FROM access WHERE room = ? AND user_id != ? AND checkout IS NULL ORDER BY checkin DESC',
    [$room, $userId]
);

if (!empty($checkinAnotherUser)) {
    echo 'Wait for another user to checkout';
    exit;
}

$checkinResult = Database::query(
    'SELECT * FROM access WHERE user_id = ? AND checkout IS NULL ORDER BY checkin DESC',
    [$userId]
);

if (empty($checkinResult)) {
    echo 'Checkin';
    Database::create('access', [
        'user_id' => $userId,
        'checkin' => $dateNow,
        'room'    => $room,
    ]);
    exit;
}

echo 'Checkout';
Database::update('access', ['checkout' => $dateNow], ['id', $checkinResult[0]['id']]);
