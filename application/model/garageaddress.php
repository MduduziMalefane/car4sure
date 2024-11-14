<?php
namespace CAR4SURE\Application\Model;



/**
 * GarageAddress Model
 */
class GarageAddress
{
    private int $garageId;
    private string $streetName;
    private string $city;
    private string $state;
    private int $zip;
    private int $plateID;

    public function __construct()
    {
        $this->garageId = 0;
        $this->streetName = '';
        $this->city = '';
        $this->state = '';
        $this->zip = 0;
        $this->plateID = 0;
    }
    public function getGarageID(): int
    {
        return $this->garageId;
    }

    /**
     * Set the value of garageID
     * @param int $garageID
     * @return self
     */
    public function setGarageID(int $garageId): self
    {
        $this->garageId = $garageId;
        return $this;
    }

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    /**
     * Set the value of streetName
     * @param int $streetName
     * @return self
     */
    public function setStreetName(string $streetName): self
    {
        $this->streetName = $streetName;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Set the value of city
     * @param int $city
     * @return self
     */
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function setState(string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getZip(): int
    {
        return $this->zip;
    }
    public function setZip(int $zip): self
    {
        $this->zip = $zip;
        return $this;
    }
    /**
     * Set the value of plateID
     * @param int $plateID
     * @return self
     */
     public function getplateID(): int
    {
        return $this->plateID;
    }
     public function setplateID(int $plateID): self
    {
        $this->plateID = $plateID;
        return $this;
    }

    
    public function save(): bool
    {
        if ($this->garageId == 0) {
            return $this->saveGarageAddress();
        } else {
            return $this->updateGarageAddress();
        }
    }

    /**
     * Insert the GarageAddress
     * @return bool
     */
    private function saveGarageAddress(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO garageaddress (streetName, city, state, zip) VALUES (?, ?, ?);";
        $con->pushParam($this->streetName);
        $con->pushParam($this->city);
        $con->pushParam($this->state);
        $con->pushParam($this->zip);
        $con->pushParam($this->plateID);


        if ($con->executeNoneQuery($query) > 0) {
            $this->garageID = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Update the GarageAddress
     * @return bool
     */
    private function updateGarageAddress(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE garageaddress SET streetName = ?, city = ?, state = ?, zip = ?, WHERE garageID = ? AND Deleted = 0";
        $con->pushParam($this->streetName);
        $con->pushParam($this->city);
        $con->pushParam($this->state);
        $con->pushParam($this->zip);
        $con->pushParam($this->garageId);
        $con->pushParam($this->plateID);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Delete the garageaddress
     * @param int $garageID
     * @return bool
     */
    public static function delete(int $garageID): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE garageaddress SET Deleted = 1 WHERE garageID = ?";
        $con->pushParam($garageID);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Get the garageAddress by garageID
     * @param int $garageID
     * @return |null
     */
    public static function getGarageAddressByGarageId(int $garageID): ?garageaddress
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM garageaddress WHERE garageID = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($garageID);
        $result = $con->queryObject($query);

        if ($result) {
            return self::mapGarageAddress($result);
        }

        return null;
    }

    /**
     * Get all garageaddresses
     * @return array
     */
    public static function getAll(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM garageaddress WHERE Deleted = 0";
        $result = [];

        $GarageAddress = $con->queryAllObject($query);

        if ($GarageAddress) {
            foreach ($GarageAddress as $row) {
                $result[] = self::mapGarageAddress($row);
            }
        }

        return $result;
    }

    /**
     * Get the bank display values
     * @return array
     */
    public static function getDisplay(): array
    {
        $garageaddresses = self::getAll();
        $result = [];

        foreach ($garageaddresses as $garageaddress) {
            $result[] = ["garageID" => $garageaddress->getgarageID(), "name" => $garageaddress->getyear()];
        }

        return $result;
    }

    private static function mapGarageAddress($garageAddress): garageAddress
    {
        return (new self())
            ->setGarageID($garageAddress->garageID)
            ->setStreetName($garageAddress->streetName)
            ->setCity($garageAddress->city)
            ->setState($garageAddress->state)
            ->setZip($garageAddress->zip)
            ->setPlateID($garageAddress->plateID);
    }
}