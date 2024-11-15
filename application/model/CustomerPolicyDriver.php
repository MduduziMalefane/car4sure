<?php

namespace Care4Sure\Application\Model;

class CustomerPolicyDriver
{
    private int $policyDriverId;
    private int $customerPolicyId;
    private int $customerId;
    private bool $deleted;

    public function __construct()
    {
        $this->policyDriverId = 0;
        $this->customerPolicyId = 0;
        $this->customerId = 0;
        $this->deleted = false;
    }

    public function getPolicyDriverId(): int
    {
        return $this->policyDriverId;
    }

    public function setPolicyDriverId(int $policyDriverId): self
    {
        $this->policyDriverId = $policyDriverId;
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

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;
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

    public static function delete(int $policyDriverId): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE customerpolicydriver SET Deleted = 1 WHERE PolicyDriverId = ? AND Deleted = 0";
        $con->pushParam($policyDriverId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function getByPolicyDriverId(int $policyDriverId): ?self
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM customerpolicydriver WHERE PolicyDriverId = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($policyDriverId);

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
        $query = "SELECT * FROM customerpolicydriver WHERE Deleted = 0";

        $result = $con->queryAllObject($query);

        $drivers = [];
        foreach ($result as $driver)
        {
            $drivers[] = self::map($driver);
        }

        return $drivers;
    }

    private static function map($driver): self
    {
        return (new self())
            ->setPolicyDriverId($driver->PolicyDriverId)
            ->setCustomerPolicyId($driver->CustomerPolicyId)
            ->setCustomerId($driver->CustomerId)
            ->setDeleted((bool) $driver->Deleted);
    }
}