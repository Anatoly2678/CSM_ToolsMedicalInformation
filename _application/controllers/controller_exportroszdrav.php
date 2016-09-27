<?php
class Controller_exportroszdrav extends Controller {
		function __construct() {
			$this->model = new model_exportroszdrav();
	}

	function action_miload() {
		ini_set("max_execution_time", 0);
		header("Content-type: text/json;charset=utf-8");
		$this->model->changeParseURL('http://www.roszdravnadzor.ru/ajax/services/mi_reesetr');
		$start = 0;
		$end = 29000;
		$step=100;
		ob_implicit_flush(1);
		for ($x = $start; $x <= $end; $x=$x+$step) {
			$params = array(
				"draw" => "1",
				"start" => $x,
				"length" => $step//, 100 000
			);
			var_dump($params);
			$arrJSON = $this->model->loadExportRoszdrav($params);
			$this->model->insertToTable($arrJSON, 'mi');
			ob_flush();
			flush();
			ob_end_clean();
			ob_end_flush();
		}
	}

	function action_oneload() {
		ini_set("max_execution_time", 0);
		header("Content-type: text/json;charset=utf-8");
		$date = new DateTime();
		$d2=$date->format('d.m.Y');
			var_dump("Ster No. ".$x."<br>");
			$params = array(
				"draw"=>"4",
				"dt_ru_to" => $d2,
				"order" => array("0" => array ("column" => "0", "dir" => "asc")),
				"start" => 0,
				"length" => 25//, 100 000
			);
			var_dump($params);
			$arrJSON = $this->model->loadExportRoszdrav($params);
			$this->model->insertToTable($arrJSON);
	}

	function action_today() {
		ini_set("max_execution_time", 0);
		header("Content-type: text/json;charset=utf-8");
		$date = new DateTime();
		$d2=$date->format('d.m.Y');
		$start = 70000; // 0
		$end = 80000;
		$step=1000;
		ob_implicit_flush(1);
//		$date = new DateTime();
		print_r ($date);
		for ($x = $start; $x <= $end; $x=$x+$step) {
			ob_start();
			var_dump("Ster No. ".$x."<br>");
			$params = array(
				"draw"=>"4",
				"dt_ru_to" => $d2,
				"order" => array("0" => array ("column" => "0", "dir" => "asc")),
				"start" => $x,
				"length" => $step//, 100 000
			);
			var_dump($params);
			$arrJSON = $this->model->loadExportRoszdrav($params);
			$this->model->insertToTable($arrJSON);
			ob_flush();
  			flush();
  			ob_end_clean();
  			ob_end_flush();
			$date_end = new DateTime();
			print_r($date_end);
		}
	}

	function  action_02day() {
		$date = new DateTime();
		$d2=$date->format('d.m.Y');
		$d1=$date->modify('-2 day')->format('d.m.Y');
		$params = array(
			"dt_ru_from" => $d1,
			"dt_ru_to" => $d2,
			"start" => 0,
			"length" => 1000000//,
		);
		var_dump($params);
		$arrJSON = $this->model->loadExportRoszdrav($params);
		$this->model->insertToTable($arrJSON);
	}

	function  action_index() {
		$date = new DateTime();
		$d2=$date->format('d.m.Y');
		$d1=$date->modify('-5 day')->format('d.m.Y');
		$params = array(
			"dt_ru_from" => $d1,
			"dt_ru_to" => $d2,
			"start" => 0,
			"length" => 1000000//,
		);
		var_dump($params);
		$arrJSON = $this->model->loadExportRoszdrav($params);
		$this->model->insertToTable($arrJSON);
	}

	function  action_07_2016() {
		$params = array(
			"dt_ru_from" => "01.07.2016",
			"dt_ru_to" => "31.07.2016",
			"start" => 0,
			"length" => 1000000//,
		);
//		echo "<h2>";
//		echo "05.2016";
//		echo "<br>";
		var_dump($params);
		echo "</h2>";
		$arrJSON = $this->model->loadExportRoszdrav($params);
		$this->model->insertToTable($arrJSON);
	}

	function  action_year_select()
	{
		$startyear = (int)$_GET['start'];
		$endyear = (int)$_GET['end'];
		if ($startyear < 1990) die ('Дата не может быть меньше 1990 г.');
		if ($startyear > $endyear) die ('Дата С не может быть больше даты ПО');
		if ($endyear == null) $endyear = $startyear;
		$yy = $startyear - 1;
		while ($yy < $endyear) {
			$yy++;
			$params = array(
				"dt_ru_from" => "01.01." . $yy,
				"dt_ru_to" => "31.12." . $yy,
				"start" => 0,
				"length" => 1000000//,
			);
			echo "<h2>";
			echo "дата выгрузки с: 01.01." . $yy . " по: 31.12." . $yy;
			echo "<br>";
			var_dump($params);
			echo "</h2>";
			$arrJSON = $this->model->loadExportRoszdrav($params);
			$this->model->insertToTable($arrJSON);
		}
	}

	function action_year_full() {
		$yy=1989;
		while ($yy<2016) {
			$yy++;
			$params = array(
				"dt_ru_from" => "01.01." . $yy,
				"dt_ru_to" => "31.12." . $yy,
				"start" => 0,
				"length" => 1000000//,
			);
			echo "<h3>";
			echo $yy;
			echo "<br>";
			var_dump($params);
			echo "</h3>";
			$arrJSON = $this->model->loadExportRoszdrav($params);
			$this->model->insertToTable($arrJSON);
		}
	}
	
	function action_monthyear_full()	{
		$y=1989;
		while ($y<2016) {
			$y ++;
			$m = 0;
			$delta = 1;
			while ($m < 12) {
				$m++; // Увеличение счетчика
				$n = ($m + $delta);
				echo $m;
				echo "<hr>";
				$d1 = "01." . $m . ".".$y;
				$d2 = "01." . $n . ".".$y;
				$params = array(
					"dt_ru_from" => $d1,
					"dt_ru_to" => $d2,
					"start" => 0,
					"length" => 1000000//,
					//"q_no_uniq" => "2237"
				);
				var_dump($params);
				$arrJSON = $this->model->loadExportRoszdrav($params);
				$this->model->insertToTable($arrJSON);
			}
		}
	}

	function action_init() {
		$this->model->createDBExportRosZdrav(); // Create DB. Runs Once
		$this->model->createTableExportRosZdrav(); // Create Table. Runs Once
//		die ();
	}

	function  action_alterdistinct() {
		$this->model->alterTableDistinct();
//		die ();
	}

	function  action_CreateTablesSectionMI() {
		$this->model->CreateSectionforMI();
	}


	function action_distinctrecord() {
		echo "убираем дублирующие записи<br>";
		$this->model->connect();
		$this->model->deleteDuplicate();
		echo "таблица с уникальными записями успешно сохранена<br>";
		$this->model->updatecol4_data();
		echo "даты преобразованы<br>";
		echo "Получаем короткие нименования МИ";
		$this->model->setShotMIValues();
		$this->model->close();
	}

	function action_updatecol4() {
		$this->model->connect();
		$this->model->updatecol4_data();
		$this->model->close();
	}

}


?>