<?php

use \OxidEsales\Eshop\Core\Registry;
use \OxidEsales\Eshop\Core\ConfigFile;
use \Webmozart\PathUtil\Path;

define('INSTALLATION_ROOT_PATH', Path::canonicalize(Path::join(__DIR__, '..')));
# Yes, adding a directory separator is stupid, but that's how the code expects it
define('OX_BASE_PATH', Path::join(INSTALLATION_ROOT_PATH, 'source') . DIRECTORY_SEPARATOR);
define('OX_LOG_FILE', Path::join(OX_BASE_PATH, 'log', 'testrun.log'));
# Yes, adding a directory separator is stupid, but that's how the code expects it
define('VENDOR_PATH', Path::join(INSTALLATION_ROOT_PATH, 'vendor') . DIRECTORY_SEPARATOR);
require Path::join(VENDOR_PATH, "autoload.php");
require Path::join(OX_BASE_PATH, "oxfunctions.php");
require Path::join(OX_BASE_PATH, "overridablefunctions.php");

setConfigFile();

function setConfigFile()
{
    $configFile = new ConfigFile(Path::join(OX_BASE_PATH, 'config.inc.php'));
    Registry::set(ConfigFile::class, $configFile);
}
