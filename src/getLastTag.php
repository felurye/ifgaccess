<?php
require_once 'func/auth.php';
require_once 'func/tag.php';
requireAuth();
header('Content-Type: text/plain; charset=utf-8');
echo e(getLastTag());
