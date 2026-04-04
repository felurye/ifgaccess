<?php
require_once __DIR__ . '/../lib/Tag.php';

$tag = isset($_POST['tagResult']) ? trim($_POST['tagResult']) : '';
saveTag($tag);
