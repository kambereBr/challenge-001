<?php
namespace App\Migrations;

use Core\Database;
use Core\MigrationInterface;

class CreateStoresTable implements MigrationInterface
{
    public function up(): void
    {
        $db = Database::getInstance()->pdo();
        $sql = <<<'SQL'
CREATE TABLE IF NOT EXISTS stores (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL UNIQUE,
  slug TEXT NOT NULL UNIQUE,
  address_line1 TEXT NOT NULL,
  address_line2 TEXT,
  city TEXT NOT NULL,
  state_region TEXT NOT NULL,
  country TEXT NOT NULL,
  phone TEXT,
  email TEXT,
  created_at TEXT NOT NULL DEFAULT (datetime('now')),
  updated_at TEXT NOT NULL DEFAULT (datetime('now'))
);
SQL;
        $db->exec($sql);
    }

    public function down(): void
    {
        Database::getInstance()->pdo()->exec("DROP TABLE IF EXISTS stores");
    }
}