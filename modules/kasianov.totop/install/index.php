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
   public function __construct()
   {
      if (file_exists(__DIR__ . "/version.php")) {
         $arModuleVersion = array();
         include_once(__DIR__ . "/version.php");

         $this->MODULE_ID = str_replace("_", ".", get_class($this));
         $this->MODULE_VERSION = $arModuleVersion["VERSION"];
         $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
         $this->MODULE_NAME = Loc::getMessage("FALBAR_TOTOP_NAME");
         $this->MODULE_DESCRIPTION  = Loc::getMessage("FALBAR_TOTOP_DESCRIPTION");
         $this->PARTNER_NAME     = Loc::getMessage("FALBAR_TOTOP_PARTNER_NAME");
         $this->PARTNER_URI      = Loc::getMessage("FALBAR_TOTOP_PARTNER_URI");
      }
   }

}

