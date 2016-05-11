<?php
class Controller_exportroszdrav extends Controller {
		function __construct() {
		$this->model = new model_exportroszdrav();
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

	function  action_05_2016() {
		$params = array(
			"dt_ru_from" => "01.05.2016",
			"dt_ru_to" => "31.05.2016",
			"start" => 0,
			"length" => 1000000//,
		);
		echo "<h2>";
		echo "05.2016";
		echo "<br>";
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
			$m = 2;
			$delta = 1;
			while ($m < 3) {
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
		die ();
	}

	function action_distinctrecord() {
		echo "убираем дублирующие записи<br>";
		$this->model->connect();
		$this->model->deleteDuplicate();
		$this->model->close();
		echo "таблица с уникальными записями успешно сохранена";
	}

}


?>