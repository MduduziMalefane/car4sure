namespace TLC\Application\Model;

use PDO;
use TLC\Application\Database;

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
    private int $annualMilage;
    private string $ownership;

    public function __construct()
    {
        $this->plateID = 0;
        $this->year = '';
        $this->make = '';
        $this->model = '';
        $this->vin = 0;
        $this->usage = '';
        $this->primaryUse = '';
        $this->annualMilage = 0;
        $this->ownership = '';
    }
    public function getplateID(): int
    {
        return $this->plateID;
    }

    /**
     * Set the value of plateID
     * @param int $plateID
     * @return self
     */
    public function setplateID(int $plateID): self
    {
        $this->plateID = $plateID;
        return $this;
    }

    public function getyear(): int
    {
        return $this->year;
    }

    /**
     * Set the value of year
     * @param int $year
     * @return self
     */
    public function setyear(string $year): self
    {
        $this->firstName = $year;
        return $this;
    }

    public function getmake(): string
    {
        return $this->make;
    }

    /**
     * Set the value of make
     * @param int $make
     * @return self
     */
    public function setmake(string $make): self
    {
        $this->make = $make;
        return $this;
    }

    public function getmake(): string
    {
        return $this->make;
    }

    /**
     * Set the value of make
     * @param int $make
     * @return self
     */
    public function setmake(string $make): self
    {
        $this->make = $make;
        return $this;
    }

    public function getmodel(): string
    {
        return $this->model;
    }

    /**
     * Set the value of model
     * @param int $model
     * @return self
     */
    public function setmodel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getvin(): int
    {
        return $this->vin;
    }

    /**
     * Set the value of vin
     * @param int $vin
     * @return self
     */
    public function setvin(int $vin): self
    {
        $this->vin = $vin;
        return $this;
    }

    public function usage(): string
    {
        return $this->usage;
    }

    /**
     * Set the value of usage
     * @param int $usage
     * @return self
     */
    public function setusage(int $usage): self
    {
        $this->usage = $usage;
        return $this;
    }

    public function save(): bool
    {
        if ($this->plateID == 0) {
            return $this->saveVehicle();
        } else {
            return $this->updateVehicle();
        }
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
        $con->pushParam($this->annualMilage);
        $con->pushParam($this->ownership);


        if ($con->executeNoneQuery($query) > 0) {
            $this->plateID = $con->getLastInsertID();
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
        $con->pushParam($this->annualMilage);
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
    public static function getvehicleByplateID(int $plateID): ?vehicle
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM vehicle WHERE plateID = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($plateID);
        $result = $con->queryObject($query);

        if ($result) {
            return self::mapVehilce($result);
        }

        return null;
    }

    /**
     * Get all vehicles
     * @return array
     */
    public static function getAllVehicles(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM vehicle WHERE Deleted = 0";
        $result = [];

        $Policyholders = $con->queryAllObject($query);

        if ($Vehicles) {
            foreach ($Vehicles as $row) {
                $result[] = self::mapVehicle($row);
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
        $vehicle = self::getAllVehicles();
        $result = [];

        foreach ($Vehicles as $Vehicle) {
            $result[] = ["plateID" => $vehicle->getplateID(), "name" => $vehicle->getyear()];
        }

        return $result;
    }

    private static function mapVehicle($Vehicle): Vehicle
    {
        return (new self())
            ->setplateID($Vehicle->plateID)
            ->setyear($Vehicle->year)
            ->setlastName($Vehicle->make)
            ->setstreetName($Vehicle->model)
            ->setcity($Vehicle->vin)
            ->setstate($Vehicle->usage)
            ->setzip($Vehicle->primaryUse)
            ->setzip($Vehicle->annualMilage)
            ->setzip($Vehicle->ownership);
    }