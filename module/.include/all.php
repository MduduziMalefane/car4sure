<?php

global $COMNDIR;
global $INCLUDEDIR;

global $UTILDIR;
global $APIDIR;
global $PAGEDIR;
global $CODEDIR;
global $CLASSDIR;
global $ENCRYPTDIR;
global $DATADIR;
global $LOGSDIR;

global $THIRDPARTY;

$PAGEDIR = "application/src/";

$COMNDIR = "$BASEDIR/.common/";
$INCLUDEDIR = "$BASEDIR/.include/";

$UTILDIR = "$BASEDIR/.util/";
$APIDIR = "$BASEDIR/.api/";


$DATADIR = 'data/';
$LOGSDIR = $DATADIR . 'logs/';


include "$BASEDIR/Logging/LogClass.php";
set_error_handler("WriteLog");  // pass error handling to our log file handler
register_shutdown_function("WriteFatal");

$loader = new \Twig\Loader\FilesystemLoader('application/view');
$twigViewEngine = new \Twig\Environment($loader, [
    'cache' => 'data/cache/twig',
]);

$pageContext = array();
