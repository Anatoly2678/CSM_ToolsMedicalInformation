-- получить все страны, отличные от России

SELECT DISTINCT col10 FROM csm.reestr_distinct
  WHERE (col10 NOT LIKE '%Россия%') AND (col10 NOT LIKE '%Моск%') AND (col10 NOT LIKE '%г.%') AND (col10 NOT LIKE '%ул.%') AND (col10 NOT LIKE '%р-н%') AND (col10 NOT LIKE '%п.%')
   AND (col10 NOT LIKE '%Санкт-Петербург%')

   /*
[17:20:40] Станислав С: Надо то не так:
[17:20:49] Станислав С: 1. Берете 50 МИ с кодами РЗН.
[17:21:20] Станислав С: 2. Ваш алгоритм сам присваивает им свои коды по логике: совпадение названия кода (любого) с названием МИ.
[17:21:38] Станислав С: 3. Сравниваем для этих МИ коды РЗН и присвоенные нашим алгоритмом.
   */

SELECT rd1.*, mrs.col1, mrs.col2 GrupFull, mrs.col3 GroupName from
(SELECT rd.col1 URNZ, rd.col2 RegNoMI, rd.col5 MI, rd.col15 NomenklCod FROM reestr_distinct rd
  WHERE rd.col15>1 ORDER BY URNZ LIMIT 1000) rd1
  LEFT JOIN mi_reestr_section mrs
  ON rd1.MI LIKE CONCAT('%',mrs.col3,'%')   