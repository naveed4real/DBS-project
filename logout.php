<?php
require_once 'config.php';
session_destroy();
header('Location: /BIA PROJECT/index.php');
exit;
