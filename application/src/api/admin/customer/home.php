<?php

use Care4Sure\Application\Model\Customer;

// Current Route is api/admin/customer

switch (UtilityClass::RequestMethod())
{
    case "POST":
        $customerRequest = Customer::mapFromRequest(UtilityClass::JSONRequest());

        if ($customerRequest == null)
        {
            JResponse::toErrorJsonResponse("Invalid customer details");
        }
        else
        {
            $customerRequest->setCustomerId(0);
            if ($customerRequest->save())
            {
                JResponse::toSuccessJsonResponse("Customer details saved successfully", $customerRequest->getCustomerId());
            }
            else
            {
                JResponse::toErrorJsonResponse("Failed to save customer details");
            }
        }

        break;

    case "PUT":

        $customerRequest = Customer::mapFromRequest(UtilityClass::JSONRequest());
        $currentCustomer =null;
        if ($customerRequest == null || $customerRequest->getCustomerId() == 0|| !($currentCustomer = Customer::getByCustomerId($customerRequest->getCustomerId())))
        {
            JResponse::toErrorJsonResponse("Invalid customer details");
        }
        else
        {
            if ($customerRequest->save())
            {
                JResponse::toSuccessJsonResponse("Customer details updated successfully", $customerRequest->getCustomerId());
            }
            else
            {
                JResponse::toErrorJsonResponse("Failed to update customer details");
            }
        }
        break;

    case "DELETE":
        $customerObject = SiteRoute::MapFromRoute($siteRoute, ['customerId']);

        if (ValidationClass::ValidateFullNumber($customerObject->customerId))
        {
            if (Customer::delete($customerObject->customerId))
            {
                JResponse::toSuccessJsonResponse("Customer details deleted successfully");
            }
            else
            {
                JResponse::toErrorJsonResponse("Failed to delete customer details");
            }
        }
        else
        {
            JResponse::toErrorJsonResponse("Invalid customer details");
        }

        break;

    case "GET":
    default:

        if ($siteRoute->IsUrlQuery)
        {
            $customerObject = SiteRoute::MapFromRoute($siteRoute, ['customerId']);

            if (ValidationClass::ValidateFullNumber($customerObject->customerId))
            {
                $siteRoute->renderJson(Customer::getByCustomerIdAsJson($customerObject->customerId));
            }
            else
            {
                $siteRoute->renderJson(null);
            }
        }
        else
        {
            $siteRoute->renderJson(Customer::getAllAsJson());
        }


        break;
}