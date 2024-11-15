<?php

use Care4Sure\Application\Model\Policy;

// Current Route is api/admin/policy

switch (UtilityClass::RequestMethod())
{
    case "POST":
        $policyRequest = Policy::mapFromRequest(UtilityClass::JSONRequest());

        if ($policyRequest == null)
        {
            JResponse::toErrorJsonResponse("Invalid coverage details");
        }
        else
        {
            $policyRequest->setPolicyNo(0);
            if ($policyRequest->save())
            {
                JResponse::toSuccessJsonResponse("Policy details saved successfully", $policyRequest->getPolicyNo());
            }
            else
            {
                JResponse::toErrorJsonResponse("Failed to save coverage details");
            }
        }

        break;

    case "PUT":

        $policyRequest = Policy::mapFromRequest(UtilityClass::JSONRequest());
        $currentPolicy =null;
        if ($policyRequest == null || $policyRequest->getPolicyNo() == 0|| !($currentPolicy = Policy::getByPolicyNo($policyRequest->getPolicyNo())))
        {
            JResponse::toErrorJsonResponse("Invalid coverage details");
        }
        else
        {
            if ($policyRequest->save())
            {
                JResponse::toSuccessJsonResponse("Policy details updated successfully", $policyRequest->getPolicyNo());
            }
            else
            {
                JResponse::toErrorJsonResponse("Failed to update policy details");
            }
        }
        break;

    case "DELETE":
        $policyObject = SiteRoute::MapFromRoute($siteRoute, ['policyNo']);

        if (ValidationClass::ValidateFullNumber($policyObject->policyNo))
        {
            if (Policy::delete($coverageObject->policyNo))
            {
                JResponse::toSuccessJsonResponse("Policy details deleted successfully");
            }
            else
            {
                JResponse::toErrorJsonResponse("Failed to delete policy details");
            }
        }
        else
        {
            JResponse::toErrorJsonResponse("Invalid policy details");
        }

        break;

    case "GET":
    default:

        if ($siteRoute->IsUrlQuery)
        {
            $policyObject = SiteRoute::MapFromRoute($siteRoute, ['policyNo']);

            if (ValidationClass::ValidateFullNumber($policyObject->policyNo))
            {
                $siteRoute->renderJson(Policy::getByPolicyNoAsJson($policyObject->policyNo));
            }
            else
            {
                $siteRoute->renderJson(null);
            }
        }
        else
        {
            $siteRoute->renderJson(Policy::getAllAsJson());
        }


        break;
}