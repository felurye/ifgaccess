<?php
require 'tag.php';

$tag = isset($_POST['tagResult']) ? trim($_POST['tagResult']) : '';
saveTag($tag);
