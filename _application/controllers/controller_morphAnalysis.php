<?php
define('tableMorpheme', 'morpheme');
define('tableSearchReestr', 'search_reestr');
define('site1', 'http://morphemeonline.ru');
define('site2', 'http://slovonline.ru/');

class Controller_morphAnalysis extends Controller
{
	function __construct()
	{
		$this->model=new model_morphAnalysis();
		$this->modelTest=new model_morphAnalysisTest();
	}

	function action_level1() {
		$res=$this->modelTest->getMiReestrMain();
		foreach ($res as $r) {
			$r=$this->modelTest->delSpecChar($r);
			$lenStr=mb_strlen ($r, "UTF-8");
			if ($lenStr>3) {
				print_r($lenStr . " - ");
				print_r($r. " = ");
				$resW=$this->slovoline($r);
				print_r($resW);
				echo "<br>";
			}
		}
	}

	function action_slovonline() {
		header ("Content-type: text/html;charset=windows-1251");
		$word = $_POST['word'];
		$this->slovoline($word);

	}
	
	protected function slovoline($word) {
		$word = mb_convert_case($word, MB_CASE_UPPER, "UTF-8");
		$word2 = iconv("UTF-8", "Windows-1251", $word);
		$resURL = $this->model->getURLFromMorpheme(site2."sug.php",$word);
		$resHref = $this->model->loadPage($resURL);
		$div = $this->model->getMorpheme($resHref,'|<w>(.*)</w>(.*)<p>(.*)</p>|isU','site2url');
		$urlListKey = array_search($word2, $div->word);
		$url=$div->site[$urlListKey];
		$url=site2.$url;
		$resHref = $this->model->loadPage($url);
		$div = $this->model->getMorpheme($resHref,'|<div class="word-article">(.*)<ul>(.*)</ul>(.*)</div>|isU','site2data');
		$pattern='/(Основа слова|приставка|корень|суффикс|соединительная гласная|окончание)(.*)<b>(.*)<\/b>/isU';
		$pattern = iconv("UTF-8", "Windows-1251", $pattern);
		$typeText = $this->model->getMorpheme($div->morpheme[0],$pattern,'morpheme');
		$arrobj=$this->model->resMorpheme($typeText);
		$resultInsert=$this->model->saveInTable($word2,$arrobj);
		print_r($resultInsert);
	}

	function action_index()
	{
//		header("Content-type: text/html;charset=utf-8");
		$word=$_POST['word'];
		$arrayFChar=$this->model->getFirstChar($word);
		$getUrl1=site1."/".$arrayFChar->case."/".$arrayFChar->word;
		$href=$this->model->loadPage($getUrl1);
		$div=$this->model->getMorpheme($href,'|<div class="morpheme">(.*)</div><p>(.*)</p>|isU','site1');
		print_r($div);
	}

}