<?php

session_destroy();
setcookie("PHPSESSID", "", time() - 3600, "/");

header('Location: ' . $_ENV["CURRENT_PATH"] . "/");
