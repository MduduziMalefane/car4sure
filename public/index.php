<?php

use TLC\Application\Model\Member;

chdir(dirname(__DIR__));

// Set Server PHP Date
date_default_timezone_set("Africa/Johannesburg");

global $CURRPAGE;
$CURRPAGE = "";

global $SITEDIR;
$SITEDIR = "";

global $BASEDIR;
$BASEDIR = "module";

global $SITE_LAYOUT_DIR;
$SITE_LAYOUT_DIR = "application/view/layout/";

global $SYSTEMPATH;
$SYSTEMPATH = str_replace("\\", "/", getcwd() . "/");

global $SYSTEMPATHBASE;
$SYSTEMPATHBASE = $SYSTEMPATH . $BASEDIR . '/';

$CONFIGDIR = "config/";
if (file_exists($CONFIGDIR . "config.dev.php"))
{
    include $CONFIGDIR . "config.dev.php";
}
else
{
    include $CONFIGDIR . "config.php";
}


// if the request is not on a localhost we redirect the request to a https version of the site
if (!isset($_SERVER['HTTPS']) && $config->IsLive == true)
{

    $redirectPort = $_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443" ? ':' . $_SERVER["SERVER_PORT"] : "";
    header('Location: https://' . $_SERVER["SERVER_NAME"] . $redirectPort . $_SERVER['REQUEST_URI']);
    exit();
}


//echo $SYSTEMPATHBASE;
// Important Directories /////////////////////
include "vendor/autoload.php";
include "$BASEDIR/.include/all.php";

////////////////////////////////////////////
/////////// Index Configs  /////////////
$denyAccess = false;
$denyAddress = "notfound";
$denyPhp = $PAGEDIR . "notfound.php";

$DefaultPage = "home";
$canView = true;

$ownMenu = false;
$addHeader = false;
$ownFooter = false;
//////////////////////////////////////


$isSubmit = UtilityClass::Post('submit') == '1' || UtilityClass::Post('ajax') == '1' || UtilityClass::Post('post') == '1' || UtilityClass::Get('submit') == '1' || UtilityClass::Get('ajax') == '1' || UtilityClass::Get('post') == '1';
$hasMenu = true;

global $siteRoute;
$siteRoute = SiteRoute::getSitePath($_SERVER['REQUEST_URI'], $DefaultPage, strlen($BASEPATH), $twigViewEngine);

///////////////////////////////////////////////////


$phpFile = "";
// we use this backup url just incase we access a php file thats on the home part
// e.g /member/ > member was not found but /member/home.php was found
$phpFileDefault = "";

// Main php to run
$MainPhpRun = "";

// directories to skip permissions, starting from the back
$pathSkip = 1; // 
$skipSessionRead = false;
/////////////////////////////////////////////////

if ($siteRoute->valid)
{

    $phpFile = $PAGEDIR . $siteRoute->pathRoute . ".php";
    $phpFileDefault = $PAGEDIR . $siteRoute->pathRoute . "/$DefaultPage.php";

}
else
{
    // Patch 1.0 fixed for invalid paths e.g $site/aPath/index.php vs $site/aPath/index/
    // should a path not be defined we bail and call home, else redirect to not found
    if (!$siteRoute->hasPath)
    {
        $phpFile = $PAGEDIR . "$DefaultPage.php";
    }
    else
    {
        header("location: $SITEURL$denyAddress");
        exit();
    }
}

if (file_exists($phpFile))
{
    $pathScripts = SiteRoute::BuildPathMod($siteRoute->pathParts, $pathSkip);
    $cnt = count($pathScripts);

    for ($i = 0; $i < $cnt; $i++)
    {
        $PermitPath = $PAGEDIR . $pathScripts[$i];
        $PagePath = $SITEURL . $pathScripts[$i];
        $pathScript = $PermitPath . ".php";

        $IsMainPage = ($i + 1 == $cnt);
        if (file_exists($pathScript))
        {
            include $pathScript;
        }
    }
    $MainPhpRun = $phpFile;
}
else if ($phpFileDefault != "" && file_exists($phpFileDefault))
{
    $siteRoute->pageName = $DefaultPage;
    $siteRoute->fullRoute = $siteRoute->pathRoute . "/$DefaultPage";

    $pathScripts = SiteRoute::BuildPathMod($siteRoute->pathParts);
    $cnt = count($pathScripts);

    for ($i = 0; $i < $cnt; $i++)
    {

        $PermitPath = $PAGEDIR . $pathScripts[$i];
        $PagePath = $SITEURL . $pathScripts[$i];
        $pathScript = $PermitPath . ".php";

        $IsMainPage = ($i + 1 == $cnt);
        if (file_exists($pathScript))
        {
            include $pathScript;
        }
    }
    $MainPhpRun = $phpFileDefault;
    $siteRoute->pathRoot = $siteRoute->pathRoute;

    if ($siteRoute->routeCount > 0)
    {
        $siteRoute->pathRoot = $siteRoute->pathRoute . "/";
    }
}
else
{
    // If the redirected page is already our redirection page we just try to show a shadow page
    if ($phpFile != $denyPhp)
    {
        $siteRoute->redirect("/notfound");
    }

    exit();
}

$siteRoute->addContext("SITEURL", $config->SITEURL);
//$siteRoute->addContext("UserInfo", $UserInfo);
$siteRoute->addContext("currentTime", time());
$siteRoute->addContext("currentDate", date("Y-m-d"));
$siteRoute->addContext("currentDateTime", date("Y-m-d H:i:s"));
$siteRoute->addContext("currentYear", date("Y"));
$siteRoute->addContext("siteName", "MTA");

$CURRPAGE = $SITEURL . $siteRoute->pathRoute;

include $MainPhpRun;

