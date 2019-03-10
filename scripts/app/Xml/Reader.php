<?php

namespace App\Xml;

class Reader {

  private $reader;

  public function __construct($srcFile)
  {
    $this->reader = \XMLReader::open($srcFile);
  }

  public function fetch()
  {
    $reader =& $this->reader;
    while ($reader->read()) {

      if($reader->nodeType !== \XMLReader::ELEMENT) {
        continue;
      }

      if($reader->name === 'AddressObjects') continue;

      if($reader->name !== 'Object') {
        throw new \Exception(sprintf("Not expected name: %s", $reader->name));
      }

      // 0 - актуальный
      if($reader->getAttribute('CURRSTATUS') != 0) continue;

      yield [
        'aoguid'      => $reader->getAttribute('AOGUID'),      // GUID ФИАС
        'parentguid'  => $reader->getAttribute('PARENTGUID'),  // GUID родителя ФИАС
        'aolevel'     => $reader->getAttribute('AOLEVEL'),     // Уровень ФИАС (1 - регион, 7 - улица)
        'formalname'  => $reader->getAttribute('FORMALNAME'),  // Название
        'shortname'   => $reader->getAttribute('SHORTNAME'),   // Тип
        'postalcode'  => $reader->getAttribute('POSTALCODE'),  // Почтовый индекс
        'code'        => $reader->getAttribute('CODE'),        // код КЛАДР
        'okato'       => $reader->getAttribute('OKATO'),       // код объекта административно-территориального деления (ОКАТО)
        'oktmo'       => $reader->getAttribute('OKTMO'),       // код муниципального образования (ОКТМО)
      ];


      // $reader->getAttribute('AOID');
      // $reader->getAttribute('CURRSTATUS');  // 0 - актуальный
      // $reader->getAttribute('OFFNAME');
      // $reader->getAttribute('REGIONCODE');
      // $reader->getAttribute('AREACODE');
      // $reader->getAttribute('AUTOCODE');
      // $reader->getAttribute('CITYCODE');
      // $reader->getAttribute('CTARCODE');
      // $reader->getAttribute('PLACECODE');
      // $reader->getAttribute('STREETCODE');
      // $reader->getAttribute('EXTRCODE');
      // $reader->getAttribute('SEXTCODE');
      // $reader->getAttribute('PLANCODE');  // Код по Кладр ?
      // $reader->getAttribute('PLAINCODE'); // код КЛАДР без признака актуальности (последних двух цифр), см. также CURRSTATUS
      // $reader->getAttribute('ACTSTATUS');
      // $reader->getAttribute('LIVESTATUS');
      // $reader->getAttribute('CENTSTATUS');
      // $reader->getAttribute('OPERSTATUS');
      // $reader->getAttribute('IFNSFL');
      // $reader->getAttribute('IFNSUL');
      // $reader->getAttribute('STARTDATE');
      // $reader->getAttribute('ENDDATE');
      // $reader->getAttribute('UPDATEDATE');
      // $reader->getAttribute('DIVTYPE');

    }

  }

}
