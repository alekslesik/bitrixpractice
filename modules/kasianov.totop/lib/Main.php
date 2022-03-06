<?php
namespace Kasianov\Totop;

//Класс для работы с параметрами модулей, хранимых в базе данных
use Bitrix\Main\Config\Option;
//Класс отвечает за подключение стилей и скриптов
use Bitrix\Main\Page\Asset;

class Main {

   public function appendScriptsToPage() {
      if (!defined("ADMIN_SECTION") && $ADMIN_SECTION !== true) {
         $module_id = pathinfo(dirname(__DIR__))["basename"];


      }
   }
}