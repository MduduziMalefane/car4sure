<?php

namespace Care4Sure\Application\Model;

class Policy
{
    private int $policyNo;
    private string $policyName;
    private string $policyDescription;
    private string $policyType;
    private float $policyCost;
    private bool $deleted;

    public function __construct()
    {
        $this->policyNo = 0;
        $this->policyName = '';
        $this->policyDescription = '';
        $this->policyType = 'Auto';
        $this->policyCost = 0.00;
        $this->deleted = false;
    }

    public function getPolicyNo(): int
    {
        return $this->policyNo;
    }

    public function setPolicyNo(int $policyNo): self
    {
        $this->policyNo = $policyNo;
        return $this;
    }

    public function getPolicyName(): string
    {
        return $this->policyName;
    }

    public function setPolicyName(string $policyName): self
    {
        $this->policyName = $policyName;
        return $this;
    }

    public function getPolicyDescription(): string
    {
        return $this->policyDescription;
    }

    public function setPolicyDescription(string $policyDescription): self
    {
        $this->policyDescription = $policyDescription;
        return $this;
    }

    public function getPolicyType(): string
    {
        return $this->policyType;
    }

    public function setPolicyType(string $policyType): self
    {
        $this->policyType = $policyType;
        return $this;
    }

    public function getPolicyCost(): float
    {
        return $this->policyCost;
    }

    public function setPolicyCost(float $policyCost): self
    {
        $this->policyCost = $policyCost;
        return $this;
    }

    public function isDeleted(): bool
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
        if ($this->policyNo == 0)
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
        $query = "INSERT INTO policy (PolicyName, PolicyDescription, PolicyType, PolicyCost, Deleted) 
                  VALUES (?, ?, ?, ?, ?)";

        $con->pushParam($this->policyName);
        $con->pushParam($this->policyDescription);
        $con->pushParam($this->policyType);
        $con->pushParam($this->policyCost);
        $con->pushParam((int) $this->deleted);

        if ($con->executeNoneQuery($query) > 0)
        {
            $this->policyNo = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    private function update(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE policy SET PolicyName = ?, PolicyDescription = ?, PolicyType = ?, PolicyCost = ?, Deleted = ? 
                  WHERE PolicyNo = ?";

        $con->pushParam($this->policyName);
        $con->pushParam($this->policyDescription);
        $con->pushParam($this->policyType);
        $con->pushParam($this->policyCost);
        $con->pushParam((int) $this->deleted);
        $con->pushParam($this->policyNo);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function delete(int $policyNo): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE policy SET Deleted = 1 WHERE PolicyNo = ? AND Deleted = 0";
        $con->pushParam($policyNo);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function getByPolicyNo(int $policyNo): ?self
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policy WHERE PolicyNo = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($policyNo);

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
        $query = "SELECT * FROM policy WHERE Deleted = 0";

        $result = $con->queryAllObject($query);

        $policies = [];
        foreach ($result as $policy)
        {
            $policies[] = self::map($policy);
        }

        return $policies;
    }

    private static function map($policy): self
    {
        return (new self())
            ->setPolicyNo($policy->PolicyNo)
            ->setPolicyName($policy->PolicyName)
            ->setPolicyDescription($policy->PolicyDescription)
            ->setPolicyType($policy->PolicyType)
            ->setPolicyCost($policy->PolicyCost)
            ->setDeleted((bool) $policy->Deleted);
    }


    public static function getByPolicyNoAsJson(int $policyNo): ?object
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policy WHERE PolicyNo = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($policyNo);

        $result = $con->queryObject($query);
        $policy = null;

        if ($result)
        {
            $policy = new \stdClass();
            $policy->policyNo = $result->PolicyNo;
            $policy->policyName = $result->PolicyName;
            $policy->policyDescription = $result->PolicyDescription;
            $policy->policyType = $result->PolicyType;
            $policy->policyCost = $result->PolicyCost;
        }

        return $policy;
    }

    public static function getAllAsJson(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policy WHERE Deleted = 0";

        $results = $con->queryAllObject($query);

        $policies = [];
        foreach ($results as $result)
        {
            $policy = new \stdClass();
            $policy->policyNo = $result->PolicyNo;
            $policy->policyName = $result->PolicyName;
            $policy->policyDescription = $result->PolicyDescription;
            $policy->policyType = $result->PolicyType;
            $policy->policyCost = $result->PolicyCost;

            $policies[] = $policy;
        }

        return $policies;
    }


    public static function mapFromRequest($request): ?self
    {
        $policy = new self();

        if (\ValidationClass::ValidateFullNumber($request->policyNo))
        {
            $policy->setPolicyNo((int) $request->policyNo);
        }

        if (!\ValidationClass::ValidateAlphaNumeric($request->policyName))
        {
            return null;
        }
        else
        {
            $policy->setPolicyName($request->policyName);
        }

        if (!\ValidationClass::ValidateAlphaNumeric($request->policyDescription))
        {
            return null;
        }
        else
        {
            $policy->setPolicyDescription($request->policyDescription);
        }

        if (!\ValidationClass::ValidateAlphaNumeric($request->policyType))
        {
            return null;
        }
        else
        {
            $policy->setPolicyType($request->policyType);
        }

        if (!\ValidationClass::ValidateDecimal($request->policyCost))
        {
            return null;
        }
        else
        {
            $policy->setPolicyCost((float) $request->policyCost);
        }


        return $policy;
    }
}