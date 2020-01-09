<?php
session_start();
$_SESSION[$_POST['field']] = $_POST['value'];