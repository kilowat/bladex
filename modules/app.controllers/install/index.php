<?

IncludeModuleLangFile(__FILE__);
use \Bitrix\Main\ModuleManager;

Class APP_CONTROLLERS extends CModule
{
    public $MODULE_ID = "app.controllers";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $errors;

    function __construct()
    {
        $this->MODULE_VERSION = "1.0.0";
        $this->MODULE_VERSION_DATE = "01.01.2022";
        $this->MODULE_NAME = "app_controllers";
        $this->MODULE_DESCRIPTION = "";
    }

    function DoInstall()
    {
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();
        \Bitrix\Main\ModuleManager::RegisterModule($this->MODULE_ID);
        return true;
    }

    function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        \Bitrix\Main\ModuleManager::UnRegisterModule($this->MODULE_ID);
        return true;
    }

    function InstallDB()
    {
        return true;
    }

    function UnInstallDB()
    {
        return true;
    }

    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }

    function InstallFiles()
    {
        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }
}