<?php

namespace App\Models;

use App\Core\Db;
use DateTime;
use PDOStatement;

abstract class Model extends Db
{
    /**
     * Va stocker le nom de la table sur laquelle on travail
     *
     * @var string|null
     */
    protected ?string $table = null;

    /**
     * Va stocker l'instance de la connexion en BDD
     *
     * @var Db|null
     */
    protected ?Db $database = null;

    /**
     * Fonction pour récupérer les entrées d'une table
     *
     * @return array
     */
    public function findAll(): array
    {
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table")->fetchAll()
        );
    }

    /**
     * Fonction pour chercher une entrée par son ID
     *
     * @param integer $id
     * @return static|boolean
     */
    public function find(int $id): static|bool
    {
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE id = :id", ['id' => $id])->fetch()
        );
    }

    public function findBy(array $filters): array
    {
        // SELECT * FROM users WHERE nom = :nom AND prenom = :prenom
        $champs = [];
        $valeurs = [];

        // On boucle notre tableau de filtre
        foreach ($filters as $key => $value) {
            $champs[] = "$key = :$key";
            $valeurs[$key] = $value;
        }

        $strChamp = implode(' AND ', $champs);

        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE $strChamp", $valeurs)->fetchAll()
        );
    }

    public function create(): PDOStatement|bool
    {
        // INSERT INTO users(nom, prenom, email, password, roles) VALUES (:nom, :prenom, :email, :password, :roles)
        $champs = [];
        $markers = [];
        $valeurs = [];

        foreach ($this as $key => $value) {
            if ($key != 'table' && $key != 'database' && $value !== null) {
                $champs[] = "$key";
                $markers[] = ":$key";

                if (is_array($value)) {
                    $valeurs[$key] = json_encode($value);
                } else if ($value instanceof DateTime) {
                    $valeurs[$key] = $value->format('Y-m-d H:i:s');
                }else if (is_bool($value)) {
                    $valeurs[$key] = (int) $value;
                }else {
                    $valeurs[$key] = $value;
                }
            }
        }

        $strChamp = implode(', ', $champs);
        $strMarker = implode(', ', $markers);

        return $this->runQuery("INSERT INTO $this->table($strChamp) VALUES ($strMarker)", $valeurs);
    }

    public function update(): PDOStatement|bool
    {
        // UPDATE users SET nom = :nom, prenom = :prenom WHERE id = :id
        $champs = [];
        $valeurs = [];

        foreach ($this as $key => $value) {
            if ($key != 'table' && $key != 'database' && $key != 'id' && $value !== null) {
                $champs[] = "$key = :$key";

                if (is_array($value)) {
                    $valeurs[$key] = json_encode($value);
                } else if ($value instanceof DateTime) {
                    $valeurs[$key] = $value->format('Y-m-d H:i:s');
                }else if (is_bool($value)) {
                    $valeurs[$key] = (int) $value;
                }else {
                    $valeurs[$key] = $value;
                }
            }
        }

        /**
         * @var User $this
         */
        $valeurs['id'] = $this->id;

        $strChamp = implode(', ', $champs);

        return $this->runQuery("UPDATE $this->table SET $strChamp WHERE id = :id", $valeurs);
    }



    public function delete(): PDOStatement|bool
    {
        // DELETE FROM users WHERE id = :id
        /**
         * @var User $this
         */
        return $this->runQuery("DELETE FROM $this->table WHERE id = :id", ['id' => $this->id]);
    }




    /**
     * Function pour exécuter n'importe quelle requêtes SQL
     *
     * @param string $sql
     * @param array $params
     * @return PDOStatement|boolean
     */
    protected function runQuery(string $sql, array $params = []): PDOStatement|bool
    {
        // On récupère l'instance de DB (connexion en BDD)
        $this->database = Db::checkInstance();

        if (!empty($params)) {
            // Requête préparée
            $query = $this->database->prepare($sql);
            $query->execute($params);
        } else {
            // Requête simple
            $query = $this->database->query($sql);
        }

        return $query;
    }

    /**
     * Undocumented function
     *
     * @param mixed $query
     * @return array|static|boolean
     */
    public function fetchHydrate(mixed $query): array|static|bool
    {
        if (is_array($query)) {
            $data = array_map(function (object $value): static {
                return (new static())->hydrate($value);
            }, $query);

            return $data;
        } else if (!empty($query)) {
            return (new static())->hydrate($query);
        }

        return $query;
    }

    public function hydrate(array|object $data): static
    {
        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key);

            if ($key === 'roles') {
                $this->$setter($value ? json_decode($value) : null);
            } else if ($key === 'createdAt' || $key === 'updatedAt' && $value) {
                $this->$setter(new DateTime($value));
            }
            else {
                $this->$setter($value);
            }
        }

        return $this;
    }
}
