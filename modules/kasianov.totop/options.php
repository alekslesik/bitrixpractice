<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages("__FILE__");

$request = HttpApplication::getInstance()->getContext()->getRequest();

$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

Loader::includeModule($module_id);

$aTabs = array(
  array(
     "DIV" => "edit",
     "TAB" =>  Loc::getMessage("FALBAR_TOTOP_OPTIONS_TAB_NAME"),
     "TITLE" =>   Loc::getMessage("FALBAR_TOTOP_OPTIONS_TAB_NAME"),
     "OPTIONS" => array(
        Loc::getMessage("FALBAR_TOTOP_OPTIONS_TAB_COMMON"),
        array(
           "switch_on",
           Loc::getMessage("FALBAR_TOTOP_OPTIONS_TAB_SWITCH_ON")
        )
     )
  )
);