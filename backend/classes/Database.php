<?php

class Database

{
    public function __construct()
    {
    }
    public static function dbConnect($database, $dbType = null, $op = [])
    {
        if ($dbType == null)
            $dbType = $_ENV["DB_TYPE"];
        if (count($op) == 0) { //op - options
            $op = [
                "host" => $_ENV["DB_HOST"],
                "username" => $_ENV["DB_USER"],
                "password" => $_ENV["DB_PASSWORD"]
            ];
        }

        if ($dbType == "sqlServer") {
            $connectionInfo = array('Database' => $database, 'UID' => $op["username"], "PWD" => $op["password"], 'ReturnDatesAsStrings' => true);
            $conn = sqlsrv_connect($op["host"], $connectionInfo);
            if ($conn === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            return $conn;
        }
        if ($dbType == "mysql") {
            // Create connection
            $conn = new mysqli($op["host"], $op["username"], $op["password"], $database);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                exit();
            }
            return $conn;
        }
        return false;
    }


    public static function select($tabel, $conn, $collumn, $where, $customFrom = "*", $sql = "")
    {
        $params = [];
        $types = "";
        if (strlen($sql) == 0) {
            $sql = " SELECT ";

            if (count($collumn) == 0) {
                $sql .= " $customFrom  FROM $tabel ";
            } else {
                if ($customFrom == "*") {
                    foreach ($collumn as $key => $value) {
                        $sql .= " " . $value . " , ";
                    }
                    $sql = substr($sql, 0, -2);  // Removes the last 2 characters
                } else {
                    $sql .= " $customFrom ";
                }
                $sql .= " FROM $tabel ";
            }

            if (count($where) > 0) {
                $sql .= " WHERE ";
                foreach ($where as $key => $value) {
                    $sql .= " " . $value["key"] . " = ? AND ";
                    array_push($params, $value["value"]);
                    if (array_key_exists("type", $value)) {
                        $types .= $value["type"];
                    } else {
                        $types .= "s";
                    }
                }
                $sql = substr($sql, 0, -4);  // Removes the last 2 characters
            }
        } else {
            foreach ($where as $key => $value) {
                array_push($params, $value["value"]);
                if (array_key_exists("type", $value)) {
                    $types .= $value["type"];
                } else {
                    $types .= "s";
                }
            }
        }

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        if (strlen($types) > 0) {
            $stmt->bind_param($types, ...array_values($params));
        }
        if ($stmt->execute()) {
            return $stmt->get_result();
        } else {
            return false;
        }
    }


    public static function insert($tabel, $conn, $collumn)
    {
        $types = "";
        $sql = " INSERT INTO $tabel (";
        if (count($collumn) == 0) {
            return false;
        }

        $params = [];
        $values = " ( ";
        foreach ($collumn as $key => $value) {
            $sql .= " " . $value["key"] . ", ";
            $values .= " ? , ";
            if (array_key_exists("type", $value)) {
                $types .= $value["type"];
            } else {
                $types .= "s";
            }
            array_push($params, $value["value"]);
        }
        $sql = substr($sql, 0, -2) . " )";  // Removes the last 2 characters
        $values = " VALUES " . substr($values, 0, -2) . " )";  // Removes the last 2 characters

        $sql = $sql . $values;
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        if (strlen($types) > 0) {
            $stmt->bind_param($types, ...array_values($params));
        }
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }


    public static function update($tabel, $conn, $collumn, $where)
    {
        $types = "";
        $sql = " UPDATE $tabel SET ";
        if (count($collumn) == 0) {
            return false;
        }

        $params = [];
        foreach ($collumn as $key => $value) {
            $sql .= " " . $value["key"] . " = ?, ";
            $types .= "s";
            array_push($params, $value["value"]);
        }
        $sql = substr($sql, 0, -2);  // Removes the last 2 characters

        if (count($where) > 0) {
            $sql .= " WHERE ";

            foreach ($where as $key => $value) {
                $sql .= " " . $value["key"] . " = ?, ";
                $types .= "s";
                array_push($params, $value["value"]);
            }
            $sql = substr($sql, 0, -2);  // Removes the last 2 characters
        }

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            // ekko($stmt);
            return false;
        }
        if (strlen($types) > 0) {
            $stmt->bind_param($types, ...array_values($params));
        }
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
