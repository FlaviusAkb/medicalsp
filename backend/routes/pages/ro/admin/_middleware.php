<?php
if (!isset($_SESSION["id"])) {
    header("Location: " . $_ENV["CURRENT_PATH"] . "/");
}
