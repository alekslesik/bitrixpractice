<?php

//Финальный класс для работы с языковыми файлами
use Bitrix\Main\Localization\Loc;
//Регистрация модуля в системе
use Bitrix\Main\ModuleManager;
//Класс для работы с параметрами модулей, хранимых в базе данных
use Bitrix\Main\Config\Option;
//Класс кратко- и долгосрочной регистрации обработчиков событий
use Bitrix\Main\EventManager;
//Абстрактный класс для любых приложений
use Bitrix\Main\Application;
//Класс для работы с директориями
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
    * Copy js, css files to /bitrix dir
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

   /**
    * Uninstall module
    * @return bool|void
    */
   public function DoUninstall()
   {
      global $APPLICATION;

      $this->UnInstallFiles();
      $this->UnInstallDB();
      $this->UnInstallEvents();

      ModuleManager::unRegisterModule($this->MODULE_ID);

      $APPLICATION->IncludeAdminFile(
         Loc::getMessage("FALBAR_TOTOP_UNINSTALL_TITLE")." \"".Loc::getMessage("FALBAR_TOTOP_NAME")."\"",
         __DIR__."/unstep.php"
      );

      return false;
   }

   /**
    * Uninstall js, css files from /bitrix dir
    */
   public function UnInstallFiles()
   {
      Directory::deleteDirectory(
         Application::getDocumentRoot() . "/bitrix/js/" . $this->MODULE_ID
      );

      Directory::deleteDirectory(
         Application::getDocumentRoot() . "/bitrix/css/" . $this->MODULE_ID
      );

      return false;
   }

   /**
    * Uninstall DB settings
    */
   public function UnInstallDB()
   {
      Option::delete($this->MODULE_ID);
      return false;
   }

   public function UnInstallEvents()
   {
      EventManager::getInstance()->unRegisterEventHandler(
         "main",
         "OnBeforeEndBufferContent",
         $this->MODULE_ID,
         "Kasianov\ToTop\Main",
         "appendScriptsToPage"
      );

      return false;
   }

}



