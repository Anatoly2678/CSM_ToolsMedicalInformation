<?php

class Model_viewreestr extends Model {
    public function get_data($where=null) {
        $this->connect();
        $query="SELECT DISTINCT col1, col2, col3, col4, col5, col6, col7, col8, col9, col10, col11, col12, col13, col14, col15, col16, col17, data_record FROM reestr";        
       if ($result =parent::$mysqliPublic->query($query, MYSQLI_USE_RESULT)) {
            while($row = $result->fetch_array(MYSQL_ASSOC)) {
                $myArray[] = $row;	
            }
            $myArray = json_encode($myArray);
            return ($myArray);
	   }
    }
}
?>
