<?php

use Care4Sure\Application\Model\Coverage;

// Current Route is api/admin/coverage

switch (UtilityClass::RequestMethod())
{
    case "POST":
        $coverageRequest = Coverage::mapFromRequest(UtilityClass::JSONRequest());

        if ($coverageRequest == null)
        {
            JResponse::toErrorJsonResponse("Invalid coverage details");
        }
        else
        {
            $coverageRequest->setCoverageId(0);
            if ($coverageRequest->save())
            {
                JResponse::toSuccessJsonResponse("Coverage details saved successfully", $coverageRequest->getCoverageId());
            }
            else
            {
                JResponse::toErrorJsonResponse("Failed to save coverage details");
            }
        }

        break;

    case "PUT":

        $coverageRequest = Coverage::mapFromRequest(UtilityClass::JSONRequest());
        $currentCoverage =null;
        if ($coverageRequest == null || $coverageRequest->getCoverageId() == 0|| !($currentCoverage = Coverage::getByCoverageId($coverageRequest->getCoverageId())))
        {
            JResponse::toErrorJsonResponse("Invalid coverage details");
        }
        else
        {
            if ($coverageRequest->save())
            {
                JResponse::toSuccessJsonResponse("Coverage details updated successfully", $coverageRequest->getCoverageId());
            }
            else
            {
                JResponse::toErrorJsonResponse("Failed to update coverage details");
            }
        }
        break;

    case "DELETE":
        $coverageObject = SiteRoute::MapFromRoute($siteRoute, ['coverageId']);

        if (ValidationClass::ValidateFullNumber($coverageObject->coverageId))
        {
            if (Coverage::delete($coverageObject->coverageId))
            {
                JResponse::toSuccessJsonResponse("Coverage details deleted successfully");
            }
            else
            {
                JResponse::toErrorJsonResponse("Failed to delete coverage details");
            }
        }
        else
        {
            JResponse::toErrorJsonResponse("Invalid coverage details");
        }

        break;

    case "GET":
    default:

        if ($siteRoute->IsUrlQuery)
        {
            $coverageObject = SiteRoute::MapFromRoute($siteRoute, ['coverageId']);

            if (ValidationClass::ValidateFullNumber($coverageObject->coverageId))
            {
                $siteRoute->renderJson(Coverage::getByCoverageIdAsJson($coverageObject->coverageId));
            }
            else
            {
                $siteRoute->renderJson(null);
            }
        }
        else
        {
            $siteRoute->renderJson(Coverage::getAllAsJson());
        }


        break;
}