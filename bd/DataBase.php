<?php

use PDO;
use RuntimeException;

class Database {
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO {
        if (self::$pdo === null) {
            $dbFile = __DIR__ .'/database.sqlite';
            if (!file_exists($dbFile)) {
                throw new RuntimeException("Le fichier de la base de données n'a pas été trouvé : $dbFile");
            }
            self::$pdo = new PDO('sqlite:' . $dbFile);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$pdo;
    }
}

?>
