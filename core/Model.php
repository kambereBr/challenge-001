<?php
namespace Core;

use Core\Database;
use PDO;

abstract class Model
{
    protected static $table;
    protected static $primaryKey = 'id';

    /**
     * Get all records, optionally filtered by criteria.
     *
     * @param array $criteria Column-value pairs for filtering.
     * @return array Model instances.
     */
    public static function all(array $criteria = []): array
    {
        $db = Database::getInstance()->pdo();
        $sql = "SELECT * FROM " . static::$table;
        if ($criteria) {
            // Build the WHERE clause based on criteria
            $conds = array_map(fn($c) => "$c = :$c", array_keys($criteria));
            $sql .= ' WHERE ' . implode(' AND ', $conds);
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($criteria);
        return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    /**
     * Find a record by primary key.
     *
     * @param mixed $id The value of the primary key.
     * @return static|null Returns the model instance or null if not found.
     */
    public static function find($id)
    {
        $db = Database::getInstance()->pdo();
        $stmt = $db->prepare("SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject(static::class);
    }

    /**
     * Save the model to the database (insert or update).
     *
     * @return $this Returns the model instance after saving.
     */
    public function save()
    {
        $db = Database::getInstance()->pdo();
        $props = get_object_vars($this);

        if (isset($this->{static::$primaryKey})) {
            // Update existing record
            $sets = array_filter(array_keys($props), fn($k) => $k !== static::$primaryKey);
            $sql = "UPDATE " . static::$table . " SET "
                . implode(', ', array_map(fn($k) => "$k = :$k", $sets))
                . " WHERE " . static::$primaryKey . " = :" . static::$primaryKey;
        } else {
            // Create new record
            $keys = array_keys($props);
            $sql = "INSERT INTO " . static::$table
                . " (" . implode(', ', $keys) . ") VALUES (:" . implode(', :', $keys) . ")";
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($props);
        if (! isset($this->{static::$primaryKey})) {
            $this->{static::$primaryKey} = (int)$db->lastInsertId();
        }
        return $this;
    }
}
