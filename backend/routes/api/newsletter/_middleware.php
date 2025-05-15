<?php

if (!isset($_SESSION["id"])) {
    response("Please log in !", 400);
    exit();
}
