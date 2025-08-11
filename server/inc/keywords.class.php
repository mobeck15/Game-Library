<?php
//declare(strict_types=1);
$GLOBALS['rootpath']= $GLOBALS['rootpath'] ?? "..";
require_once $GLOBALS['rootpath']."/inc/dataAccess.class.php";

class Keywords implements ArrayAccess, IteratorAggregate, Countable
{
    private array $data = [];
	private $dataAccess;
	
    public function __construct(dataAccess $dataAccess = null, ?int $gameID = null)
    {
		$this->dataAccess = $dataAccess ?? new dataAccess();
		$this->loadKeywords($gameID);
    }
	
	private function loadKeywords(?int $gameID = null ): void
	{
		$statement = $this->dataAccess->getKeywords($gameID);
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $this->data[$row['ProductID']][$row['KwType']][] = $row['Keyword'];
        }
	}
	
	public static function legacyGet(string $gameID = "", ?mysqli $connection = null)
	{
		$dataAccess = new dataAccess();
		$normalizedGameId = trim($gameID) === "" ? null : (int)$gameID;
		return new self($dataAccess, $normalizedGameId);
	}

    /* === ArrayAccess === */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    /* === IteratorAggregate === */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    /* === Countable === */
    public function count(): int
    {
        return count($this->data);
    }

    /* === Optional OOP API === */
    public function getByProductAndType(int $productId, string $type): array
    {
        return $this->data[$productId][$type] ?? [];
    }
}
