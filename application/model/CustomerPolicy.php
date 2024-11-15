<?php

namespace Care4Sure\Application\Model;

class CustomerPolicy
{
    private int $customerPolicyId;
    private string $policyStatus;
    private int $customerId;
    private int $policyNo;
    private \DateTime $policyEffectiveDate;
    private \DateTime $policyExpirationDate;
    private bool $deleted;

    public function __construct()
    {
        $this->customerPolicyId = 0;
        $this->policyStatus = '';
        $this->customerId = 0;
        $this->policyNo = 0;
        $this->policyEffectiveDate = new \DateTime();
        $this->policyExpirationDate = new \DateTime();
        $this->deleted = false;
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

    public function getPolicyStatus(): string
    {
        return $this->policyStatus;
    }

    public function setPolicyStatus(string $policyStatus): self
    {
        $this->policyStatus = $policyStatus;
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

    public function getPolicyNo(): int
    {
        return $this->policyNo;
    }

    public function setPolicyNo(int $policyNo): self
    {
        $this->policyNo = $policyNo;
        return $this;
    }

    public function getPolicyEffectiveDate(): \DateTime
    {
        return $this->policyEffectiveDate;
    }

    public function setPolicyEffectiveDate(\DateTime $policyEffectiveDate): self
    {
        $this->policyEffectiveDate = $policyEffectiveDate;
        return $this;
    }

    public function getPolicyExpirationDate(): \DateTime
    {
        return $this->policyExpirationDate;
    }

    public function setPolicyExpirationDate(\DateTime $policyExpirationDate): self
    {
        $this->policyExpirationDate = $policyExpirationDate;
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
        if ($this->customerPolicyId == 0)
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
        $query = "INSERT INTO customerpolicy (PolicyStatus, CustomerId, PolicyNo, PolicyEffectiveDate, PolicyExpirationDate, Deleted) 
                  VALUES (?, ?, ?, ?, ?, ?)";

        $con->pushParam($this->policyStatus);
        $con->pushParam($this->customerId);
        $con->pushParam($this->policyNo);
        $con->pushParam($this->policyEffectiveDate->format('Y-m-d H:i:s'));
        $con->pushParam($this->policyExpirationDate->format('Y-m-d H:i:s'));
        $con->pushParam((int) $this->deleted);

        if ($con->executeNoneQuery($query) > 0)
        {
            $this->customerPolicyId = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    private function update(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE customerpolicy SET PolicyStatus = ?, CustomerId = ?, PolicyNo = ?, PolicyEffectiveDate = ?, PolicyExpirationDate = ?, Deleted = ? 
                  WHERE CustomerPolicyId = ?";

        $con->pushParam($this->policyStatus);
        $con->pushParam($this->customerId);
        $con->pushParam($this->policyNo);
        $con->pushParam($this->policyEffectiveDate->format('Y-m-d H:i:s'));
        $con->pushParam($this->policyExpirationDate->format('Y-m-d H:i:s'));
        $con->pushParam((int) $this->deleted);
        $con->pushParam($this->customerPolicyId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function delete(int $customerPolicyId): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE customerpolicy SET Deleted = 1 WHERE CustomerPolicyId = ? AND Deleted = 0";
        $con->pushParam($customerPolicyId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function getByCustomerPolicyId(int $customerPolicyId): ?self
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM customerpolicy WHERE CustomerPolicyId = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($customerPolicyId);

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
        $query = "SELECT * FROM customer_policy WHERE Deleted = 0";

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
            ->setCustomerPolicyId($policy->CustomerPolicyId)
            ->setPolicyStatus($policy->PolicyStatus)
            ->setCustomerId($policy->CustomerId)
            ->setPolicyNo($policy->PolicyNo)
            ->setPolicyEffectiveDate(new \DateTime($policy->PolicyEffectiveDate))
            ->setPolicyExpirationDate(new \DateTime($policy->PolicyExpirationDate))
            ->setDeleted((bool) $policy->Deleted);
    }
}