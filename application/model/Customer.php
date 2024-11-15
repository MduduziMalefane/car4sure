<?php

namespace Care4Sure\Application\Model;

class Customer
{
    private int $customerId;
    private string $firstName;
    private string $lastName;
    private string $gender;
    private \DateTime $dateOfBirth;
    private \DateTime $dateRegistered;
    private string $maritalStatus;
    private string $contactNo;
    private string $email;
    private string $street;
    private string $city;
    private string $state;
    private string $zipCode;
    private string $licenseNumber;
    private string $licenseState;
    private string $licenseStatus;
    private \DateTime $licenseEffectiveDate;
    private \DateTime $licenseExpirationDate;
    private string $licenseClass;
    private string $password;
    private bool $deleted;

    public function __construct()
    {
        $this->customerId = 0;
        $this->firstName = '';
        $this->lastName = '';
        $this->gender = '';
        $this->dateOfBirth = new \DateTime();
        $this->dateRegistered = new \DateTime();
        $this->maritalStatus = '';
        $this->contactNo = '';
        $this->email = '';
        $this->street = '';
        $this->city = '';
        $this->state = '';
        $this->zipCode = '';
        $this->licenseNumber = '';
        $this->licenseState = '';
        $this->licenseStatus = '';
        $this->licenseEffectiveDate = new \DateTime();
        $this->licenseExpirationDate = new \DateTime();
        $this->licenseClass = '';
        $this->password = '';
        $this->deleted = false;
    }

    // Getters and Setters for each property

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFullName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    public function getInitials(): string
    {
        return sprintf('%s%s', substr($this->firstName, 0, 1), substr($this->lastName, 0, 1));
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getDateOfBirth(): \DateTime
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTime $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    public function getDateRegistered(): \DateTime
    {
        return $this->dateRegistered;
    }

    public function setDateRegistered(\DateTime $dateRegistered): self
    {
        $this->dateRegistered = $dateRegistered;
        return $this;
    }

    public function getMaritalStatus(): string
    {
        return $this->maritalStatus;
    }

    public function setMaritalStatus(string $maritalStatus): self
    {
        $this->maritalStatus = $maritalStatus;
        return $this;
    }

    public function getContactNo(): string
    {
        return $this->contactNo;
    }

    public function setContactNo(string $contactNo): self
    {
        $this->contactNo = $contactNo;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function getLicenseNumber(): string
    {
        return $this->licenseNumber;
    }

    public function setLicenseNumber(string $licenseNumber): self
    {
        $this->licenseNumber = $licenseNumber;
        return $this;
    }

    public function getLicenseState(): string
    {
        return $this->licenseState;
    }

    public function setLicenseState(string $licenseState): self
    {
        $this->licenseState = $licenseState;
        return $this;
    }

    public function getLicenseStatus(): string
    {
        return $this->licenseStatus;
    }

    public function setLicenseStatus(string $licenseStatus): self
    {
        $this->licenseStatus = $licenseStatus;
        return $this;
    }

    public function getLicenseEffectiveDate(): \DateTime
    {
        return $this->licenseEffectiveDate;
    }

    public function setLicenseEffectiveDate(\DateTime $licenseEffectiveDate): self
    {
        $this->licenseEffectiveDate = $licenseEffectiveDate;
        return $this;
    }

    public function getLicenseExpirationDate(): \DateTime
    {
        return $this->licenseExpirationDate;
    }

    public function setLicenseExpirationDate(\DateTime $licenseExpirationDate): self
    {
        $this->licenseExpirationDate = $licenseExpirationDate;
        return $this;
    }

    public function getLicenseClass(): string
    {
        return $this->licenseClass;
    }

    public function setLicenseClass(string $licenseClass): self
    {
        $this->licenseClass = $licenseClass;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
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
        if ($this->customerId == 0)
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
        $query = "INSERT INTO customer (FirstName, LastName, Gender, DateOfBirth, DateRegistered, MaritalStatus, ContactNo, Email, Street, City, State, ZipCode, LicenseNumber, LicenseState, LicenseStatus, LicenseEffectiveDate, LicenseExpirationDate, LicenseClass, Password, Deleted) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $con->pushParam($this->firstName);
        $con->pushParam($this->lastName);
        $con->pushParam($this->gender);
        $con->pushParam($this->dateOfBirth->format('Y-m-d'));
        $con->pushParam($this->dateRegistered->format('Y-m-d H:i:s'));
        $con->pushParam($this->maritalStatus);
        $con->pushParam($this->contactNo);
        $con->pushParam($this->email);
        $con->pushParam($this->street);
        $con->pushParam($this->city);
        $con->pushParam($this->state);
        $con->pushParam($this->zipCode);
        $con->pushParam($this->licenseNumber);
        $con->pushParam($this->licenseState);
        $con->pushParam($this->licenseStatus);
        $con->pushParam($this->licenseEffectiveDate->format('Y-m-d'));
        $con->pushParam($this->licenseExpirationDate->format('Y-m-d'));
        $con->pushParam($this->licenseClass);
        $con->pushParam($this->password);
        $con->pushParam((int) $this->deleted);

        if ($con->executeNoneQuery($query) > 0)
        {
            $this->customerId = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    private function update(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE customer SET FirstName = ?, LastName = ?, Gender = ?, DateOfBirth = ?, DateRegistered = ?, MaritalStatus = ?, ContactNo = ?, Email = ?, Street = ?, City = ?, State = ?, ZipCode = ?, LicenseNumber = ?, LicenseState = ?, LicenseStatus = ?, LicenseEffectiveDate = ?, LicenseExpirationDate = ?, LicenseClass = ?, Password = ?, Deleted = ? WHERE CustomerId = ?";

        $con->pushParam($this->firstName);
        $con->pushParam($this->lastName);
        $con->pushParam($this->gender);
        $con->pushParam($this->dateOfBirth->format('Y-m-d'));
        $con->pushParam($this->dateRegistered->format('Y-m-d H:i:s'));
        $con->pushParam($this->maritalStatus);
        $con->pushParam($this->contactNo);
        $con->pushParam($this->email);
        $con->pushParam($this->street);
        $con->pushParam($this->city);
        $con->pushParam($this->state);
        $con->pushParam($this->zipCode);
        $con->pushParam($this->licenseNumber);
        $con->pushParam($this->licenseState);
        $con->pushParam($this->licenseStatus);
        $con->pushParam($this->licenseEffectiveDate->format('Y-m-d'));
        $con->pushParam($this->licenseExpirationDate->format('Y-m-d'));
        $con->pushParam($this->licenseClass);
        $con->pushParam($this->password);
        $con->pushParam((int) $this->deleted);
        $con->pushParam($this->customerId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function delete(int $customerId): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE customer SET Deleted = 1 WHERE CustomerId = ? AND Deleted = 0";
        $con->pushParam($customerId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function getByCustomerId(int $customerId): ?self
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM customer WHERE CustomerId = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($customerId);

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
        $query = "SELECT * FROM customer WHERE Deleted = 0";

        $result = $con->queryAllObject($query);

        $customers = [];
        foreach ($result as $customer)
        {
            $customers[] = self::map($customer);
        }

        return $customers;
    }

    public static function getByCustomerIdAsJson(int $customerId): ?object
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM customer WHERE CustomerId = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($customerId);

        $result = $con->queryObject($query);
        $customer = null;

        if ($result)
        {
            $customer = new \stdClass();
            $customer->customerId = $result->CustomerId;
            $customer->firstName = $result->FirstName;
            $customer->lastName = $result->LastName;
            $customer->gender = $result->Gender;
            $customer->dateOfBirth = $result->DateOfBirth;
            $customer->dateRegistered = $result->DateRegistered;
            $customer->maritalStatus = $result->MaritalStatus;
            $customer->contactNo = $result->ContactNo;
            $customer->email = $result->Email;
            $customer->street = $result->Street;
            $customer->city = $result->City;
            $customer->state = $result->State;
            $customer->zipCode = $result->ZipCode;
            $customer->licenseNumber = $result->LicenseNumber;
            $customer->licenseState = $result->LicenseState;
            $customer->licenseStatus = $result->LicenseStatus;
            $customer->licenseEffectiveDate = $result->LicenseEffectiveDate;
            $customer->licenseExpirationDate = $result->LicenseExpirationDate;
            $customer->licenseClass = $result->LicenseClass;
        }

        return $customer;
    }

    private static function map($customer): self
    {
        return (new self())
            ->setCustomerId($customer->CustomerId)
            ->setFirstName($customer->FirstName)
            ->setLastName($customer->LastName)
            ->setGender($customer->Gender)
            ->setDateOfBirth(new \DateTime($customer->DateOfBirth))
            ->setDateRegistered(new \DateTime($customer->DateRegistered))
            ->setMaritalStatus($customer->MaritalStatus)
            ->setContactNo($customer->ContactNo)
            ->setEmail($customer->Email)
            ->setStreet($customer->Street)
            ->setCity($customer->City)
            ->setState($customer->State)
            ->setZipCode($customer->ZipCode)
            ->setLicenseNumber($customer->LicenseNumber)
            ->setLicenseState($customer->LicenseState)
            ->setLicenseStatus($customer->LicenseStatus)
            ->setLicenseEffectiveDate(new \DateTime($customer->LicenseEffectiveDate))
            ->setLicenseExpirationDate(new \DateTime($customer->LicenseExpirationDate))
            ->setLicenseClass($customer->LicenseClass)
            ->setPassword($customer->Password)
            ->setDeleted((bool) $customer->Deleted);
    }

    public static function mapFromJson($customer): self
    {
        return (new self())
            ->setCustomerId($customer->customerId)
            ->setFirstName($customer->firstName)
            ->setLastName($customer->lastName)
            ->setGender($customer->gender)
            ->setDateOfBirth(new \DateTime($customer->dateOfBirth))
            ->setDateRegistered(new \DateTime($customer->dateRegistered))
            ->setMaritalStatus($customer->maritalStatus)
            ->setContactNo($customer->contactNo)
            ->setEmail($customer->email)
            ->setStreet($customer->street)
            ->setCity($customer->city)
            ->setState($customer->state)
            ->setZipCode($customer->zipCode)
            ->setLicenseNumber($customer->licenseNumber)
            ->setLicenseState($customer->licenseState)
            ->setLicenseStatus($customer->licenseStatus)
            ->setLicenseEffectiveDate(new \DateTime($customer->licenseEffectiveDate))
            ->setLicenseExpirationDate(new \DateTime($customer->licenseExpirationDate))
            ->setLicenseClass($customer->licenseClass)
            ->setPassword($customer->password)
            ->setDeleted((bool) $customer->deleted);

    }

    public static function mapFromRequest($request): ?self
    {
        $customer = new self();

        if (\ValidationClass::ValidateFullNumber($request->customerId))
        {
            $customer->setCustomerId((int) $request->customerId);
        }

        if ($request->firstName !== null)
        {
            if (!\ValidationClass::ValidateText($request->firstName))
            {
                return null;
            }
            $customer->setFirstName($request->firstName);
        }
        else
        {
            $customer->setFirstName('');
        }

        if ($request->lastName !== null)
        {
            if (!\ValidationClass::ValidateText($request->lastName))
            {
                return null;
            }
            $customer->setLastName($request->lastName);
        }
        else
        {
            $customer->setLastName('');
        }

        if ($request->gender !== null)
        {
            $customer->setGender($request->gender);
        }
        else
        {
            $customer->setGender('');
        }

        if ($request->dateOfBirth !== null)
        {
            $customer->setDateOfBirth(new \DateTime($request->dateOfBirth));
        }
        else
        {
            $customer->setDateOfBirth(new \DateTime());
        }

        if ($request->dateRegistered !== null)
        {
            $customer->setDateRegistered(new \DateTime($request->dateRegistered));
        }
        else
        {
            $customer->setDateRegistered(new \DateTime());
        }

        if ($request->maritalStatus !== null)
        {
            $customer->setMaritalStatus($request->maritalStatus);
        }
        else
        {
            $customer->setMaritalStatus('');
        }

        if (!empty($request->contactNo))
        {
            if (!\ValidationClass::ValidateContactNumber($request->contactNo))
            {
                return null;
            }
            $customer->setContactNo($request->contactNo);
        }
        else
        {
            $customer->setContactNo('');
        }

        if (!empty($request->email))
        {
            if (!\ValidationClass::ValidateEmail($request->email))
            {
                return null;
            }
            $customer->setEmail($request->email);
        }
        else
        {
            $customer->setEmail('');
        }

        if ($request->street !== null)
        {
            $customer->setStreet($request->street);
        }
        else
        {
            $customer->setStreet('');
        }

        if ($request->city !== null)
        {
            $customer->setCity($request->city);
        }
        else
        {
            $customer->setCity('');
        }

        if ($request->state !== null)
        {
            $customer->setState($request->state);
        }
        else
        {
            $customer->setState('');
        }

        if ($request->zipCode !== null)
        {
            $customer->setZipCode($request->zipCode);
        }
        else
        {
            $customer->setZipCode('');
        }

        if ($request->licenseNumber !== null)
        {
            $customer->setLicenseNumber($request->licenseNumber);
        }
        else
        {
            $customer->setLicenseNumber('');
        }

        if ($request->licenseState !== null)
        {
            $customer->setLicenseState($request->licenseState);
        }
        else
        {
            $customer->setLicenseState('');
        }

        if ($request->licenseStatus !== null)
        {
            $customer->setLicenseStatus($request->licenseStatus);
        }
        else
        {
            $customer->setLicenseStatus('');
        }

        if ($request->licenseEffectiveDate !== null)
        {
            $customer->setLicenseEffectiveDate(new \DateTime($request->licenseEffectiveDate));
        }
        else
        {
            $customer->setLicenseEffectiveDate(new \DateTime());
        }

        if ($request->licenseExpirationDate !== null)
        {
            $customer->setLicenseExpirationDate(new \DateTime($request->licenseExpirationDate));
        }
        else
        {
            $customer->setLicenseExpirationDate(new \DateTime());
        }

        if ($request->licenseClass !== null)
        {
            $customer->setLicenseClass($request->licenseClass);
        }
        else
        {
            $customer->setLicenseClass('');
        }

        if ($request->password !== null)
        {
            $customer->setPassword($request->password);
        }
        else
        {
            $customer->setPassword('');
        }

        return $customer;
    }


    public static function getAllAsJson(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM customer WHERE Deleted = 0";

        $results = $con->queryAllObject($query);

        $customers = [];
        foreach ($results as $result)
        {
            $customer = new \stdClass();
            $customer->customerId = $result->CustomerId;
            $customer->firstName = $result->FirstName;
            $customer->lastName = $result->LastName;
            $customer->gender = $result->Gender;
            $customer->dateOfBirth = $result->DateOfBirth;
            $customer->dateRegistered = $result->DateRegistered;
            $customer->maritalStatus = $result->MaritalStatus;
            $customer->contactNo = $result->ContactNo;
            $customer->email = $result->Email;
            $customer->street = $result->Street;
            $customer->city = $result->City;
            $customer->state = $result->State;
            $customer->zipCode = $result->ZipCode;
            $customer->licenseNumber = $result->LicenseNumber;
            $customer->licenseState = $result->LicenseState;
            $customer->licenseStatus = $result->LicenseStatus;
            $customer->licenseEffectiveDate = $result->LicenseEffectiveDate;
            $customer->licenseExpirationDate = $result->LicenseExpirationDate;
            $customer->licenseClass = $result->LicenseClass;

            $customers[] = $customer;
        }

        return $customers;

    }
}