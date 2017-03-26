<?php
class Model_handbooks extends Model {
    public function getWords() {
        $this->connect();
        $responce = $this->Words();
        echo json_encode($responce);
        $this->close();
    }

    public function getEndOf() {
        $this->connect();
        $responce = $this->EndOf();
        echo json_encode($responce);
        $this->close();
    }

    public function getStopWords() {
        $this->connect();
        $responce = $this->StopWords();
        echo json_encode($responce);
        $this->close();
    }

    public function getMIUnique() {
        $this->connect();
        $query = "SELECT id, word, prefix, root, vowel, suffix, ending, basis, isExclude,customRoot FROM morpheme ORDER BY word";
        $responce=$this->createResult($query);
        echo json_encode($responce);
        $this->close();
    }

    public function updateMIUnique() {
        $this->connect();
        $id=$_POST[id];
        $word=$_POST[word];
        $prefix=$_POST[prefix];
        $root=$_POST[root];
        $vowel=$_POST[vowel];
        $suffix=$_POST[suffix];
        $ending=$_POST[ending];
        $basis=$_POST[basis];
        $customRoot=$_POST[customRoot];
        $isExclude=$_POST[isExclude];
        if ($customRoot == "" OR $customRoot == " ")
        {$customRoot = "customRoot = NULL";} else {$customRoot="customRoot='".mb_strtoupper($customRoot, 'UTF-8')."'";}
        $query = "UPDATE morpheme SET word = '$word', prefix = '$prefix',root = '$root',vowel = '$vowel',suffix = '$suffix',
        ending = '$ending' ,basis = '$basis' ,isExclude=$isExclude ,$customRoot WHERE id = $id";
//        print_r($query);
        $responce=$this->query_data($query);
//        echo json_encode($responce);
        $this->close();
    }

    public function getSynonyms() {
        $this->connect();
        $responce = $this->Synonyms();
        // print_r($responce);
        echo json_encode($responce);
        $this->close();
    }

    /**
     * SELECT w.word,GROUP_CONCAT(w1.word) FROM synonyms s
    INNER JOIN words w
    ON s.w_id=w.id
    INNER JOIN words w1
    ON s.s_id=w1.id
    GROUP BY s.w_id
    ORDER BY word
     *
     */
    private function createResultSQL($sql) {
        $responce=$this->createResult($sql);
        return $responce;
    }

    private function EndOf() {
        $query = "SELECT id, endOfWord FROM end_of ORDER BY endOfWord";
        $responce=$this->createResult($query);
        return $responce;
    }

    private function StopWords() {
        $query = "SELECT id, stopWord FROM stop_words ORDER BY stopWord";
        $responce=$this->createResult($query);
        return $responce;
    }

    private function Words() {
        $query = "SELECT id, word FROM words ORDER BY word";
        $responce=$this->createResult($query);
        return $responce;
    }

    private function Synonyms() {
        $query="SELECT DISTINCT w.word, w1.word synonym FROM morpheme w INNER JOIN synonyms s ON w.id=s.w_id INNER JOIN morpheme w1 ON s.s_id=w1.id LIMIT 500"; // LIMIT 10000
//        print_r($query);
        $responce=$this->createResult($query);
        return $responce;
    }

    private function createResult($query) {
        if ($result = $this->get_data($query)) {
            $rows = array();
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $responce[] = $row;
                $i++;
            }
        }
        return $responce;
    }
}