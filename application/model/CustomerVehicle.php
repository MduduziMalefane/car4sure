<?php

namespace Care4Sure\Application\Model;

class CustomerVehicle
{
    private int $customerVehicleId;
    private int $customerId;
    private int $year;
    private string $make;
    private string $model;
    private string $vin;
    private string $usage;
    private string $primaryUse;
    private int $annualMileage;
    private string $ownership;
    private bool $deleted;

    public function __construct()
    {
        $this->customerVehicleId = 0;
        $this->customerId = 0;
        $this->year = 0;
        $this->make = '';
        $this->model = '';
        $this->vin = '';
        $this->usage = '';
        $this->primaryUse = '';
        $this->annualMileage = 0;
        $this->ownership = '';
        $this->deleted = false;
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

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function getMake(): string
    {
        return $this->make;
    }

    public function setMake(string $make): self
    {
        $this->make = $make;
        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getVin(): string
    {
        return $this->vin;
    }

    public function setVin(string $vin): self
    {
        $this->vin = $vin;
        return $this;
    }

    public function getUsage(): string
    {
        return $this->usage;
    }

    public function setUsage(string $usage): self
    {
        $this->usage = $usage;
        return $this;
    }

    public function getPrimaryUse(): string
    {
        return $this->primaryUse;
    }

    public function setPrimaryUse(string $primaryUse): self
    {
        $this->primaryUse = $primaryUse;
        return $this;
    }

    public function getAnnualMileage(): int
    {
        return $this->annualMileage;
    }

    public function setAnnualMileage(int $annualMileage): self
    {
        $this->annualMileage = $annualMileage;
        return $this;
    }

    public function getOwnership(): string
    {
        return $this->ownership;
    }

    public function setOwnership(string $ownership): self
    {
        $this->ownership = $ownership;
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
        if ($this->customerVehicleId == 0)
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
        $query = "INSERT INTO customervehicle (CustomerId, Year, Make, Model, Vin, Usage, PrimaryUse, AnnualMileage, Ownership, Deleted) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $con->pushParam($this->customerId);
        $con->pushParam($this->year);
        $con->pushParam($this->make);
        $con->pushParam($this->model);
        $con->pushParam($this->vin);
        $con->pushParam($this->usage);
        $con->pushParam($this->primaryUse);
        $con->pushParam($this->annualMileage);
        $con->pushParam($this->ownership);
        $con->pushParam((int) $this->deleted);

        if ($con->executeNoneQuery($query) > 0)
        {
            $this->customerVehicleId = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    private function update(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE customervehicle SET CustomerId = ?, Year = ?, Make = ?, Model = ?, Vin = ?, Usage = ?, PrimaryUse = ?, AnnualMileage = ?, Ownership = ?, Deleted = ? 
                  WHERE CustomerVehicleId = ?";

        $con->pushParam($this->customerId);
        $con->pushParam($this->year);
        $con->pushParam($this->make);
        $con->pushParam($this->model);
        $con->pushParam($this->vin);
        $con->pushParam($this->usage);
        $con->pushParam($this->primaryUse);
        $con->pushParam($this->annualMileage);
        $con->pushParam($this->ownership);
        $con->pushParam((int) $this->deleted);
        $con->pushParam($this->customerVehicleId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function delete(int $customerVehicleId): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE customervehicle SET Deleted = 1 WHERE CustomerVehicleId = ? AND Deleted = 0";
        $con->pushParam($customerVehicleId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function getByCustomerVehicleId(int $customerVehicleId): ?self
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM customervehicle WHERE CustomerVehicleId = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($customerVehicleId);

        $result = $con->queryObject($query);

        if ($result)
        {
            return self::map($result);
        }

        return null;
    }

    public static function getByCustomerId(int $customerId): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM customervehicle WHERE CustomerId = ? AND Deleted = 0";
        $con->pushParam($customerId);

        $result = $con->queryAllObject($query);

        $vehicles = [];
        foreach ($result as $vehicle)
        {
            $vehicles[] = self::map($vehicle);
        }

        return $vehicles;
    }

    public static function getAll(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM customervehicle WHERE Deleted = 0";

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
            ->setCustomerVehicleId($vehicle->CustomerVehicleId)
            ->setCustomerId($vehicle->CustomerId)
            ->setYear($vehicle->Year)
            ->setMake($vehicle->Make)
            ->setModel($vehicle->Model)
            ->setVin($vehicle->Vin)
            ->setUsage($vehicle->Usage)
            ->setPrimaryUse($vehicle->PrimaryUse)
            ->setAnnualMileage($vehicle->AnnualMileage)
            ->setOwnership($vehicle->Ownership)
            ->setDeleted((bool) $vehicle->Deleted);
    }
}