<?php

namespace Care4Sure\Application\Model;

class CustomerPolicyVehicle
{
    private int $policyVehicleId;
    private int $customerPolicyId;
    private int $customerVehicleId;
    private int $policyCoverageId;
    private bool $deleted;

    public function __construct()
    {
        $this->policyVehicleId = 0;
        $this->customerPolicyId = 0;
        $this->customerVehicleId = 0;
        $this->policyCoverageId = 0;
        $this->deleted = false;
    }

    public function getPolicyVehicleId(): int
    {
        return $this->policyVehicleId;
    }

    public function setPolicyVehicleId(int $policyVehicleId): self
    {
        $this->policyVehicleId = $policyVehicleId;
        return $this;
    }

    public function getCustomerPolicyId(): int
    {
        return $this->customerPolicyId;
    }

    public function setCustomerPolicyId(int $customerPolicyId): self
    {
        $this->customerPolicyId = $customerPolicyId;
        return $this;
    }

    public function getCustomerVehicleId(): int
    {
        return $this->customerVehicleId;
    }

    public function setCustomerVehicleId(int $customerVehicleId): self
    {
        $this->customerVehicleId = $customerVehicleId;
        return $this;
    }

    public function getPolicyCoverageId(): int
    {
        return $this->policyCoverageId;
    }

    public function setPolicyCoverageId(int $policyCoverageId): self
    {
        $this->policyCoverageId = $policyCoverageId;
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
        if ($this->policyVehicleId == 0)
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
        $query = "INSERT INTO customerpolicyvehicle (CustomerPolicyId, CustomerVehicleId, PolicyCoverageId, Deleted) 
                  VALUES (?, ?, ?, ?)";

        $con->pushParam($this->customerPolicyId);
        $con->pushParam($this->customerVehicleId);
        $con->pushParam($this->policyCoverageId);
        $con->pushParam((int) $this->deleted);

        if ($con->executeNoneQuery($query) > 0)
        {
            $this->policyVehicleId = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    private function update(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE customerpolicyvehicle SET CustomerPolicyId = ?, CustomerVehicleId = ?, PolicyCoverageId = ?, Deleted = ? 
                  WHERE PolicyVehicleId = ?";

        $con->pushParam($this->customerPolicyId);
        $con->pushParam($this->customerVehicleId);
        $con->pushParam($this->policyCoverageId);
        $con->pushParam((int) $this->deleted);
        $con->pushParam($this->policyVehicleId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function delete(int $policyVehicleId): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE customerpolicyvehicle SET Deleted = 1 WHERE PolicyVehicleId = ? AND Deleted = 0";
        $con->pushParam($policyVehicleId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function getByPolicyVehicleId(int $policyVehicleId): ?self
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM customerpolicyvehicle WHERE PolicyVehicleId = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($policyVehicleId);

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
        $query = "SELECT * FROM customerpolicyvehicle WHERE Deleted = 0";

        $result = $con->queryAllObject($query);

        $vehicles = [];
        foreach ($result as $vehicle)
        {
            $vehicles[] = self::map($vehicle);
        }

        return $vehicles;
    }

    private static function map($vehicle): self
    {
        return (new self())
            ->setPolicyVehicleId($vehicle->PolicyVehicleId)
            ->setCustomerPolicyId($vehicle->CustomerPolicyId)
            ->setCustomerVehicleId($vehicle->CustomerVehicleId)
            ->setPolicyCoverageId($vehicle->PolicyCoverageId)
            ->setDeleted((bool) $vehicle->Deleted);
    }
}