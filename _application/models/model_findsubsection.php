<?php
//error_reporting(E_ALL | E_STRICT);
require_once(dirname(__FILE__).'/../phpmorphy/src/common.php'); // dirname(__FILE__)
class MITable {
	public $word;
	public $transcription;
	public $metaphone;
	public $soundex;
	public $sectionWord;
	public $sectionCountWord;
	public $subSectionWord;
	public $subSectionCountWord;
	public $miWordURNZ;
	public $miWordNomenclature;
	public $miWordSection;
	public $miWordSubsection;
	public $miWordCount;
	public $miMetaphoneURNZ;
	public $miMetaphoneNomenclature;
	public $miMetaphoneSection;
	public $miMetaphoneSubsection;
	public $miMetaphoneCount;
	public $matchURNZ;
	public $matchURNZCount;
	public $differenceURNZ;
	public $differenceURNZCount;

	public $matchSectionInWord;
	public $matchSectionInWordCount;
	public $matchSectionInWordPercentYes;
	public $matchSectionInWordPercentNo;

	public $matchSubsectionInWord;
	public $matchSubsectionInWordCount;
	public $matchSubsectionInWordPercentYes;
	public $matchSubsectionInWordPercentNo;

	public $matchSectionInMetaphone;
	public $matchSectionInMetaphoneCount;
	public $matchSectionInMetaphonePercentYes;
	public $matchSectionInMetaphonePercentNo;

	public $matchSubsectionInMetaphone;
	public $matchSubsectionInMetaphoneCount;
	public $matchSubsectionInMetaphonePercentYes;
	public $matchSubsectionInMetaphonePercentNo;

	public $findCountMIBySecton;
	public $findCountMetaphoneBySecton;
}

#region find1
class model_findSubSection extends Model
{
	public static $word;
	public static $transcription;
	public static $metaphone;
	public static $metaphoneRu;
	public static $soundex;

	public static $responce;

	public function __construct() {
		self::$responce = new MITable();
//		$cRU=new customRUMetaphon();
	}

	public function getResponse() {
		return self::$responce;
	}

	public function getWord($word) {
		$cRU=new customRUMetaphon();
		$word = mb_strtoupper($word, 'UTF-8');
		self::$word=$word;
		self::$responce->word=$word;
		$dbh=$this->connectPDO();
		$w1=explode(";",$word);
		print_r($w1);
		var_dump(count($w1));
		if (count($w1)==1) {
			$sql = "SELECT id, root word FROM morpheme WHERE upper(word) LIKE '%" . $word . "%' and isExclude is null";
		}
		if (count($w1)==2) {
			$sql = "SELECT id, root word FROM morpheme WHERE upper(word) LIKE '%" . $w1[0] . "%' and upper(word) LIKE '%" . $w1[1] . "%' and isExclude is null";
		}
		print_r($sql);
		$res = $this->getRecinKeyValue($sql);
		if (empty ($res)) { die ('<hr>слово '.$word.' не найдено!');}
		print_r($res);
		echo "<br>";
		echo "Транскрипция:<br>";
		$trans=$this->translitIt($word);
		self::$transcription=$trans;
		self::$responce->transcription=$trans;
		print_r($trans);
		echo "<br>";
		echo "Метафон:<br>";
		$mm=$this->metaphoneWord(0,$trans,1);
		$mmW=trim($mm[meta]);
		self::$metaphone=$mmW;
		self::$responce->metaphone=$mmW;
		print_r($mmW);
		echo "<br>";

		echo "НАШ Метафон:<br>";
		$rMetaV=$cRU->getFirstVowel($word);
		$rMetaC=$cRU->getConsonant($word);
		self::$metaphoneRu=$rMetaV.$rMetaC;
		echo self::$metaphoneRu;
//
//		var_dump($rMetaV);
//		echo "<br>";
//		var_dump($rMetaC);

//		$mm=$this->metaphoneWord(0,$trans,1);
//		$mmW=trim($mm[meta]);
//		self::$metaphone=$mmW;
//		self::$responce->metaphone=$mmW;
//		print_r($mmW);
		echo "<br>";

		echo "ключ soundex:<br>";
		self::$soundex=trim($mm[sound]);
		self::$responce->soundex=trim($mm[sound]);
		print_r($mm[sound]);
		echo "<hr>";
		$dbh = null;
		return $word;
	}
	#region View on display
	public function getSection($word) {
		echo "<b><u>Разделы:</u></b><br>";
		$word = mb_strtoupper($word, 'UTF-8');
		$dbh=$this->connectPDO();
		$sql="SELECT id, miName FROM mi_reestr_main WHERE (UPPER (miName) LIKE '%".$word."%' OR UPPER (miName) LIKE '%".self::$transcription."%')";
		$res = $this->getAllRecinArray($sql);
		if (empty ($res)) { echo ('Разделы не найдены!<br>');}
		$selectReturn=$this->selectArraySection($res,$word);
		print_r($selectReturn);
		echo "<br>";
		$i=0;
		foreach ($res as $key=> $val)
		{
			$ret[]=$val[id];
			$i++;
		}
		$dbh = null;
		echo "Соответствует разделам = ";
		print_r(implode(";",$ret));
		self::$responce->sectionWord=implode(";",$ret);
		echo "<br>";
		echo "<b><i>Всего = ";
		print_r($i);
		self::$responce->sectionCountWord=$i;
		echo "</i></b>";
		echo "<hr>";
		$responce[section]=$ret;
		$responce[count]=$i;
		return $responce;
	}

	private function selectArraySection($arr,$searchWord) {
		foreach ($arr as $value) {
			$v[id]=$value[id];
			$newstr = preg_replace("/(".$searchWord.")/iu",'<b>'.$searchWord.'</b>',$value[miName]);
			$v[miName]=$newstr; //str_replace($searchWord,'<b>'.$searchWord.'</b>',$value[miName]);
			$retArr[] = $v;
		}
		return ($retArr);
	}

	public function getSubSection($word) {
		echo "<b><u>Подразделы:</u></b><br>";
		$word = mb_strtoupper($word, 'UTF-8');
		$dbh=$this->connectPDO();
		$sql="SELECT col2, col2_section, col2_subsection FROM mi_reestr_section WHERE 
		(UPPER (col2) LIKE '%".$word."%' OR UPPER (col2) LIKE '%".self::$transcription."%') GROUP BY col2";
		$res = $this->getAllRecinArray($sql);
		if (empty ($res)) { echo ('Подразделы не найдены!<br>');}
		$selectReturn=$this->selectArraySubSection($res,$word);
		print_r($selectReturn);
		echo "<br>";
		$i=0;
		foreach ($res as $key=> $val)
		{
			$ret[]=$val[col2_section].".".$val[col2_subsection]; //col2_section, col2_subsection
			$i++;
		}
		$dbh = null;
		echo "Соответствует подразделам = ";
		print_r(implode(";",$ret));
		self::$responce->subSectionWord=implode(";",$ret);
		echo "<br>";
		echo "<b><i>Всего = ";
		print_r($i);
		self::$responce->subSectionCountWord=$i;
		echo "</i></b>";
		echo "<hr>";
		$responce[subSection]=$ret;
		$responce[count]=$i;
		return $responce;
	}

	private function selectArraySubSection($arr,$searchWord) {
		foreach ($arr as $value) {
			$newstr = preg_replace("/".$searchWord."/iu",'<b>'.$searchWord.'</b>',$value[col2], -1, $count);
			$v[col2]=$newstr; //str_replace($searchWord,'<b>'.$searchWord.'</b>',$value[miName]);
			$v[col2_section]=$value[col2_section];
			$v[col2_subsection]=$value[col2_subsection];
			$retArr[] = $v;
		}
		return ($retArr);
	}

	public function getMIWord($word) {
		echo "<b><u>МИ (по словам):</u></b><br>";
		$word = mb_strtoupper($word, 'UTF-8');
		$dbh=$this->connectPDO();
		$sql="SELECT col1, col5, col15 FROM reestr_distinct WHERE (UPPER(col5) LIKE UPPER('%".$word."%') OR UPPER(col5) LIKE UPPER('%".self::$transcription."%')) 
		AND col15>0 GROUP BY col1, col5, col15";
		$res = $this->getAllRecinArray($sql);
		if (empty ($res)) { echo ('слово не найдено!<br>');}
		$selectReturn=$this->selectArrayMIWord($res,$word);
		$i=0;
		foreach ($res as $key=> $val)
		{
			$ret[]=$val[col15];
			$retCol1[]=$val[col1];
			$i++;
		}
		$dbh = null;
		echo "УНРЗ = ";
		print_r(implode("; ",$retCol1));
		echo "<br>";
		echo "Соответствует МИ в соотв с номенклатурой = ";
		print_r(implode("; ",$ret));
		$ss=$this->getSubSectionByFindMI($ret);
		$sCount=$this->getCountMIByFindSection(self::$responce->sectionWord,'Word');
		echo "<h3>Найдено МИ с разделом: ".self::$responce->sectionWord;
		echo " Записей МИ = ".$sCount." </h3>";
		self::$responce->findCountMIBySecton=$sCount;
		$responce[nomenclature]=$ret;
		$responce[urnz]=$retCol1;
		$responce[count]=$i;
		$responce[section]=$ss[section];
		$responce[subsection]=$ss[subSection];
		self::$responce->miWordCount=$i;
		self::$responce->miWordNomenclature=implode("; ",$ret);
		self::$responce->miWordSection=implode("; ",$responce[section]);
		self::$responce->miWordSubsection=implode("; ",$responce[subsection]);
		self::$responce->miWordURNZ=implode("; ",$retCol1);
		echo "<br>";
		echo "Соответствует разделам = ";
		print_r(implode("; ",$responce[section]));
		echo "<br>";
		echo "Соответствует подразделам = ";
		print_r(implode("; ",$responce[subsection]));
		echo "<br>";
		echo "<b><i>Всего = ";
		print_r($i);
		echo "</i></b>";
		echo "<hr>";
		return $responce;
	}

	private function selectArrayMIWord($arr,$searchWord) {
		foreach ($arr as $value) {
			$v[col1]=$value[col1];
			$newstr = preg_replace("/".$searchWord."/iu",'<b>'.$searchWord.'</b>',$value[col5], -1, $count);
			$v[col5]=$newstr; //str_replace($searchWord,'<b>'.$searchWord.'</b>',$value[miName]);
			$v[col15]=$value[col15];
			$retArr[] = $v;
		}
		return ($retArr);
	}

	public function getMIMEtaphone($word) {
		echo "<b><u>МИ (метафон):</u></b><br>";
		$word = mb_strtoupper($word, 'UTF-8');
		$dbh=$this->connectPDO();
		$sql="SELECT fs.col1,fs.col5Morpheme, fs.col5Soudex, rd.col15 FROM findSubSection fs
  		INNER JOIN reestr_distinct rd ON fs.col1 = rd.col1 WHERE 
  		fs.col5Morpheme LIKE ('%".self::$metaphone."%')
  		AND fs.col5Soudex LIKE '%".self::$soundex."%' 
   		GROUP BY fs.col1" ; // AND fs.col5Soudex LIKE '%".self::$soundex."%'
		$res = $this->getAllRecinArray($sql);
		if (empty ($res)) { echo ('слово не найдено!<br>');}
		$i=0;
		foreach ($res as $key=> $val)
		{
			$retM=$val[col5Morpheme];
			$res=$this->levenshteinWord($retM);
			if ($res) {
				$ret[] = $val[col15];
				$retCol1[] = $val[col1];
				$i++;
			}
		}
		$dbh = null;
		echo "УНРЗ = ";
		print_r(implode("; ",$retCol1));
		echo "<br>";
		echo "Соответствует МИ в соотв с номенклатурой = ";
		print_r(implode("; ",$ret));
		$ss=$this->getSubSectionByFindMI($ret);
		$sCount=$this->getCountMIByFindSection(self::$responce->sectionWord,'metaEn');
		$sCountRu=$this->getCountMIByFindSection(self::$responce->sectionWord,'metaRu');
		echo "<h3>Найдено МИ с разделом: ".self::$responce->sectionWord;
		echo " Записей МИ (метафон) = ".$sCount." </h3>";
		echo "<h4> Записей МИ (метафон РУ) = ".$sCountRu." </h4>";
		self::$responce->findCountMetaphoneBySecton=$sCount;
		$responce[nomenclature]=$ret;
		$responce[urnz]=$retCol1;
		$responce[count]=$i;
		$responce[section]=$ss[section];
		$responce[subsection]=$ss[subSection];

		self::$responce->miMetaphoneCount=$i;
		self::$responce->miMetaphoneNomenclature=implode("; ",$ret);
		self::$responce->miMetaphoneSection=implode("; ",$responce[section]);
		self::$responce->miMetaphoneSubsection=implode("; ",$responce[subsection]);
		self::$responce->miMetaphoneURNZ=implode("; ",$retCol1);
		echo "<br>";
		echo "Соответствует разделам = ";
		print_r(implode("; ",$responce[section]));
		echo "<br>";
		echo "Соответствует подразделам = ";
		print_r(implode("; ",$responce[subsection]));
		echo "<br>";
		echo "<b><i>Всего = ";
		print_r($i);
		echo "</i></b>";
		echo "<hr>";
		return $responce;
	}

	private function levenshteinWord ($words) {
		$wordsArr=explode(" ",$words);
		$res=false;
		foreach ($wordsArr as $item) {
			$curLen=strlen($item);
			$sLen=strlen(self::$metaphone);
			$levLen=levenshtein($item,self::$metaphone);
//			echo ($item."(".ceil($curLen/2).") ++ ".$sLen." -- ". $levLen."<br>");
			if ($levLen <=ceil($curLen/2)) {$lev[] = $levLen; $res=true;}
		}
//		echo "<hr>";
//		var_dump($lev);

		return $res;
	}

	public function findURNZ ($arr1,$arr2) {
		echo "<b><u>Совпадения УРНЗ слова + метафон:</u></b><br>";
		$result = array_intersect($arr1, $arr2);
		$lenArr1=count($arr1);
		$lenArr2=count($arr2);
		if ($lenArr1 >= $lenArr2) {
			$resultdiff = array_diff($arr1, $arr2);
		} else {
			$resultdiff = array_diff($arr2, $arr1);
		}
		print_r(implode("; ",$result));
		echo "<br>";
		echo "<b><i>Всего = ".count($result);
		self::$responce->matchURNZ=implode("; ",$result);
		self::$responce->matchURNZCount=count($result);
		echo "</i></b>";
		echo "<hr>";
		echo "<b><u>Расхождения УРНЗ слова + метафон:</u></b><br>";
		print_r(implode("; ",$resultdiff));
		echo "<br>";
		echo "<b><i>Всего = ".count($resultdiff);
		self::$responce->differenceURNZ=implode("; ",$resultdiff);
		self::$responce->differenceURNZCount=count($resultdiff);
		echo "</i></b>";
		echo "<hr>";
	}

	public function findSection ($arr1,$arr2)	{
		$result = array_intersect($arr1, $arr2);
		$percentVerno=count($result)*100/count($arr2);
		if (count($result)>0) {
			print_r(implode("; ",$result));
			echo "<br>";
		}
		echo "<b><i>Всего = ".count($result)."</i></b><br>";
		echo "<b><i>Верно в % = ".$percentVerno."</i></b><br>";
		echo "<b><i>Не верно в % = ".(100-$percentVerno)."</i></b><br>";
		echo "<hr>";
		$ret[word]=implode("; ",$result);
		$ret[all]=count($result);
		$ret[matchPercent]=$percentVerno;
		$ret[diffPercent]=(100-$percentVerno);
		return $ret;
	}

	private function getSubSectionByFindMI($nomenclatureArr) {
		$dbh=$this->connectPDO();
		if (is_null($nomenclatureArr)) {
			$nomencature=0;
		} else {
			$nomencature = implode(", ", $nomenclatureArr);
		}
		$sql="SELECT id, col1, col2, col2_section, concat (col2_section,\".\", col2_subsection) subSection FROM mi_reestr_section 
		WHERE col1 IN (".$nomencature.") ORDER BY col1";
		$res = $this->getAllRecinArray($sql);
		foreach ($res as $result) {
			$section[]=$result[col2_section];
			$subSection[]=$result[subSection];
		}
		$section[section]=array_unique($section);
		$section[subSection]=array_unique($subSection);
		$dbh=null;
		return $section;
	}

	private function getCountMIByFindSection($section,$metafon=false) {
		$section=str_replace(";",",",$section);
		$dbh=$this->connectPDO();
		if ($metafon == 'Word') {
			$sql = "SELECT 'cnt', COUNT(*)cnt FROM (SELECT DISTINCT rd.col1 FROM reestr_distinct rd INNER JOIN mi_reestr_section mrs ON mrs.col1=rd.col15 
		WHERE mrs.col2_section IN ($section) AND UPPER(rd.col5) LIKE upper('%" . self::$word . "%') GROUP BY rd.col1, rd.col5, rd.col15 
		ORDER BY rd.col1) a1";
			$sql="SELECT 'cnt', COUNT(*)cnt FROM (SELECT rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section FROM findSubSection ss
  			INNER JOIN reestr_distinct rd ON rd.col1=ss.col1 
  			INNER JOIN mi_reestr_section mrs ON rd.col15=mrs.col1 
  			WHERE UPPER(rd.col5)  LIKE upper('%" . self::$word . "%')
  			AND mrs.col2_section  IN ($section)) a1";
		}
		if ($metafon == 'metaEn') {
			$sql="SELECT 'cnt', COUNT(*)cnt FROM (SELECT DISTINCT rd.col1,mrs.col2_section FROM reestr_distinct rd INNER JOIN mi_reestr_section mrs 
  			ON mrs.col1=rd.col15 INNER JOIN findSubSection fss ON rd.col1=fss.col1 WHERE mrs.col2_section IN ($section) 
  			AND UPPER(fss.col5Morpheme) LIKE upper('%".self::$metaphone."%') AND fss.col5Soudex LIKE '%".self::$soundex."%' 
  			GROUP BY rd.col1, rd.col5, rd.col15 
  			ORDER BY rd.col1) a1";
			$sql="SELECT 'cnt', COUNT(*)cnt FROM (SELECT rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section FROM findSubSection ss 
			INNER JOIN reestr_distinct rd ON rd.col1=ss.col1 
			INNER JOIN mi_reestr_section mrs ON rd.col15=mrs.col1 
			WHERE ss.col5Morpheme  LIKE upper('%".self::$metaphone."%') AND mrs.col2_section  IN ($section) AND ss.col5Soudex LIKE '%".self::$soundex."%') a1 
			";
		}
		if ($metafon == 'metaRu') {
			$sql="SELECT 'cnt', COUNT(*)cnt FROM (SELECT DISTINCT rd.col1,mrs.col2_section FROM reestr_distinct rd INNER JOIN mi_reestr_section mrs 
  			ON mrs.col1=rd.col15 INNER JOIN findSubSection fss ON rd.col1=fss.col1 WHERE mrs.col2_section IN ($section) 
  			AND UPPER(fss.ruMetaphone) LIKE upper('%".self::$metaphoneRu."%')  
  			GROUP BY rd.col1, rd.col5, rd.col15 
  			ORDER BY rd.col1) a1";
			$sql="SELECT 'cnt', COUNT(*)cnt FROM (SELECT rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section FROM findSubSection ss 
			INNER JOIN reestr_distinct rd ON rd.col1=ss.col1 
			INNER JOIN mi_reestr_section mrs ON rd.col15=mrs.col1 
			WHERE ss.ruMetaphone  LIKE upper('%".self::$metaphoneRu."%') AND mrs.col2_section  IN ($section)) a1";
		}
		echo "<p>".$sql."</p>";
		$result=$this->getRecinKeyValue($sql);
		return $result[cnt];
		$dbh=null;
	}

	#endregion
	public function updateMI()
	{
		$dbh=$this->connectPDO();
		$sql="SELECT col1,col5 FROM reestr_distinct where col15>0 GROUP BY col1,col5 ";
		$res = $this->getRecinKeyValue($sql);
		$smt=$dbh->exec("DELETE FROM findSubSection");
		var_dump($smt);

		$stmt = $dbh->prepare("INSERT INTO findSubSection(col1,col5,col5Translate,col5Morpheme,col5Soudex,ruMetaphone) VALUES(:col1,:col5,:col5Translate,:col5Morpheme,:col5Soudex,:ruMetaphone)");
		$stmt->bindParam(':col1', $col1);
		$stmt->bindParam(':col5', $col5);
		$stmt->bindParam(':col5Translate', $col5Translate);
		$stmt->bindParam(':col5Morpheme', $col5Morpheme);
		$stmt->bindParam(':col5Soudex', $col5Soudex);
		$stmt->bindParam(':ruMetaphone', $ruMetaphone);

		foreach ($res as $key => $value) {
//			$value = preg_replace("/(\r\n|:|-|=|,|\/|\(|\))/ius",' ', $value);
			$value = preg_replace("/(\r\n|-|=|,|\/|\(|\))/ius",' ', $value);
//			$value = str_replace(",",' ', $value);
//			$value = str_replace("=",' ', $value);
//			$value = str_replace(":",' ', $value);
			$value=explode(":",$value);
			$value=$value[0];
			$trans=$this->translitIt($value);
			$mm=$this->metaphoneWord($key,$value,$trans);
			var_dump($mm);
			echo "<hr>";

			$col1=$mm[key];
			$col5=$value;
			$col5Translate=$mm[trans];
			$meta=preg_replace('|\s+|', ' ', $mm[meta]);
			$meta=substr($meta,1);
			$col5Morpheme=$meta; //trim($mm[meta]);
			$sound=preg_replace('|\s+|', ' ', $mm[sound]);
			$sound=substr($sound,1);
			$col5Soudex=$sound; //trim($mm[sound]);
//			echo "<h2>".$mm[rus]."</h2>";
			$rus=preg_replace('|\s+|', ' ', $mm[rus]);
			$ruMetaphone = substr($rus,1);
			var_dump($ruMetaphone);
			$stmt->execute();
		}
	}

	public function updateMorpheme() {
		$cRU1 = new customRUMetaphon();
		$dbh=$this->connectPDO();
		$sql="SELECT id,word,root,basis FROM morpheme order by word";
		$res = $this->getAllRecinArray($sql);
		$stmt = $dbh->prepare("UPDATE morpheme SET metaphone = :meta,soundex = :sound,ruMetaphone = :ru WHERE id = :id");
		$stmt->bindParam(':meta', $meta);
		$stmt->bindParam(':sound', $sound);
		$stmt->bindParam(':ru', $ru);
		$stmt->bindParam(':id', $id);

		foreach ($res as $key => $value) {
			$trans=$this->translitIt($value[root]); // basis
			$mm=$this->metaphoneWord($value[id],$trans);
			print_r($mm);
			echo "<hr>";
			$id=$value[id];
			$meta=preg_replace('|\s+|', ' ', $mm[meta]);
			$meta=substr($meta,1);
			$ll= strlen($meta);
			if ($ll>3) $meta = substr($meta, 0, -1);
			$meta=$meta; //trim($mm[meta]);
			$sound=preg_replace('|\s+|', ' ', $mm[sound]);
			$sound=substr($sound,1);
			$sound=$sound; //trim($mm[sound]);
			$ru = $ruRet=$cRU1->ruMetaphone($value[root]); //$mm[rus];
			$stmt->execute();
		}
	}

	private function metaphoneWord($key,$rus,$str,$len=0) {
		$cRU2 = new customRUMetaphon();
		$word=explode(" ",$str);
		$ruWord=explode(" ",$rus);
		$mword="";
		$sword="";
		$ru="";
		foreach ($word as $w) {
			$strRet=metaphone($w);
			if ($len>0) {
				$lenM=(strlen(metaphone($w)));
				$strRet=metaphone($w,$lenM-1);
			}
			$soundRet=soundex($w);
			$mword .=" ".$strRet;
			$sword .=" ".$soundRet;
		}
		foreach ($ruWord as $w) {
			$sr=mb_strtoupper($cRU2->getFirstVowel($w), 'UTF-8');
			$sr2=mb_strtoupper($cRU2->getConsonant($w), 'UTF-8');
			$ru .=" ".$sr.$sr2;
			$ru .=" ";
		}
		$retArr=['key'=>$key,'trans'=>$str,'meta'=>$mword, 'sound'=>$sword,'rus'=>$ru];
//		var_dump($retArr);
		return $retArr;
	}

	public function createTable()
	{
		$queryCreate = "CREATE TABLE csm.reportMIWM (
  		id int(11) NOT NULL AUTO_INCREMENT,
  		word text DEFAULT NULL,
  		transcription text DEFAULT NULL,
  		metaphone text DEFAULT NULL,
 		soundex text DEFAULT NULL,
  		sectionWord text DEFAULT NULL,
  		sectionCountWord text DEFAULT NULL,
  		subSectionWord text DEFAULT NULL,
  		subSectionCountWord text DEFAULT NULL,
  		miWordURNZ text DEFAULT NULL,
  		miWordNomenclature text DEFAULT NULL,
  		miWordSection text DEFAULT NULL,
  		miWordSubsection text DEFAULT NULL,
  		miWordCount text DEFAULT NULL,
  		miMetaphoneURNZ text DEFAULT NULL,
  		miMetaphoneNomenclature text DEFAULT NULL,
  		miMetaphoneSection text DEFAULT NULL,
  		miMetaphoneSubsection text DEFAULT NULL,
  		miMetaphoneCount text DEFAULT NULL,
  		matchURNZ text DEFAULT NULL,
  		matchURNZCount text DEFAULT NULL,
  		differenceURNZ text DEFAULT NULL,
  		differenceURNZCount text DEFAULT NULL,
  		matchSectionInWord text DEFAULT NULL,
  		matchSectionInWordCount text DEFAULT NULL,
  		matchSectionInWordPercentYes text DEFAULT NULL,
  		matchSectionInWordPercentNo text DEFAULT NULL,
  		matchSubsectionInWord text DEFAULT NULL,
  		matchSubsectionInWordCount text DEFAULT NULL,
  		matchSubsectionInWordPercentYes text DEFAULT NULL,
  		matchSubsectionInWordPercentNo text DEFAULT NULL,
  		matchSectionInMetaphone text DEFAULT NULL,
  		matchSectionInMetaphoneCount text DEFAULT NULL,
  		matchSectionInMetaphonePercentYes text DEFAULT NULL,
  		matchSectionInMetaphonePercentNo text DEFAULT NULL,
  		matchSubsectionInMetaphone text DEFAULT NULL,
  		matchSubsectionInMetaphoneCount text DEFAULT NULL,
  		matchSubsectionInMetaphonePercentYes text DEFAULT NULL,
  		matchSubsectionInMetaphonePercentNo text DEFAULT NULL,
  		PRIMARY KEY (id)
	)
	ENGINE = MYISAM
	AUTO_INCREMENT = 1
	CHARACTER SET utf8
	COLLATE utf8_general_ci;";
		echo $queryCreate;
		echo "<br>";
		$dth = $this->connectPDO();
		$r0 = $dth->exec($queryCreate) or die(print_r($dth->errorInfo(), true));
		print_r($r0);
	}

	public function readBasisWord() {
		$keyArray="root";
		$dth=$this->connectPDO();
		$sql="SELECT word, root, basis, metaphone, soundex FROM morpheme WHERE isExclude IS null ORDER BY word LIMIT 100";
		$resp=$this->getAllRecinArray($sql);
		foreach ($resp as $key=>$val) {
			$basisWord[]=$val[$keyArray];
		}
		$dth= null;
		return $basisWord;
	}

	public function saveReportInTable($arr) {
		$falFirst=$arr->word;
//		print_r($falFirst);
		$dth=$this->connectPDO();
		$insert="INSERT INTO reportMIWM (word) VALUES ('$falFirst');";
		$dth->exec($insert);
		$idLast=$dth->lastInsertId(id);
		print_r($idLast);
//		$field="";
//		$val="''";
		foreach ($arr as $fields=>$value) {
			$qUpdate="UPDATE reportMIWM SET $fields = '$value' WHERE id = $idLast";
			$dth->exec($qUpdate);
		}
	}
}
#endregion
/**
 * Class Morpheme - все типы значений из слова (корень, основа, транскрипция, метафоны, звучание)
 */
class Morpheme {
	public $word;
	public $root;
	public $basis;
	public $transcript;
	public $metaphone;
	public $soundex;
	public $metaphoneRU;
}

class model_findSubSection2 extends  Model {
	public static $morpheme;
	public static $section;
	public static $wordSearch;

//	public function __construct() {
//
//	}

	/**
	 * Подготавливаем объект по слову
	 * @param $word = Слово
	 * @return Morpheme - объект
	 */
	public function getMorpheme($word) {
		$morpheme= new Morpheme();
		$dth=$this->connectPDO();
		$sql="SELECT id, word, root, basis, metaphone, soundex, ruMetaphone FROM morpheme
  		WHERE isExclude IS null AND UPPER(word) LIKE '%$word%'";
		$result=$this->getAllRecinArray($sql);
		$morpheme->word=$result[0][word];
		$morpheme->root=$result[0][root];
		$morpheme->basis=$result[0][basis];
		$morpheme->transcript=$this->translitIt($result[0][basis]);
		$morpheme->metaphone=$result[0][metaphone];
		$morpheme->soundex=$result[0][soundex];
		$morpheme->metaphoneRU=$result[0][ruMetaphone];
		$dth=null;
		self::$morpheme=$morpheme;
		self::$wordSearch = self::$morpheme->root; // здесь меняем!!! Если надо по корню
		return self::$morpheme;
	}

	/**
	 * Кол-во совпадений в МИ
	 * $word=self::$morpheme->basis; // здесь меняем!!! Если надо по корню
	 * @return null
	 *
	 */
	public function getCountbyMI() {
		$word=self::$wordSearch; // здесь меняем!!! Если надо по корню
		$dth=$this->connectPDO();
		$sql="SELECT COUNT(*) cnt FROM (SELECT rd.col1,rd.col15, ss.ruMetaphone 
  		FROM findSubSection ss 
  		INNER JOIN reestr_distinct rd ON rd.col1=ss.col1  		 
  		WHERE UPPER(ss.col5) LIKE upper('%$word%') 
  		GROUP BY rd.col1,rd.col15, ss.ruMetaphone) a1";
		echo "<p>".$sql."</p>";
		$result=$this->getAllRecinArray($sql);
		$dth=null;
		echo "Кол-во совпадений в МИ<br>";
		return $result;
	}

	/**
	 * Кол-во, где совпали разделы в МИ
	 * @return null
	 *
	 */
	public function getNumberMatchesMI() {
		$word=self::$wordSearch; // здесь меняем!!! Если надо по корню
		$dth=$this->connectPDO();
		$sql="SELECT COUNT(*) cnt FROM (SELECT rd.col1,rd.col15, ss.ruMetaphone 
  		FROM findSubSection ss 
  		INNER JOIN reestr_distinct rd ON rd.col1=ss.col1
  		LEFT JOIN mi_reestr_section mrs ON rd.col15=mrs.col1
  		WHERE UPPER(ss.col5) LIKE upper('%$word%') AND mrs.col2_section IN (".self::$section.")
  		GROUP BY rd.col1,rd.col15, ss.ruMetaphone) a1";
		echo "<p>".$sql."</p>";
		$result=$this->getAllRecinArray($sql);
		$dth=null;
		echo "Кол-во, где совпали разделы в МИ<br>";
		return $result;
	}

	#region Not USE
	public function getCountbyWord() {
		$word=self::$morpheme->basis;
		$this->connectPDO();
		$sql="SELECT COUNT(*) cnt FROM (SELECT rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section 
  		FROM findSubSection ss 
  		INNER JOIN reestr_distinct rd ON rd.col1=ss.col1 
  		INNER JOIN mi_reestr_section mrs ON rd.col15=mrs.col1 
  		WHERE UPPER(rd.col5) LIKE upper('%$word%') 
  		GROUP BY rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section) a1";
		$result=$this->getAllRecinArray($sql);
		return $result;
	}
	#endregion

	/**
	 * Кол-во совпадений в МИ (по метафону)
	 * @return null
	 */
	public function getCountbyMetaphone() {
		$word=self::$morpheme->metaphone;
		$this->connectPDO();
		$sql="SELECT COUNT(*) cnt FROM (SELECT rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section 
  		FROM findSubSection ss 
  		INNER JOIN reestr_distinct rd ON rd.col1=ss.col1 
  		INNER JOIN mi_reestr_section mrs ON rd.col15=mrs.col1 
  		WHERE UPPER(ss.col5Morpheme) LIKE upper('%$word%') AND ss.col5Soudex LIKE '%".self::$morpheme->soundex."%'
  		GROUP BY rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section) a1"; //
		echo "<p>".$sql."</p>";
		$result=$this->getAllRecinArray($sql);
		echo "Кол-во совпадений в МИ (по метафону)<br>";
		return $result;
	}

	/**
	 * Кол-во, где совпали разделы в МИ (по метафону)
	 * @return null
	 */
	public function getNumberMatchesMetaphone() {
		$word=self::$morpheme->metaphone;
		$this->connectPDO();
		$sql="SELECT COUNT(*) cnt FROM (SELECT rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section 
  		FROM findSubSection ss 
  		INNER JOIN reestr_distinct rd ON rd.col1=ss.col1 
  		INNER JOIN mi_reestr_section mrs ON rd.col15=mrs.col1 
  		WHERE UPPER(ss.col5Morpheme) LIKE upper('%$word%') AND ss.col5Soudex LIKE '%".self::$morpheme->soundex."%'
  		AND mrs.col2_section IN (".self::$section.")
  		GROUP BY rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section) a1"; //
		echo "<p>".$sql."</p>";
		$result=$this->getAllRecinArray($sql);
		echo "Кол-во, где совпали разделы в МИ (по метафону)<br>";
		return $result;
	}
	
	/**
	 * Кол-во совпадений в МИ (по метафону РУССКОМУ)
	 * @return null
	 */
	public function getCountbyMetaphoneRU() {
		$word=self::$morpheme->metaphoneRU;
		$this->connectPDO();
		$sql="SELECT COUNT(*) cnt FROM (SELECT rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section 
  		FROM findSubSection ss 
  		INNER JOIN reestr_distinct rd ON rd.col1=ss.col1 
  		INNER JOIN mi_reestr_section mrs ON rd.col15=mrs.col1 
  		WHERE UPPER(ss.ruMetaphone) LIKE upper('%$word%') 
  		GROUP BY rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section) a1";
		echo "<p>".$sql."</p>";
		$result=$this->getAllRecinArray($sql);
		echo "Кол-во совпадений в МИ (по метафону РУССКОМУ)<br>";
		return $result;
	}

	/**
	 * Кол-во, где совпали разделы в МИ (по метафону РУССКОМУ)
	 * @return null
	 */
	public function getNumberMatchesMetaphoneRU() {
		$word=self::$morpheme->metaphoneRU;
		$this->connectPDO();
		$sql="SELECT COUNT(*) cnt FROM (SELECT rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section 
  		FROM findSubSection ss 
  		INNER JOIN reestr_distinct rd ON rd.col1=ss.col1 
  		INNER JOIN mi_reestr_section mrs ON rd.col15=mrs.col1 
  		WHERE UPPER(ss.ruMetaphone) LIKE upper('%$word%') 
  		AND mrs.col2_section IN (".self::$section.")
  		GROUP BY rd.col1,rd.col15, ss.ruMetaphone, mrs.col2_section) a1";
		echo "<p>".$sql."</p>";
		$result=$this->getAllRecinArray($sql);
		echo "Кол-во, где совпали разделы в МИ (по метафону РУССКОМУ)<br>";
		return $result;
	}
	
	/**
	 * Получить разделы для слова (найдено его ОСНОВА или ТРАНСКРИПЦИЯ ОСНОВЫ getMorpheme - здесь настраивается)
	 * @return mixed
	 */
	public function getSection() {
		$word=self::$wordSearch;
		$wordTrans=self::$morpheme->transcript;
		echo "<b><u>Разделы:</u></b><br>";
		$word = mb_strtoupper($word, 'UTF-8');
		$dbh=$this->connectPDO();
		$sql="SELECT id, miName FROM mi_reestr_main WHERE (UPPER (miName) LIKE '%".$word."%' OR UPPER (miName) LIKE '%".$wordTrans."%')";
		$res = $this->getAllRecinArray($sql);
		if (empty ($res)) { echo ('Разделы не найдены!<br>');}
		$i=0;
		foreach ($res as $key=> $val) {
			$ret[]=$val[id];
			$i++;
		}
		$dbh = null;
		echo "Соответствует разделам = ";
		$returnTxt=implode(",",$ret);
		print_r($returnTxt);
		echo "<br>";
		self::$section=$returnTxt;
		$responce[section]=$ret;
		$responce[text]=$returnTxt;
		$responce[count]=$i;
		return $responce;
	}
}

class customRUMetaphon {
	public function ruMetaphone($word) {//		var_dump($word);

		$vowel=$this->getFirstVowel($word);
		$consonant=$this->getConsonant($word);
		$ruMetaphone=$vowel[0].$consonant;
		if ($ruMetaphone == null) {$ruMetaphone=$word;}
		return $ruMetaphone;
	}

	public function getFirstVowel($word) {
//		$vowel="";
		preg_match_all("/(а|о|и|е|ё|э|ы|у|ю|я)/isu", $word, $vowel_array);
		if (count($vowel_array) > 0) {
			$vowel = $vowel_array[0];
		} else {
			$vowel=null;
		}
		return $vowel[0];
	}

	public function getConsonant($word) {
		$consonant="";
		preg_match_all("/(б|в|г|д|ж|з|й|к|л|м|н|п|р|с|т|ф|х|ц|ч|ш|щ|ь|ъ)/isu", $word, $consonant_array);
		if (count($consonant_array) > 0) {
//			print_r($consonant_array);
			$consonant = implode($consonant_array[0],"");
		} else {
			$consonant=null;
		}
		return $consonant;
	}

}