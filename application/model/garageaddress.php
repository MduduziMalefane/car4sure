namespace TLC\Application\Model;

use PDO;
use TLC\Application\Database;

/**
 * Policyholder Model
 */
class GarageAddress
{
    private int $garageID;
    private string $streetName;
    private string $city;
    private string $state;
    private int $zip;

    public function __construct()
    {
        $this->garageID = 0;
        $this->streetName = '';
        $this->city = '';
        $this->state = '';
        $this->zip = 0;
    }
    public function getgarageID(): int
    {
        return $this->garageID;
    }

    /**
     * Set the value of garageID
     * @param int $garageID
     * @return self
     */
    public function setgarageID(int $garageID): self
    {
        $this->garageID = $garageID;
        return $this;
    }

    public function getstreetName(): string
    {
        return $this->streetName;
    }

    /**
     * Set the value of streetName
     * @param int $streetName
     * @return self
     */
    public function setstreetName(string $streetName): self
    {
        $this->streetName = $streetName;
        return $this;
    }

    public function getcity(): string
    {
        return $this->city;
    }

    /**
     * Set the value of city
     * @param int $city
     * @return self
     */
    public function setcity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getcity(): string
    {
        return $this->city;
    }

    /**
     * Set the value of city
     * @param int $city
     * @return self
     */
    public function setstate(string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getzip(): int
    {
        return $this->zip;
    }

    /**
     * Set the value of zip
     * @param int $zip
     * @return self
     */
  
    public function save(): bool
    {
        if ($this->id == 0) {
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
        $con->pushParam($this->garageID);

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
        $query = "UPDATE garage SET Deleted = 1 WHERE garageID = ?";
        $con->pushParam($garageID);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Get the garageAddress by garageID
     * @param int $garageID
     * @return garage|null
     */
    public static function getgarageaddressBygarageID(int $garageID): ?garageaddress
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
    public static function getAllGarageAddresses(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM garageaddress WHERE Deleted = 0";
        $result = [];

        $GarageAddress = $con->queryAllObject($query);

        if ($GarageAddresses) {
            foreach ($GarageAddresses as $row) {
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
        $garageaddresses = self::getAllGarageAddress();
        $result = [];

        foreach ($GarageAddresses as $GarageAddress) {
            $result[] = ["garageID" => $garageID->getgarageID(), "name" => $garageaddress->getyear()];
        }

        return $result;
    }

    private static function mapGarageAddress($garageAddress): garageAddress
    {
        return (new self())
            ->setgarageID($garageAddress->garageID)
            ->setyear($garageAddress->year)
            ->setlastName($garageAddress->city)
            ->setstreetName($garageAddress->state)
            ->setzip($garageAddress->zip);
    }