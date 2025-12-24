<?php

class Sertificate {

    private $pdo;
    protected $table = 'sertificates';

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function firstSix(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM sertificates LIMIT 6');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate(int $limit, int $offset): array {
        $stmt = $this->pdo->prepare('SELECT * FROM sertificates LIMIT :limit OFFSET :offset');
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}