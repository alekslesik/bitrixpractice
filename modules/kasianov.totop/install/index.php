<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;


Loc::loadMessages(__FILE__);

class kasianov_totop extends CModule
{
   /**
    * kasianov_totop constructor.
    */
   public function __construct()
   {
      if (file_exists(__DIR__ . "/version.php")) {
         $arModuleVersion = array();
         include_once(__DIR__ . "/version.php");

         $this->MODULE_ID = str_replace("_", ".", get_class($this));
         $this->MODULE_VERSION = $arModuleVersion["VERSION"];
         $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
         $this->MODULE_NAME = Loc::getMessage("FALBAR_TOTOP_NAME");
         $this->MODULE_DESCRIPTION = Loc::getMessage("FALBAR_TOTOP_DESCRIPTION");
         $this->PARTNER_NAME = Loc::getMessage("FALBAR_TOTOP_PARTNER_NAME");
         $this->PARTNER_URI = Loc::getMessage("FALBAR_TOTOP_PARTNER_URI");
      }
   }

   /**
    * Install module.
    */
   public function DoInstall()
   {
      global $APPLICATION;

      if (CheckVersion(ModuleManager::getVersion("main"), "14.00.00")) {
         $this->InstallFiles();
         $this->InstallDB();

         ModuleManager::registerModule($this->MODULE_ID);

         $this->InstallEvents();

      } else {
         $APPLICATION->ThrowException(
            Loc::getMessage("FALBAR_TOTOP_INSTALL_ERROR_VERSION")
         );

         $APPLICATION->IncludeAdminFile(
            Loc::getMessage("FALBAR_TOTOP_INSTALL_TITLE") . " \"" . Loc::getMessage("FALBAR_TOTOP_NAME")
            . "\"", __DIR__ . "/step.php"
         );

         return false;
      }
   }

   /**
    * Copy files to /bitrix dir
    * @return bool|void
    */
   public function InstallFiles()
   {
      CopyDirFiles(
         __DIR__ . "assets/scripts",
         Application::getDocumentRoot() . "bitrix/js/" . $this->MODULE_ID . "/",
         true,
         true
      );

      CopyDirFiles(
         __DIR__ . "assets/styes",
         Application::getDocumentRoot() . "bitrix/css/" . $this->MODULE_ID . "/",
         true,
         true
      );

      return false;
   }

   /**
    * Install DB
    * @return bool
    */
   public function InstallDB()
   {
      return false;
   }


   /**
    * Event registration
    * @return bool|void
    */
   public function InstallEvents()
   {
      EventManager::getInstance()->registerEventHandler(
         "main",
         "OnBeforeEndBufferContent",
         $this->MODULE_ID,
         "Kasianov\ToTop\Main",
         "appendScriptsToPage"
      );

      return false;
   }
}



