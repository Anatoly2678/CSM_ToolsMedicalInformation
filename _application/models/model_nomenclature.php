<?php
/**
 * Created by PhpStorm.
 * User: Анатоли
 * Date: 06.09.2016
 * Time: 20:46
 */
class model_nomenclature extends Search {
    function __construct() {
        $this->connect();
    }
    function __destruct() {
        $this->close();
    }

    public function updateKeywordsOne() {
        $query="SELECT group1_word, group1_index, COUNT(*) keywords1 FROM mi_reestr_subgroup GROUP BY group1_word;";
        if ($result = $this->get_data($query)) {
            while($row = $result->fetch_assoc()) {
                $key_word=$row[group1_word];
                print_r($key_word."<br>");
                $this->getNomenclatureByKeywords($key_word);
            }
        }
    }

    private  function getNomenclatureByKeywords($keywords) {
        $sql="INSERT INTO mi_reestr_subgroup (mi_reestr_id,group1_word,group1_index) 
        SELECT mrs.id,'$keywords',1 FROM mi_reestr_section mrs 
        INNER JOIN mi_reestr_subgroup mrsg ON mrs.col3 LIKE CONCAT('%$keywords%') WHERE mrsg.group1_index<>1 OR mrsg.group1_index IS NULL
        ON DUPLICATE KEY UPDATE group1_index=1";
        var_dump($sql."<br>");
        $this->get_data($sql);
    }

    public function findSubSection() {
        $query="SELECT id, `code subrazdel`, `code razdel`, razdel, subrazdel, `over razdel`, `over subrazdel`, `find subrazdel` FROM TMITable"; // WHERE `find subrazdel` IS NULL
        if ($result = $this->get_data($query)) {
            while($row = $result->fetch_assoc()) {
                $key_word=explode(";",str_replace(" ","",$row["code subrazdel"]));
                $key_word2=explode(";",str_replace(" ","",$row["subrazdel"]));
                $find=0;
                foreach ($key_word as $word) {
                    if (in_array($word, $key_word2)) {
                        $find=1;
                    }
                }
                    $sql="UPDATE TMITable SET `find subrazdel` = $find WHERE id = $row[id]";
                    $this->get_data($sql);

                $key1_word=explode(";",str_replace(" ","",$row["code razdel"]));
                $key1_word2=explode(";",str_replace(" ","",$row["razdel"]));
                $find1=0;
                foreach ($key1_word as $word1) {
                    if (in_array($word1, $key1_word2)) {
                        $find1=1;
                    }
                }
                $sql="UPDATE TMITable SET `find razdel` = $find1 WHERE id = $row[id]";
                $this->get_data($sql);

                $key2_word=$row["code razdel"];
                $key2_word2=$row["razdel"];
                $find2=0;
                    if ($key2_word == $key2_word2) { $find2=1; }
                $sql="UPDATE TMITable SET `over razdel` = $find2 WHERE id = $row[id]";
                $this->get_data($sql);

                $key2_word=$row["code subrazdel"];
                $key2_word2=$row["subrazdel"];
                $find2=0;
                if ($key2_word == $key2_word2) { $find2=1; }
                $sql="UPDATE TMITable SET `over subrazdel` = $find2 WHERE id = $row[id]";
                $this->get_data($sql);


                print_r($key_word2);
                print_r($key_word);
                echo "<hr>";
                print_r($key1_word2);
                print_r($key1_word);
                print_r("<br><br>");
//                $this->getNomenclatureByKeywords($key_word);
            }
        }
    }

    /** Get Count by Group
     *
     * SELECT mrsg.group1_word, COUNT(*) counterRecord FROM mi_reestr_section mrs
    INNER JOIN mi_reestr_subgroup mrsg
    ON mrs.id=mrsg.mi_reestr_id
    GROUP BY mrsg.group1_word
     */

}