<?php

class SphinxXml2Pipe {


  public function __construct()
  {
    // Файл с адресами
    $this->reader = XMLReader::open('/tmp/src/AS_ADDROBJ_20190303_769b2e2b-691b-422e-8328-5dc87ab03991.XML');
  }

  public function run()
  {
    // ini_set("memory_limit","128M"); // Включить в условиях ограниченного объема памяти
    $this->startXml();
    $this->putXmlSchema();

    $this->docID = 0;

    foreach ($this->getDocs() as $this->doc) {
      $this->docID++;
      $this->putXmlDoc();
      fwrite(STDOUT, $this->xml->flush(true)); // против переполнения памяти
      // if($this->docID >= 10000) break; // TEST CASE
    }

    $this->endXml();
    fwrite(STDOUT, $this->xml->flush(true)); // против переполнения памяти
  }

  private function getDocs()
  {
    $reader =& $this->reader;
    while ($reader->read()) {

      if($reader->nodeType !== XMLReader::ELEMENT) {
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
      ];


      // $reader->getAttribute('AOID');
      // $reader->getAttribute('POSTALCODE');  // Почтовый индекс
      // $reader->getAttribute('CURRSTATUS');  // 0 - актуальный
      // $reader->getAttribute('PLANCODE');    // Код по Кладр
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
      // $reader->getAttribute('PLAINCODE');
      // $reader->getAttribute('CODE');
      // $reader->getAttribute('ACTSTATUS');
      // $reader->getAttribute('LIVESTATUS');
      // $reader->getAttribute('CENTSTATUS');
      // $reader->getAttribute('OPERSTATUS');
      // $reader->getAttribute('IFNSFL');
      // $reader->getAttribute('IFNSUL');
      // $reader->getAttribute('OKATO');
      // $reader->getAttribute('STARTDATE');
      // $reader->getAttribute('ENDDATE');
      // $reader->getAttribute('UPDATEDATE');
      // $reader->getAttribute('DIVTYPE');

    }

  }

  private $xml;
  private function startXml()
  {
    $this->xml = new XmlWriter();

    $this->xml->openMemory();
    $this->xml->setIndent(true);
    $this->xml->setIndentString('  ');
    $this->xml->startDocument('1.0', 'utf-8');

      $this->xml->startElement('sphinx:docset');

  }

  private function putXmlSchema()
  {
    $this->xml->startElement('sphinx:schema');

      $this->xml->startElement('sphinx:field');
      $this->xml->writeAttribute('name', 'formalname');
      $this->xml->endElement();

      $this->xml->startElement('sphinx:field');
      $this->xml->writeAttribute('name', 'shortname');
      $this->xml->endElement();

      $this->xml->startElement('sphinx:attr');
        $this->xml->writeAttribute('name', 'text');
        $this->xml->writeAttribute('type', 'string');
      $this->xml->endElement();

      $this->xml->startElement('sphinx:attr');
        $this->xml->writeAttribute('name', 'aoguid');
        $this->xml->writeAttribute('type', 'string');
      $this->xml->endElement();

      $this->xml->startElement('sphinx:attr');
        $this->xml->writeAttribute('name', 'parentguid');
        $this->xml->writeAttribute('type', 'string');
      $this->xml->endElement();

      $this->xml->startElement('sphinx:attr');
        $this->xml->writeAttribute('name', 'aolevel');
        $this->xml->writeAttribute('type', 'int');
        $this->xml->writeAttribute('bits', 8);
      $this->xml->endElement();

    $this->xml->endElement();
  }

  private function putXmlDoc()
  {
    $doc =& $this->doc;
    $this->xml->startElement('sphinx:document');
    $this->xml->writeAttribute('id', $this->docID);

    $this->xml->writeElement('formalname', $doc['formalname']);
    $this->xml->writeElement('shortname', $doc['shortname']);
    $this->xml->writeElement('text', $doc['shortname'] . ' ' . $doc['formalname']);
    $this->xml->writeElement('aoguid', $doc['aoguid']);
    $this->xml->writeElement('parentguid', $doc['parentguid']);
    $this->xml->writeElement('aolevel', $doc['aolevel']);

    $this->xml->endElement();
  }

  private function endXml()
  {
    $this->xml->endElement();
  }

}


$pipe = new SphinxXml2Pipe();
$pipe->run();
