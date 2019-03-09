# Адреса ФИАС в докер контейнере



# Ссылки


## ФИАС

* [XML](https://fias.nalog.ru/Updates.aspx)
* [Описание](https://fias.nalog.ru/Docs/%D0%A1%D0%B2%D0%B5%D0%B4%D0%B5%D0%BD%D0%B8%D1%8F%20%D0%BE%20%D1%81%D0%BE%D1%81%D1%82%D0%B0%D0%B2%D0%B5%20%D0%B8%D0%BD%D1%84%D0%BE%D1%80%D0%BC%D0%B0%D1%86%D0%B8%D0%B8%20%D0%A4%D0%98%D0%90%D0%A1.doc)
* [Уровни aolevel](https://dadata.userecho.com/knowledge-bases/4/articles/1059-urovni-fias-i-urovni-adresa-dadatyi)
* [wiki](http://wiki.gis-lab.info/w/%D0%A4%D0%98%D0%90%D0%A1)

## Прочее

* Подсказки Дадаты: [статья на Хабр](https://habr.com/ru/company/hflabs/blog/349872/), [страница](https://dadata.ru/suggestions/#address)

# Сниппеты

```sh
indexer --rotate --all
/usr/bin/searchd
mysql --port=9306 --host=127.0.0.1
```

```sql
SELECT * FROM fias_main WHERE MATCH('толст*');
SELECT * FROM fias_main WHERE MATCH('*хина*');
CALL SUGGEST('толстго', 'fias_main');
```
