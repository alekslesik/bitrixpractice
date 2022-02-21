<?php


namespace SomePartner\MyBooksCatalog;

use Bitrix\Main\Entity;
use Bitrix\Main\EventResult;
use Bitrix\Main\ORM\Event;
use Bitrix\Main\Type\Date;
use function MongoDB\Driver\Monitoring\removeSubscriber;


//Book
//  ID int [autoincrement, primary]
//  ISBN str [match: /[0-9X-]+/]
//  TITLE str [max_length: 50]
//  PUBLISH_DATE date

class BookTable extends Entity\DataManager
{

   public static function getTableName()
   {
      return 'my_book';
   }

   public static function getUfId()
   {
      return 'MY_BOOK';
   }

   public static function getMap()
   {
      return array(
         new Entity\IntegerField('ID', array(
            'primary' => true, // первичный ключ
            'autocomplete' => true, //
         )),
         new Entity\StringField('ISBN', array(
            'required' => true, // обязательное поле
            'column_name' => 'ISBNCODE',// заменяем старое имя колонки
            'validation' => function() {
               return array(
                  function ($value) {
                     $clean = str_replace('-', '', $value);
                     if (preg_match('/^\d{13}$/', $clean))
                     {
                        return true;
                     }
                     else
                     {
                        return 'Код ISBN должен содержать 13 цифр';
                     }
                  }
               );
            }
         )),
         new Entity\StringField('TITLE'),
         new Entity\DateField('PUBLISH_DATE', array(
            'default_value' => function () {
               // последняя пятница
               $lastFriday = \date('Y-m-d', strtotime('last friday'));
               return new Date($lastFriday, 'Y-m-d');
            }
         )),
//         SELECT DATEDIFF(NOW(), PUBLISH_DATE) AS AGE_DAYS FROM my_book
         new Entity\ExpressionField('AGE_DAYS',
            'DATEDIFF(NOW(), %s)', array('PUBLISH_DATE')),

         new Entity\TextField('EDITIONS_ISBN', array(
//            сериалищация значения перед сохранением в бд
            'save_data_modification' => function() {
               return array(
                  function ($value) {
                     return serialize($value);
                  }
               );
            },
//            рассериализация при выборке из бд
            'fetch_data_modification' => function() {
               return array(
                  function ($value) {
                     return unserialize($value);
                  }
               );
            }
         )),

      );
   }

   public static function onBeforeAdd(Event $event)
   {
      $result = new Entity\EventResult();
      $data = $event->getParameter('fields');

      if (isset($data['ISBN']))
      {
         $cleanIsbn = str_replace('-', '', $data['ISBN']);
         $result->modifyFields(array('ISBN' => $cleanIsbn));
      }

      return $result;
   }


//   запрет обновления ISBN кода для уже существующих в каталоге книг
   public static function onBeforeUpdate(Event $event)
   {
      $result = new EventResult();
      $data = $event->getParameter("fields");

      if (isset($data['ISBN'])) {
         $result->unsetFields(array('ISBN'));
      }

      return $result;
   }
}

