<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';

class Xmlpipe2 {

  public function __construct()
  {
    $this->reader = new Sqlite\Reader("sqlite:/var/fias.db");
  }

  public function run()
  {
    $this->startXml();
    $this->putXmlSchema();

    $this->docID = 0;

    foreach ($this->reader->fetch() as $this->doc) {
      $this->docID++;
      $this->putXmlDoc();
      fwrite(STDOUT, $this->xml->flush(true)); // против переполнения памяти
      // if($this->docID >= 10) break; // TEST CASE
    }

    $this->endXml();
    fwrite(STDOUT, $this->xml->flush(true)); // против переполнения памяти
  }



  private $xml;
  private function startXml()
  {
    $this->xml = new \XmlWriter();

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
        $this->xml->writeAttribute('name', 'text');
      $this->xml->endElement();

      $this->xml->startElement('sphinx:attr');
        $this->xml->writeAttribute('name', 'address');
        $this->xml->writeAttribute('type', 'string');
      $this->xml->endElement();

      $this->xml->startElement('sphinx:attr');
        $this->xml->writeAttribute('name', 'aoguid');
        $this->xml->writeAttribute('type', 'string');
      $this->xml->endElement();

    $this->xml->endElement();
  }

  private function putXmlDoc()
  {
    $doc =& $this->doc;
    $this->xml->startElement('sphinx:document');
    $this->xml->writeAttribute('id', $this->docID);

    $this->xml->writeElement('text', $doc['address']);
    $this->xml->writeElement('address', $doc['address']);
    $this->xml->writeElement('aoguid', $doc['aoguid']);

    $this->xml->endElement();
  }

  private function endXml()
  {
    $this->xml->endElement();
  }

}


$pipe = new Xmlpipe2();
$pipe->run();
