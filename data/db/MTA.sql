CREATE DATABASE IF NOT EXISTS insurance_1;
USE insurance_1;

CREATE TABLE policy (
    policyNo VARCHAR(20) PRIMARY KEY,
    policyStatus VARCHAR(20),
    policyType VARCHAR(20),
    policyEffectiveDate DATE,
    policyExpirationDate DATE
);

CREATE TABLE policyHolder (
    policyNo VARCHAR(20),
    firstName VARCHAR(50),
    lastName VARCHAR(50),
    street VARCHAR(100),
    city VARCHAR(50),
    state VARCHAR(2),
    zip VARCHAR(10),
    PRIMARY KEY (policyNo),
    FOREIGN KEY (policyNo) REFERENCES policy(policyNo)
);

CREATE TABLE driver (
    policyNo VARCHAR(20),
    firstName VARCHAR(50),
    lastName VARCHAR(50),
    age INT,
    gender VARCHAR(10),
    maritalStatus VARCHAR(20),
    licenseNumber VARCHAR(20),
    licenseState VARCHAR(2),
    licenseStatus VARCHAR(20),
    licenseEffectiveDate DATE,
    licenseExpirationDate DATE,
    licenseClass VARCHAR(5),
    PRIMARY KEY (policyNo, licenseNumber),
    FOREIGN KEY (policyNo) REFERENCES policy(policyNo)
);

CREATE TABLE vehicle (
    policyNo VARCHAR(20),
    year INT,
    make VARCHAR(50),
    model VARCHAR(50),
    vin VARCHAR(20),
    usage VARCHAR(20),
    primaryUse VARCHAR(20),
    annualMileage INT,
    ownership VARCHAR(20),
    street VARCHAR(100),
    city VARCHAR(50),
    state VARCHAR(2),
    zip VARCHAR(10),
    PRIMARY KEY (policyNo, vin),
    FOREIGN KEY (policyNo) REFERENCES policy(policyNo)
);

CREATE TABLE coverage (
    policyNo VARCHAR(20),
    vin VARCHAR(20),
    type VARCHAR(20),
    limit INT,
    deductible INT,
    PRIMARY KEY (policyNo, vin, type),
    FOREIGN KEY (policyNo, vin) REFERENCES vehicle(policyNo, vin)
);