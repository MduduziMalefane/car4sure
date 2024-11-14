<?php

// Controller
use CAR4SURE\Application\Model\PolicyHolder;

$siteRoute->addContext("policyList", PolicyHolder::getAll());
$siteRoute->renderView();
