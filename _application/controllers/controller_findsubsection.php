<?php
ini_set('memory_limit', '-1');
class Controller_findSubSection extends Controller
{
	function __construct() {
		$this->model=new model_findSubSection();
		$this->model2=new model_findSubSection2();
	}

	function action_createTables() {
		$this->model->createTable();
	}
	
	function action_setReport() {
		$words=$this->model->readBasisWord();
		foreach ($words as $word) {
			$this->report($word);
		}
	}

	function action_get2fill() {
		$words=$this->model->readBasisWord();
		foreach ($words as $word) {
			$_GET[word] = $word;
			$this->action_get2();
			echo "<hr>";
			echo "<hr>";
		}
	}

	function action_get2() {
		$w=$_GET[word];
		$morpheme=$this->model2->getMorpheme($w); //Подготавливаем объект по слову
		var_dump($morpheme);
		echo "<hr>";
		$countMI=$this->model2->getCountbyMI();
		var_dump($countMI);
		echo "<hr>";
		$countMetaphone=$this->model2->getCountbyMetaphone();
		var_dump($countMetaphone);
		echo "<hr>";
		$countMetaphoneRU=$this->model2->getCountbyMetaphoneRU();
		var_dump($countMetaphoneRU);
		echo "<hr>";
		echo "<div style='color:red'>";
		$countSection=$this->model2->getSection();
		var_dump($countSection);
		echo "<hr>";
		$getNumberMatchesMI=$this->model2->getNumberMatchesMI();
		var_dump($getNumberMatchesMI);
		echo "<hr>";
		$getNumberMatchesMetaphone=$this->model2->getNumberMatchesMetaphone();
		var_dump($getNumberMatchesMetaphone);
		echo "<hr>";
		$getNumberMatchesMetaphoneRU=$this->model2->getNumberMatchesMetaphoneRU();
		var_dump($getNumberMatchesMetaphoneRU);
		echo "<hr>";
		echo "</div>";

	}

	function action_index()	{
		$w=$_GET[word];
		$this->report($w);
	}

	private function report ($w) {
		$this->model->getWord($w);
		$resSection=$this->model->getSection($w);
		$resSubSection=$this->model->getSubSection($w);
		$rezMi=$this->model->getMIWord($w);
		$rezMeta=$this->model->getMIMEtaphone($w);
		$this->model->findURNZ($rezMeta[urnz],$rezMi[urnz]);
		$res0=$this->model->getResponse();
		echo "<b><u>Совпадения разделов раздел + слово:</u></b><br>";
		$r1=$this->model->findSection($resSection[section],$rezMi[section]);
		$res0->matchSectionInWord=$r1[word];
		$res0->matchSectionInWordCount=$r1[all];
		$res0->matchSectionInWordPercentYes=$r1[matchPercent];
		$res0->matchSectionInWordPercentNo=$r1[diffPercent];
		echo "<b><u>Совпадения подразделов подраздел + слово:</u></b><br>";
		$r2=$this->model->findSection($resSubSection[subSection],$rezMi[subsection]);
		$res0->matchSubsectionInWord=$r2[word];
		$res0->matchSubsectionInWordCount=$r2[all];
		$res0->matchSubsectionInWordPercentYes=$r2[matchPercent];
		$res0->matchSubsectionInWordPercentNo=$r2[diffPercent];
		echo "<b><u>Совпадения разделов раздел + метафон:</u></b><br>";
		$r3=$this->model->findSection($resSection[section],$rezMeta[section]);
		$res0->matchSectionInMetaphone=$r3[word];
		$res0->matchSectionInMetaphoneCount=$r3[all];
		$res0->matchSectionInMetaphonePercentYes=$r3[matchPercent];
		$res0->matchSectionInMetaphonePercentNo=$r3[diffPercent];
		echo "<b><u>Совпадения подразделов подраздел + метафон:</u></b><br>";
		$r4=$this->model->findSection($resSubSection[subSection],$rezMeta[subsection]);
		$res0->matchSubsectionInMetaphone=$r4[word];
		$res0->matchSubsectionInMetaphoneCount=$r4[all];
		$res0->matchSubsectionInMetaphonePercentYes=$r4[matchPercent];
		$res0->matchSubsectionInMetaphonePercentNo=$r4[diffPercent];
		print_r($res0);
//		echo "MEM: ".memory_get_usage() . "<br>";
		$this->model->saveReportInTable($res0);
	}

	function action_updateMI()	{
		$this->model->updateMI();
	}

	function action_morpheme()	{
		$this->model->updateMorpheme();
	}

}