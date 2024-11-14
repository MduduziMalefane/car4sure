<?php

// Controller
use 1_CAR4SURE\Application\Model\Policyholder;

$siteRoute->addContext("packageList", Policyholder::getAll());
$siteRoute->render('dashboard.twig');
