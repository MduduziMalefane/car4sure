<?php
namespace CAR4SURE\Application\Model;
/**
 * Vehicle Model
 */
class Vehicle
{
    private int $plateID;
    private int $year;
    private string $make;
    private string $model;
    private int $vin;
    private string $usage;
    private string $primaryUse;
    private int $annualMileage;
    private string $ownership;
    private int $userId;

    public function __construct()
    {
        $this->plateID = 0;
        $this->year = 0;
        $this->make = '';
        $this->model = '';
        $this->vin = 0;
        $this->usage = '';
        $this->primaryUse = '';
        $this->annualMileage = 0;
        $this->ownership = '';
        $this->userId = 0;
    }
    public function getPlateID(): int
    {
        return $this->plateID;
    }

    /**
     * Set the value of plateID
     * @param int $plateID
     * @return self
     */
    public function setPlateID(int $plateID): self
    {
        $this->plateID = $plateID;
        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * Set the value of year
     * @param int $year
     * @return self
     */
    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function getMake(): string
    {
        return $this->make;
    }


    /**
     * Set the value of make
     * @param int $make
     * @return self
     */
    public function setMake(string $make): self
    {
        $this->make = $make;
        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Set the value of model
     * @param int $model
     * @return self
     */
    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getVin(): int
    {
        return $this->vin;
    }

    /**
     * Set the value of vin
     * @param int $vin
     * @return self
     */
    public function setVin(int $vin): self
    {
        $this->vin = $vin;
        return $this;
    }

    public function getUsage(): string
    {
        return $this->usage;
    }

    /**
     * Set the value of usage
     * @param int $usage
     * @return self
     */
    public function setUsage(int $usage): self
    {
        $this->usage = $usage;
        return $this;
    }
    public function getPrimaryUse(): string
    {
        return $this->usage;
    }

    /**
     * Set the value of usage
     * @param int $usage
     * @return self
     */
    public function setPrimaryUse(string $primaryUse): self
    {
        $this->primaryUse = $primaryUse;
        return $this;
    }

    public function getAnnualMileage(): int
    {
        return $this->annualMileage;
    }

    /**
     * Set the value of usage
     * @param int $annualMileage
     * @return self
     */
    public function setAnnualMileage(int $annualMileage): self
    {
        $this->annualMileage = $annualMileage;
        return $this;
    }

    public function getOwnership(): string
    {
        return $this->ownership;
    }

    /**
     * Set the value of usage
     * @param int $ownership
     * @return self
     */
    public function setOwnership(int $ownership): self
    {
        $this->ownership = $ownership;
        return $this;
    }


    public function save(): bool
    {
        if ($this->plateID == 0)
        {
            return $this->saveVehicle();
        }
        else
        {
            return $this->updateVehicle();
        }
    }

    public function getuserId(): int
    {
        return $this->userId;
    }

    /**
     * Set the value of year
     * @param int $year
     * @return self
     */
    public function setuserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Insert the Vehicle
     * @return bool
     */
    private function saveVehicle(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO vehicle (year, make, model, vin, usage, primaryUse,annualMilage,ownership) VALUES (?, ?, ?);";
        $con->pushParam($this->year);
        $con->pushParam($this->make);
        $con->pushParam($this->model);
        $con->pushParam($this->vin);
        $con->pushParam($this->usage);
        $con->pushParam($this->primaryUse);
        $con->pushParam($this->annualMileage);
        $con->pushParam($this->ownership);


        if ($con->executeNoneQuery($query) > 0)
        {
            $this->plateID = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Update the vehicle
     * @return bool
     */
    private function updateVehicle(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE vehicle SET year = ?, make = ?, model = ?, vin = ?, usage = ?, primaryUse = ?,annualMilage = ?,ownership = ?, WHERE plateID = ? AND Deleted = 0";
        $con->pushParam($this->year);
        $con->pushParam($this->make);
        $con->pushParam($this->model);
        $con->pushParam($this->vin);
        $con->pushParam($this->usage);
        $con->pushParam($this->primaryUse);
        $con->pushParam($this->annualMileage);
        $con->pushParam($this->ownership);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Delete the vehicle
     * @param int $plateID
     * @return bool
     */
    public static function delete(int $plateID): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE vehicle SET Deleted = 1 WHERE plateID = ?";
        $con->pushParam($plateID);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Get the vehicle by plateID
     * @param int $plateID
     * @return vehicle|null
     */
    public static function getVehicleByPlateId(int $plateID): ?vehicle
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM vehicle WHERE plateID = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($plateID);
        $result = $con->queryObject($query);

        if ($result)
        {
            return self::map($result);
        }

        return null;
    }

    /**
     * Get all vehicles
     * @return array
     */
    public static function getAll(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM vehicle WHERE Deleted = 0";
        $result = [];

        $result = $con->queryAllObject($query);

        if ($result)
        {
            foreach ($result as $row)
            {
                $result[] = self::map($row);
            }
        }

        return $result;
    }

    /**
     * Get the vehicle display values
     * @return array
     */
    public static function getDisplay(): array
    {
        $vehicles = self::getAll();
        $result = [];

        foreach ($vehicles as $vehicle)
        {
            $result[] = ["plateID" => $vehicle->getPlateID(), "name" => $vehicle->getyear()];
        }

        return $result;
    }

    private static function map($data): Vehicle
    {
        return (new self())
            ->setPlateId($data->plateID)
            ->setYear($data->year)
            ->setMake($data->make)
            ->setModel($data->model)
            ->setVin($data->vin)
            ->setUsage($data->usage)
            ->setPrimaryUse($data->primaryUse)
            ->setAnnualMileage($data->annualMileage)
            ->setOwnership($data->ownership);
    }
}