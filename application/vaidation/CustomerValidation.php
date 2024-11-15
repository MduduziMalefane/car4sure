<?php

namespace Care4Sure\Application\Validation;

class CustomerValidation
{

    public static function validateCustomerJson($customer): bool
    {
    
        if (!$customer) {
            return false;
        }

        if (!\ValidationClass::ValidateText($customer->firstName) ||
            !\ValidationClass::ValidateText($customer->lastName) ||
            !\ValidationClass::ValidateAlphabet($customer->gender) ||
            !\ValidationClass::ValidateDate($customer->dateOfBirth) ||
            !\ValidationClass::ValidateDateTime($customer->dateRegistered) ||
            !\ValidationClass::ValidateText($customer->maritalStatus) ||
            !\ValidationClass::ValidateContactNumber($customer->contactNo) ||
            !\ValidationClass::ValidateEmail($customer->email) ||
            !\ValidationClass::ValidateNormalText($customer->street) ||
            !\ValidationClass::ValidateText($customer->city) ||
            !\ValidationClass::ValidateText($customer->state) ||
            !\ValidationClass::ValidateNumber($customer->zipCode) ||
            !\ValidationClass::ValidateNormalText($customer->licenseNumber) ||
            !\ValidationClass::ValidateText($customer->licenseState) ||
            !\ValidationClass::ValidateText($customer->licenseStatus) ||
            !\ValidationClass::ValidateDate($customer->licenseEffectiveDate) ||
            !\ValidationClass::ValidateDate($customer->licenseExpirationDate) ||
            !\ValidationClass::ValidateText($customer->licenseClass) ||
            !\ValidationClass::ValidateNormalText($customer->password)) {
            return false;
        }

        return true;
    }

}