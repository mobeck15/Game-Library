<?php

class AutocompleteHandler
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function search(string $queryType, string $term): array
    {
        $queryMap = [
            'Game'      => ['sql' => "SELECT `Game_ID` AS id, `Title` AS label FROM `gl_products` WHERE Title LIKE ?", 'distinct' => false],
            'Trans'     => ['sql' => "SELECT `TransID` AS id, `Title` AS label FROM `gl_transactions` WHERE Title LIKE ?", 'distinct' => false],
            'DRM'       => ['sql' => "SELECT DISTINCT `DRM` AS label FROM `gl_items` WHERE DRM LIKE ?", 'distinct' => true],
            'OS'        => ['sql' => "SELECT DISTINCT `OS` AS label FROM `gl_items` WHERE OS LIKE ?", 'distinct' => true],
            'Library'   => ['sql' => "SELECT DISTINCT `Library` AS label FROM `gl_items` WHERE Library LIKE ?", 'distinct' => true],
            'Series'    => ['sql' => "SELECT DISTINCT `Series` AS label FROM `gl_products` WHERE Series LIKE ?", 'distinct' => true],
            'Type'      => ['sql' => "SELECT DISTINCT `Type` AS label FROM `gl_products` WHERE Type LIKE ?", 'distinct' => true],
            'Developer' => ['sql' => "SELECT DISTINCT `Developer` AS label FROM `gl_products` WHERE Developer LIKE ?", 'distinct' => true],
            'Publisher' => ['sql' => "SELECT DISTINCT `Publisher` AS label FROM `gl_products` WHERE Publisher LIKE ?", 'distinct' => true],
            'Store'     => ['sql' => "SELECT DISTINCT `Store` AS label FROM `gl_transactions` WHERE Store LIKE ?", 'distinct' => true],
        ];

        if (!isset($queryMap[$queryType])) {
            throw new InvalidArgumentException("Invalid query type: $queryType");
        }

        $sql = $queryMap[$queryType]['sql'];
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException("SQL prepare error: " . $this->conn->error);
        }

        $searchTerm = '%' . $term . '%';
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        $returnArr = [];
        while ($row = $result->fetch_assoc()) {
            $returnArr[] = $row;
        }

        return $returnArr;
    }
}
