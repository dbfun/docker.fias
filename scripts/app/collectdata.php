<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';

class Collectdata {

  public function __construct()
  {
    // Файл с адресами
    $srcFile = Fs\File::locateOne('/src/*ADDROBJ*.XML');
    $this->reader = new Xml\Reader($srcFile);
    $this->pdo = new \PDO("sqlite:{$_ENV['SQLITE_DB']}", null, null, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
  }

  public function run()
  {
    $this->initSchema();
    $this->putDocs();
  }

  private function putDocs()
  {
    $this->docID = 0;

    $sql = 'INSERT INTO fias(aoguid, parentguid, aolevel, postalcode, code, okato, oktmo, formalname, shortname)
      VALUES(:aoguid, :parentguid, :aolevel, :postalcode, :code, :okato, :oktmo, :formalname, :shortname)
      ON CONFLICT (aoguid) DO UPDATE SET parentguid = :parentguid, aolevel = :aolevel,
      postalcode = :postalcode, code = :code, okato = :okato, oktmo = :oktmo,
      formalname = :formalname, shortname = :shortname
      ';
    $this->stmt = $this->pdo->prepare($sql);

    $this->pdo->beginTransaction();
    foreach ($this->reader->fetch() as $this->doc) {
      $this->docID++;
      $this->putDoc();
      // if($this->docID >= 10) break; // TEST CASE
      if($this->docID % 1000 === 0) echo $this->docID . PHP_EOL;
    }
    $this->pdo->commit();

  }

  private function putDoc()
  {
    $stmt =& $this->stmt;
    $stmt->bindValue(':aoguid', $this->doc['aoguid']);
    $stmt->bindValue(':parentguid', $this->doc['parentguid']);
    $stmt->bindValue(':aolevel', $this->doc['aolevel']);
    $stmt->bindValue(':postalcode', $this->doc['postalcode']);
    $stmt->bindValue(':code', $this->doc['code']);
    $stmt->bindValue(':okato', $this->doc['okato']);
    $stmt->bindValue(':oktmo', $this->doc['oktmo']);
    $stmt->bindValue(':formalname', $this->doc['formalname']);
    $stmt->bindValue(':shortname', $this->doc['shortname']);

    $stmt->execute();

    return $this->pdo->lastInsertId();
  }

  private function initSchema()
  {
    $query = 'CREATE TABLE IF NOT EXISTS fias (
                    aoguid CHAR(36) PRIMARY KEY,
                    parentguid CHAR(36),
                    aolevel INTEGER NOT NULL,
                    postalcode CHAR(6),
                    code VARCHAR(32),
                    okato VARCHAR(16),
                    oktmo VARCHAR(16),
                    formalname VARCHAR(255) NOT NULL,
                    shortname VARCHAR(255) NOT NULL
              )';
    $this->pdo->exec($query);
  }


}

$c = new Collectdata;
$c->run();
