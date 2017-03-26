<?php
class model_morphAnalysis extends Model
{
	/** Get First Char in word
	 * @param $word
	 * @return object
	 * word - Word
	 * first - First Char
	 * case - First Char in Upper case
	 */
	public function getFirstChar($word)
	{
		$firstChar=mb_substr($word,0,1,'utf-8');
		$firstCaseChar = mb_convert_case($firstChar, MB_CASE_UPPER, "UTF-8");
		$obj = (object) array('word' => $word, 'first' => $firstChar, 'case' => $firstCaseChar);
		return $obj;
	}

	/** Load Page in variable
	 * @param $url
	 * @return variable = HTML page
	 */
	public function loadPage($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // что бы локально открывалось
		$resultPage = curl_exec($ch);
		curl_close($ch);
		return $resultPage;
	}

	public function getMorpheme($data,$pattern,$typeSite) {
		preg_match_all($pattern, $data, $divMorpheme);
		switch ($typeSite)
		{
			case "site1data":
				$getObj = (object) array('morpheme' => $divMorpheme[1], 'part' => $divMorpheme[2]);
				break;
			case "site2url":
				$getObj = (object) array('word' => $divMorpheme[1], 'site' => $divMorpheme[3]);
				break;
			case "site2data":
				$getObj = (object) array('morpheme' => $divMorpheme[1], 'morphemeUL' => $divMorpheme[2]);
				break;
			case "morpheme":
				unset($divMorpheme[1][count($divMorpheme[1]) - 1]);
				unset($divMorpheme[3][count($divMorpheme[3]) - 1]);
				$getObj = (object) array('type' => $divMorpheme[1], 'text' => $divMorpheme[3]);
				break;
		}
		return $getObj;		
	}
	
	#region site2
	public function getURLFromMorpheme ($site,$word) {
		$word=urlencode($word);
		$url=$site."?q=".$word."&s=sostav";
		return $url;
	}

	public function resMorpheme($obj) {
		$type =array("prefix"=>"приставка","root"=>"корень","vowel"=>"соединительная гласная","suffix"=>"суффикс","ending"=>"окончание","basis"=>"Основа слова");
		$arrayobj = new stdObject();
		foreach ($type as $ktype=>$t1) {
			$t1 = iconv("UTF-8", "Windows-1251", $t1);
			$key = array_keys($obj->type, $t1);
			if (count($key)>0){
				$txt="";
				foreach ($key as $k) {
					$txt .= $obj->text[$k].";";
				}
				$txt=substr($txt, 0, -1);
				$arrayobj->$ktype = $txt;
			} else {
				$txt=NULL;
				$arrayobj->$ktype = $txt;
			}
		}
		return $arrayobj;
	}

	/** Insert morpheme in table
	 * @param $word - word
	 * @param $obj - object morpheme
	 * @return string - result
	 */
	public function saveInTable($word,$obj,$level=1)
	{
		$DBH = $this->connectPDO();
		$word = strtoupper($word);
		$obj->word = $word;
		$iWord = iconv("Windows-1251", "UTF-8", $obj->word);
		$iWord = strtoupper($iWord);
		$iPrefix = iconv("Windows-1251", "UTF-8", $obj->prefix);
		$iRoot = iconv("Windows-1251", "UTF-8", $obj->root);
		$iVowel = iconv("Windows-1251", "UTF-8", $obj->vowel);
		$iSuffix = iconv("Windows-1251", "UTF-8", $obj->suffix);
		$iEnding = iconv("Windows-1251", "UTF-8", $obj->ending);
		$iBasis = iconv("Windows-1251", "UTF-8", $obj->basis);
		$column = $DBH->query("SELECT  id,count(*) count FROM " . DB . "." . tableMorpheme . " WHERE word='$iWord'");
		$res=$column->fetchAll(PDO::FETCH_ASSOC);
		$countRec = $res[0][count]; //$column->fetchColumn();
		$lastIdRec = $res[0][id];
		if ($countRec < 1) { // Normal state =1
			$STH = $DBH->prepare("INSERT INTO " . DB . "." . tableMorpheme . " (word,prefix,root,vowel,suffix,ending,basis,ismorpeme) 
			values ('$iWord', '$iPrefix', '$iRoot', '$iVowel', '$iSuffix', '$iEnding', '$iBasis',1)");
			$STH->execute();
			$id = $DBH->lastInsertId();
			$STH = $DBH->prepare("INSERT INTO " . DB . "." . tableSearchReestr . " (morphemeId,levelReestr)	values ('$id', '$level')");
			$STH->execute();
			$id = $DBH->lastInsertId();
			$result = "Insert is success. Last ID: ".$id;
			if (!isset($obj->root)) {
				$STH = $DBH->prepare("UPDATE " . DB . "." . tableMorpheme . " SET ismorpeme = 0 WHERE id = $id");
				$STH->execute();
				$result .= " !! No morpheme";
			}
		} else {
			$result = "Word is present in the table";
			$STH = $DBH->prepare("INSERT INTO " . DB . "." . tableSearchReestr . " (morphemeId,levelReestr)	values ('$lastIdRec', '$level')");
			$STH->execute();
			$id = $DBH->lastInsertId();
		}
		$STH = null;
		$DBH = null;
		return $result;
	}


	
	#endregion

}

class miReestr {
	public $id;
	public $miName;
}

class model_morphAnalysisTest extends model_morphAnalysis {
	
	public function getMiReestrMain() {
		$DB=$this->connectPDO();
		$sth=$DB->prepare("SELECT id,miName FROM mi_reestr_main");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_CLASS, "miReestr");
		$results = array();
		foreach ($result as $res) {
			$resParseString = $this->parseString(str_replace("/"," ",$res->miName));
			$results = array_merge($results, $resParseString);
		}
//		print_r($results);
		// (.*)( |/)(.*) isU
		return $results;
	}

	public function parseString($string) {
		$string=mb_convert_case($string, MB_CASE_LOWER, "UTF-8");
		$arr=explode(" ",$string);
		return $arr;
	}

	public function delSpecChar($word) {
		$word = str_replace("(", "", $word);
		$word = str_replace(")", "", $word);
		$word = str_replace(".", "", $word);
		return $word;
	}
}