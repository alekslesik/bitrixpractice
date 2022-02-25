<?php

namespace SomePartner\MyBooksCatalog;

use Bitrix\Main\Type;

// return obj AddResult
$result = BookTable::add(array(
   'ISBN' => '978-0321127426',
   'TITLE' => 'Patterns of Enterprise Application Architecture',
   'PUBLISH_DATE' => new Type\Date('2002-11-16', 'Y-m-d')
));

if ($result-> isSuccess()) {
   $id = $result->getId();
}

// return obj AddResult
$result = BookTable::update($id, array(
   'PUBLISH_DATE' => new Type\Date('2002-11-15', 'Y-m-d')
));

if (!$result->isSuccess())
{
   $errors = $result->getErrorMessages();
}

$result = BookTable::delete($id);

$book = \Bitrix\Main\Test\Typography\BookTable::getByPrimary(1)
   ->fetchObject();




