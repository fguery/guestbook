<?php
declare(strict_types=1);

namespace Bark\Model;

class Guestbook
{
    /** @var \PDO */
    private $db;

    /**
     * Employee constructor.
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Called only by tests
     */
    public function init()
    {
        $this->db->exec(
            <<<SQL
DROP TABLE IF EXISTS guestbook;
CREATE TABLE guestbook (
 id SERIAL PRIMARY KEY,
 name VARCHAR(50),
 email VARCHAR(50),
 creation_date timestamp,
 comment TEXT
);

CREATE INDEX IF NOT EXISTS idx_creation_date ON guestbook(creation_date);
SQL
        );
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getLastMessages(int $limit = 10)
    {
        $query = $this->db->prepare(
            "SELECT * FROM guestbook ORDER BY creation_date LIMIT :limit"
        );
        $query->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $query->execute([':limit' => $limit]);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        foreach (['name', 'email', 'comment'] as $field) {
            if (!isset($data[$field])) {
                $data[$field] = null;
            }
        }

        $query = $this->db->prepare(
            'INSERT INTO guestbook
            (name, email, comment, creation_date)
            VALUES (:name, :email, :comment, NOW())'
        );

        $query->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':comment' => $data['comment'],
        ]);
        return (int)$this->db->lastInsertId();
    }
}
