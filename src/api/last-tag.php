<?php
require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../lib/Tag.php';
requireAuth();
header('Content-Type: text/plain; charset=utf-8');
echo e(getLastTag());
