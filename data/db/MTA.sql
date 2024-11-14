CREATE DATABASE IF NOT EXISTS insurance_1;
USE insurance_1;

CREATE TABLE policy (
    policyNo int PRIMARY KEY,
    policyStatus VARCHAR(20),
    policyType VARCHAR(20),
    policyEffectiveDate DATE,
    policyExpirationDate DATE
);

CREATE TABLE policyHolder (
    policyHolderID VARCHAR(20),
    policyNo VARCHAR(20),  -- Added policyNo column for foreign key reference
    firstName VARCHAR(50),
    lastName VARCHAR(50),
    street VARCHAR(100),
    city VARCHAR(50),
    state VARCHAR(2),
    zip VARCHAR(10),
    PRIMARY KEY (policyHolderID),
    FOREIGN KEY (policyNo) REFERENCES policy(policyNo)
);
CREATE TABLE user (
    userID int,
    policyNo VARCHAR(20), 
    firstName VARCHAR(50),
    lastName VARCHAR(50),
    age INT,
    gender VARCHAR(10),
    maritalStatus VARCHAR(20),
    PRIMARY KEY (userID),
    FOREIGN KEY (policyNo) REFERENCES policy(policyNo)
);

CREATE TABLE vehicle (
    plateID int,
    userID INT,
    year INT,
    make VARCHAR(50),
    model VARCHAR(50),
    vin VARCHAR(20),
    `usage` VARCHAR(20),       -- Enclosed in backticks
    primaryUse VARCHAR(20),
    annualMileage INT,
    ownership VARCHAR(20),
    PRIMARY KEY (plateID),
    FOREIGN KEY (userID) REFERENCES user(userID)
);


CREATE TABLE coverage (
    coverageID INT,
    type VARCHAR(20),
    `limit` INT,              -- Enclosed in backticks
    deductible INT,
    policyNo INT,             -- Define policyNo in this table
    PRIMARY KEY (coverageID),
    FOREIGN KEY (policyNo) REFERENCES policy(policyNo)
);

CREATE TABLE license (
    licenseNo int,
    licenseState VARCHAR(20),
    licenseStatus VARCHAR(20),
    licenseEffectiveDate datetime(0) NOT NULL DEFAULT current_timestamp(0),
    licenseExpirationDate datetime(0) NOT NULL DEFAULT current_timestamp(0),
    licenseClass VARCHAR(20),
    PRIMARY KEY (licenseNo),
    FOREIGN KEY (userID) REFERENCES user(userID)
);

CREATE TABLE policytype (
    policytypeID int,
    description VARCHAR(20),
    policyNo int,
    PRIMARY KEY (policytypeID),
    FOREIGN KEY (policyNo) REFERENCES user(policyNo)
);

CREATE TABLE garageaddress (
    garageID int,
    streetName VARCHAR(20),
    city VARCHAR(20),
    state VARCHAR(20),
    zip int,
    plateID int,
    PRIMARY KEY (garageID),
    FOREIGN KEY (plateID) REFERENCES vehicle(plateID)
);