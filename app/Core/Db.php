<?php

namespace App\Core;

use PDO;
use PDOException;

class Db extends PDO
{
    // Informations de connexion en BDD
    private const DB_HOST = "mvcdebutexo-db-1";
    private const DB_USER = "root";
    private const DB_PASSWORD = "root";
    private const DB_NAME = "mvc_data";

    private static ?Db $instance = null;

    public function __construct()
    {
        // Lien de connexion en BDD
        $dsn = "mysql:dbname=" . Db::DB_NAME . ";host=" . Db::DB_HOST . ";charset=utf8mb4";

        // On essaie de se connecter en BDD
        try {
            parent::__construct($dsn, Db::DB_USER, Db::DB_PASSWORD);

            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAME utf8');
        } catch (PDOException $error) {
            // S'il y a une erreur on affiche le message d'erreur
            die('Erreur: ' . $error->getMessage());
        }
    }

    /**
     * Permet de récupérer l'instance de la connexion en BDD 
     * ou d'en créer une si elle n'existe pas
     *
     * @return self
     */
    public static function checkInstance(): self
    {
        // On vérifie si l'instance est déjà créée ou non
        if (Db::$instance === null) {
            // On la créée
            Db::$instance = new Db();
        }

        // On retourne l'instance
        return Db::$instance;
    }
}
