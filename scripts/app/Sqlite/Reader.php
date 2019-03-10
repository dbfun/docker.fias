<?php

namespace App\Sqlite;

class Reader {

  public function __construct($dsn)
  {
    $this->pdo = new \PDO($dsn);
  }

  public function fetch()
  {
    $query = '
    SELECT
    f_0.formalname AS f_0_formalname, f_0.shortname AS f_0_shortname, f_0.aolevel AS f_0_aolevel,
    f_1.formalname AS f_1_formalname, f_1.shortname AS f_1_shortname, f_1.aolevel AS f_1_aolevel,
    f_2.formalname AS f_2_formalname, f_2.shortname AS f_2_shortname, f_2.aolevel AS f_2_aolevel,
    f_3.formalname AS f_3_formalname, f_3.shortname AS f_3_shortname, f_3.aolevel AS f_3_aolevel,
    f_4.formalname AS f_4_formalname, f_4.shortname AS f_4_shortname, f_4.aolevel AS f_4_aolevel,
    f_5.formalname AS f_5_formalname, f_5.shortname AS f_5_shortname, f_5.aolevel AS f_5_aolevel,

    f_0.aoguid AS aoguid

    FROM fias AS f_0
         JOIN fias AS f_1 ON (f_1.aoguid = f_0.parentguid)
    LEFT JOIN fias AS f_2 ON (f_2.aoguid = f_1.parentguid)
    LEFT JOIN fias AS f_3 ON (f_3.aoguid = f_2.parentguid)
    LEFT JOIN fias AS f_4 ON (f_4.aoguid = f_3.parentguid)
    LEFT JOIN fias AS f_5 ON (f_5.aoguid = f_4.parentguid)
    WHERE f_0.aolevel = 7
    ';
    $stmt = $this->pdo->query($query);
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
      $address = [];
      for($i = 5; $i >= 0; $i--) {
        if(!$row["f_{$i}_formalname"]) continue;
        $address[] = $row["f_{$i}_shortname"] . ' ' . $row["f_{$i}_formalname"];
      }
      $address = trim(implode(', ', $address));
      yield [
        'aoguid' => $row['aoguid'],
        'address' => $address
      ];
    }
  }

}
