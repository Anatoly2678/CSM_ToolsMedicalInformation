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

    /** Get Count by Group
     *
     * SELECT mrsg.group1_word, COUNT(*) counterRecord FROM mi_reestr_section mrs
    INNER JOIN mi_reestr_subgroup mrsg
    ON mrs.id=mrsg.mi_reestr_id
    GROUP BY mrsg.group1_word
     */

}