CREATE TABLE user
(
    UserId                  INT             PRIMARY KEY     AUTO_INCREMENT,
    FirstName               VARCHAR(50)     NOT NULL,
    LastName                VARCHAR(50)     NOT NULL,
    Username                VARCHAR(50)     NOT NULL,
    Password                VARCHAR(64)     NOT NULL,
    Deleted                 TINYINT(1)      NOT NULL DEFAULT 0
)ENGINE=InnoDB;

INSERT INTO user (FirstName, LastName, Username, Password) VALUES 
('Admin', 'Admin', 'admin', 'FD732EF346CC719ACFE4D289B22442CD763635E1935622F87615DED30BC0894C');


CREATE TABLE policy
(
    PolicyNo                INT             PRIMARY KEY     AUTO_INCREMENT,
    PolicyName              VARCHAR(250)    NOT NULL,
    PolicyDescription       TEXT,
    PolicyType              VARCHAR(50)     NOT NULL DEFAULT 'Auto',
    PolicyCost              DECIMAL(10,2)   NOT NULL DEFAULT '0.00',
    Deleted                 TINYINT(1)      NOT NULL DEFAULT 0
)ENGINE=InnoDB;

INSERT INTO policy (PolicyName, PolicyDescription, PolicyType, PolicyCost) VALUES 
('Auto Insurance', 'Auto Insurance Policy', 'Auto', 200),
('Motorcycle Insurance', 'Motorcycle Insurance Policy', 'Motorcycle', 350),
('Boat Insurance', 'Boat Insurance Policy', 'Boat', 900);

CREATE TABLE coverage
(
    CoverageId              INT             PRIMARY KEY     AUTO_INCREMENT,
    CoverageName            VARCHAR(250)    NOT NULL,
    CoverageDescription     TEXT,
    Deleted                 TINYINT(1)      NOT NULL DEFAULT 0
)ENGINE=InnoDB;

INSERT INTO coverage (CoverageName, CoverageDescription) VALUES 
('Liability', 'Liability Coverage'),
('Collision', 'Collision Coverage'),
('Comprehensive', 'Comprehensive Coverage'),
('Medical', 'Medical Coverage'),
('Personal Injury Protection', 'Personal Injury Protection Coverage');

CREATE TABLE policycoverage
(
    PolicyCoverageId        INT             PRIMARY KEY     AUTO_INCREMENT,
    PolicyNo                INT             NOT NULL,
    CoverageId              INT             NOT NULL,
    `Limit`                 DECIMAL(10,2)   NOT NULL DEFAULT '0.00',
    Deductible              DECIMAL(10,2)   NOT NULL DEFAULT '0.00',
    Deleted                 TINYINT(1)      NOT NULL DEFAULT 0,

    CONSTRAINT fk_policycoverage_PolicyNo FOREIGN KEY (PolicyNo) REFERENCES policy(PolicyNo)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

    CONSTRAINT fk_policycoverage_CoverageId FOREIGN KEY (CoverageId) REFERENCES coverage(CoverageId)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE=InnoDB;


INSERT INTO policycoverage(PolicyNo, CoverageId, `Limit`, Deductible) VALUES 
(1, 1, 25000, 500),
(1, 2, 25000, 500),
(1, 3, 25000, 500),
(2, 1, 15000, 500),
(2, 2, 15000, 500),
(2, 3, 15000, 500),
(3, 1, 45000, 500),
(3, 2, 45000, 500),
(3, 3, 45000, 500);

CREATE TABLE customer
(
    CustomerId              INT             PRIMARY KEY     AUTO_INCREMENT,
    FirstName               VARCHAR(50)     NOT NULL,
    LastName                VARCHAR(50)     NOT NULL,
    Gender                  VARCHAR(30)     NOT NULL DEFAULT 1,
    DateOfBirth             DATE            NOT NULL,
    DateRegistered          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    MaritalStatus           VARCHAR(50)     NOT NULL DEFAULT 1,

    ContactNo               VARCHAR(13)     NOT NULL DEFAULT '',
    Email                   VARCHAR(256)    NOT NULL DEFAULT '',

    Street                  VARCHAR(150)    NOT NULL DEFAULT '',
    City                    VARCHAR(150)    NOT NULL DEFAULT '',
    State                   VARCHAR(150)    NOT NULL DEFAULT '',
    ZipCode                 VARCHAR(10)     NOT NULL DEFAULT '',

    LicenseNumber           VARCHAR(50)     NOT NULL DEFAULT '',
    LicenseState            VARCHAR(50)     NOT NULL DEFAULT '',
    LicenseStatus           VARCHAR(50)     NOT NULL DEFAULT '',
    LicenseEffectiveDate    DATE            NOT NULL DEFAULT CURRENT_DATE,
    LicenseExpirationDate   DATE            NOT NULL DEFAULT CURRENT_DATE,
    LicenseClass            VARCHAR(50)     NOT NULL DEFAULT '',
    
    Password                VARCHAR(64)     NOT NULL,
    Deleted                 TINYINT(1)      NOT NULL DEFAULT 0

)ENGINE=InnoDB;


INSERT INTO customer (FirstName, LastName, Gender, DateOfBirth, MaritalStatus, ContactNo, Email, Street, City, State, ZipCode, LicenseNumber, LicenseState, LicenseStatus, LicenseEffectiveDate, LicenseExpirationDate, LicenseClass, Password) VALUES 
('John', 'Doe', 'Male', '1986-01-01', 'Married', '0123456789', 'JoneDoe@gmail.com', '123 Main St', 'New York', 'NY', '10001', '1234567890', 'NY', 'Valid', '2018-01-01', '2020-01-01', 'A', '6514648A5880F138A561EEA75E20B9CEC7CCC1FA8C2607FA5993B8DC16306A6D');

CREATE TABLE customervehicle
(
    CustomerVehicleId       INT             PRIMARY KEY     AUTO_INCREMENT,
    CustomerId              INT             NOT NULL,

    `Year`                    INT             NOT NULL,
    Make                    VARCHAR(50)     NOT NULL,
    Model                   VARCHAR(50)     NOT NULL,
    Vin                     VARCHAR(17)     NOT NULL,
    `Usage`                 VARCHAR(50)     NOT NULL,
    PrimaryUse              VARCHAR(50)     NOT NULL,
    AnnualMileage           INT             NOT NULL,
    Ownership               VARCHAR(50)     NOT NULL,
    Deleted                 TINYINT(1)      NOT NULL DEFAULT 0,

    CONSTRAINT fk_customervehicle_CustomerId FOREIGN KEY (CustomerId) REFERENCES customer(CustomerId)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE=InnoDB;

INSERT INTO customervehicle (CustomerId, `Year`, Make, Model, Vin, `Usage`, PrimaryUse, AnnualMileage, Ownership) VALUES
(1, 2018, 'Honda', 'Accord', '12345678901234567', 'Pleasure', 'Commuting', 10000, 'Leased');


CREATE TABLE customerpolicy
(
    CustomerPolicyId        INT             PRIMARY KEY     AUTO_INCREMENT,
    PolicyStatus            VARCHAR(250)    NOT NULL DEFAULT 1,

    CustomerId              INT             NOT NULL,
    PolicyNo                INT             NOT NULL,

    PolicyEffectiveDate     DATE            NOT NULL DEFAULT CURRENT_DATE,
    PolicyExpirationDate    DATE            NOT NULL DEFAULT CURRENT_DATE,

    Deleted                 TINYINT(1)      NOT NULL DEFAULT 0,

    CONSTRAINT fk_customerpolicy_CustomerId FOREIGN KEY (CustomerId) REFERENCES customer(CustomerId)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

    CONSTRAINT fk_customerpolicy_PolicyNo FOREIGN KEY (PolicyNo) REFERENCES policy(PolicyNo)
    ON DELETE CASCADE
    ON UPDATE CASCADE

)ENGINE=InnoDB;

INSERT INTO customerpolicy (PolicyStatus, CustomerId, PolicyNo, PolicyEffectiveDate, PolicyExpirationDate) VALUES
('Active', 1, 1, '2018-01-01', '2019-01-01');

CREATE TABLE customerpolicydriver
(
    PolicyDriverId          INT             PRIMARY KEY     AUTO_INCREMENT,
    CustomerPolicyId        INT             NOT NULL,
    CustomerId              INT             NOT NULL,
    Deleted                 TINYINT(1)      NOT NULL DEFAULT 0,

    CONSTRAINT fk_customerpolicydriver_CustomerPolicyId FOREIGN KEY (CustomerPolicyId) REFERENCES customerpolicy(CustomerPolicyId)
    ON DELETE CASCADE
    ON UPDATE CASCADE

)ENGINE=InnoDB;

INSERT INTO customerpolicydriver (CustomerPolicyId, CustomerId) VALUES
(1, 1);

CREATE TABLE customerpolicyvehicle
(
    PolicyVehicleId         INT             PRIMARY KEY     AUTO_INCREMENT,
    CustomerPolicyId        INT             NOT NULL,
    CustomerVehicleId       INT             NOT NULL,
    PolicyCoverageId        INT             NOT NULL,
    Deleted                 TINYINT(1)      NOT NULL DEFAULT 0,

    CONSTRAINT fk_customerpolicyvehicle_CustomerPolicyId FOREIGN KEY (CustomerPolicyId) REFERENCES customerpolicy(CustomerPolicyId)
    ON DELETE CASCADE
    ON UPDATE CASCADE

)ENGINE=InnoDB;

INSERT INTO customerpolicyvehicle (CustomerPolicyId, CustomerVehicleId, PolicyCoverageId) VALUES
(1, 1, 1),
(1, 1, 2),
(1, 1, 3);


CREATE TABLE userlogin
(
	Id						BIGINT				PRIMARY KEY AUTO_INCREMENT,
	LoginDate				DATETIME			NOT NULL DEFAULT CURRENT_TIMESTAMP,
	Hash					CHAR(64)			NOT NULL DEFAULT '',
	Token					CHAR(32)			NOT NULL DEFAULT '',
	
	LoginTime				DATETIME			NOT NULL DEFAULT CURRENT_TIMESTAMP,
	LoginTimeInt			INT					NOT NULL DEFAULT 0,
	
	Device					VARCHAR(300)		NOT NULL DEFAULT '',
	IPAddress				VARCHAR(64)			NOT NULL DEFAULT '',
	

	Enabled					TINYINT(1)			NOT NULL DEFAULT 1,
	Deleted					TINYINT(1)			NOT NULL DEFAULT 0,
	UserId					INT					NOT NULL,
    UserType                INT                 NOT NULL DEFAULT 1
)ENGINE=InnoDB;