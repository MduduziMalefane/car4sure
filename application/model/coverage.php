<?php

namespace Care4Sure\Application\Model;

class Coverage
{
    private int $coverageId;
    private string $coverageName;
    private string $coverageDescription;
    private bool $deleted;

    public function __construct()
    {
        $this->coverageId = 0;
        $this->coverageName = '';
        $this->coverageDescription = '';
        $this->deleted = false;
    }

    public function getCoverageId(): int
    {
        return $this->coverageId;
    }

    public function setCoverageId(int $coverageId): self
    {
        $this->coverageId = $coverageId;
        return $this;
    }

    public function getCoverageName(): string
    {
        return $this->coverageName;
    }

    public function setCoverageName(string $coverageName): self
    {
        $this->coverageName = $coverageName;
        return $this;
    }

    public function getCoverageDescription(): string
    {
        return $this->coverageDescription;
    }

    public function setCoverageDescription(string $coverageDescription): self
    {
        $this->coverageDescription = $coverageDescription;
        return $this;
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function save(): bool
    {
        if ($this->coverageId == 0)
        {
            return $this->create();
        }
        else
        {
            return $this->update();
        }
    }

    private function create(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO coverage (CoverageName, CoverageDescription, Deleted) 
        VALUES (?, ?, ?)";

        $con->pushParam($this->coverageName);
        $con->pushParam($this->coverageDescription);
        $con->pushParam((int) $this->deleted);

        if ($con->executeNoneQuery($query) > 0)
        {
            $this->coverageId = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    private function update(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE coverage SET CoverageName = ?, CoverageDescription = ?, Deleted = ? WHERE CoverageId = ?";

        $con->pushParam($this->coverageName);
        $con->pushParam($this->coverageDescription);
        $con->pushParam((int) $this->deleted);
        $con->pushParam($this->coverageId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function delete(int $coverageId): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE coverage SET Deleted = 1 WHERE CoverageId = ? AND Deleted = 0";
        $con->pushParam($coverageId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function getByCoverageId(int $coverageId): ?self
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM coverage WHERE CoverageId = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($coverageId);

        $result = $con->queryObject($query);

        if ($result)
        {
            return self::map($result);
        }

        return null;
    }

    public static function getAll(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM coverage WHERE Deleted = 0";

        $result = $con->queryAllObject($query);

        $coverages = [];
        foreach ($result as $coverage)
        {
            $coverages[] = self::map($coverage);
        }

        return $coverages;
    }

    private static function map($coverage): self
    {
        return (new self())
            ->setCoverageId($coverage->CoverageId)
            ->setCoverageName($coverage->CoverageName)
            ->setCoverageDescription($coverage->CoverageDescription)
            ->setDeleted((bool) $coverage->Deleted);
    }



    public static function getByCoverageIdAsJson(int $coverageId): ?object
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM coverage WHERE coverageId = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($coverageId);

        $result = $con->queryObject($query);
        $coverage = null;

        if ($result)
        {
            $coverage = new \stdClass();
            $coverage->coverageId = $result->CoverageId;
            $coverage->coverageName = $result->CoverageName;
            $coverage->coverageDescription = $result->CoverageDescription;
        }

        return $coverage;
    }

    public static function getAllAsJson(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM coverage WHERE Deleted = 0";

        $results = $con->queryAllObject($query);

        $coverages = [];
        foreach ($results as $result)
        {
            $coverage = new \stdClass();
            $coverage->coverageId = $result->CoverageId;
            $coverage->coverageName = $result->CoverageName;
            $coverage->coverageDescription = $result->CoverageDescription;

            $coverages[] = $coverage;
        }

        return $coverages;

    }


    public static function mapFromRequest($request): ?self
    {
        $coverage = new self();

        if (\ValidationClass::ValidateFullNumber($request->coverageId))
        {
            $coverage->setCoverageId((int) $request->coverageId);
        }

        if (!\ValidationClass::ValidateAlphaNumeric($request->coverageName))
        {
            return null;
        }
        else
        {
            $coverage->setCoverageName($request->coverageName);
        }

        if (!empty($request->coverageDescription) && !\ValidationClass::ValidateAlphaNumeric($request->coverageDescription))
        {
            return null;
        }
        else
        {
            $coverage->setCoverageDescription($request->coverageDescription);
        }

        return $coverage;
    }

}