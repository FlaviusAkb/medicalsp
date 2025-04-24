<?php


class Auth
{
    public static function middleware($accessLvl, $path, $page, $class, $redirect = "")
    {
        if ($path == $_SESSION["REQUEST"]) {
            if (self::accessControl($accessLvl)) {
                call_user_func_array($class, [$path, $page]);
            } else {
                header("Location: " . $_ENV["CURRENT_PATH"] . "/" . $redirect);
                exit();
            }
        }
    }



    public static function accessControl($accessLvl)
    {
        foreach ($accessLvl as $item) {
            if (method_exists(self::class, $item)) {
                if (call_user_func(array(self::class, $item))) // if any of the accessLvl given is true then send true
                    return true;
            } else {
                exit("method '$item' does not exist");
            }
        }

        return false; // it did not hit any of the access lvls so send false back
    }


    public static function isLogged()
    {
        if (array_key_exists("grade", $_SESSION)) {
            return true;
        } else {
            return false;
        }
    }

    public static function root()
    {
        if (self::isLogged() && $_SESSION["grade"] == -1) {
            return true;
        } else {
            return false;
        }
    }

    public static function admin()
    {
        if (self::isLogged() && $_SESSION["grade"] == 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function underAdmin()
    {
        if ((self::isLogged() && $_SESSION["grade"] == 1) || self::admin()) {
            return true;
        } else {
            return false;
        }
    }

    public static function editor()
    {
        if ((self::isLogged() && $_SESSION["grade"] == 5) || self::admin()) {
            return true;
        } else {
            return false;
        }
    }

    public static function visitor()
    {
        if ((self::isLogged() && $_SESSION["grade"] == 100) || self::admin()) {
            return true;
        } else {
            return false;
        }
    }

    public static function demo()
    {
        if ((self::isLogged() && $_SESSION["grade"] == 150) || self::admin()) {
            return true;
        } else {
            return false;
        }
    }
}
