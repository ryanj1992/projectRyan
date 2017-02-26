<?php
session_start();
if (isset($_SESSION['userSession'])) {
	unset($_SESSION['userSession']);
	session_destroy();
	header("Location: /");
} else if (!isset($_SESSION['userSession'])) {
	header("Location: login");
}
