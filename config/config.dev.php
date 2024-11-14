<?php

unset($config);
global $config;
$config = new stdClass();

$config->IsLive = false;

$config->SiteOffline = false;

// Session Config
$config->sessExpire = 86400;
$config->sessKey = 'UD$a0uPHQZg(FfZZGwb85DF:qFY0TG';

$config->sessID = "mtasos"; // Session ID
$config->cookID = "mtlii"; // Cookie ID

// Hash algorithm to use for passwords
$config->hashUsed = "sha256"; //md5 sha1 sha256

// Hash Salting Config Section
$config->hashHasSalt = true;
$config->hashSaltKey = 'ywP[i(bF$N[%j!H;6{5=Zfkk}FD(0]';


global $BASEPATH;
// Database Confg

$config->DBServer = 'localhost';
$config->DBUser = 'root';
$config->DBPass = '';
$config->DBName = 'insurance_1';

// Url Config
$BASEPATH = "";
$SITEURL = "http://car4sure.local" . $BASEPATH . "/";
$config->SITEURL = $SITEURL;

// File Location
$SYSTEMPATH_RESOURCE = $SYSTEMPATH;

$config->routeCachePath = "data/cache/routes.php";
$config->controllerPath = "application/controller";
$config->viewPath = "application/view";
$config->modelPath = "application/model";
$config->viewCachePath = 'data/cache/twig';