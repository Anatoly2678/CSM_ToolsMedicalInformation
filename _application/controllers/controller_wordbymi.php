<?php
error_reporting(E_ERROR);
class Controller_wordByMI extends Controller
{
	function __construct()
	{
		$this->model=new model_wordByMI();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('wordbymi.php', 'cms_template.php');
	}

	function action_json()
	{
		$this->model->setJSONHead();
		$result=$this->model->get_data();
		echo json_encode($result);
	}

	function action_updateRec()
	{
		$result=$this->model->updateRecordsInTable();
//		echo json_encode($result);
	}

	function action_export() {
		$this->model->setExcelHead('Слова в МИ.xls');
		echo '<table border="1">';
		echo '<tr>';
		echo '<th>УНРЗ</th>';
		echo '<th>Кол-во слов найденных</th>';
		echo '<th>Слова найденные</th>';
//		echo '<th>Корень</th>';
		echo '<th>Раздел присвоенный для слов</th>';
		echo '<th>Вид МИ в соотв с номенкл</th>';
		echo '<th>Раздел присвоенный для МИ</th>';
		echo '<th>Совпали разделы</th>';
		echo '<th>Кол-во совпадений</th>';
		echo '<th>% верно</th>';
		echo '<th>% неверно</th>';
		echo '</tr>';
		$result=$this->model->get_data();
		foreach ($result as $res) {
			echo '
			<tr>
			<td>'.$res['col1'].'</td>
			<td>'.$res['cnt'].'</td>
			<td>'.$res['wordConcat'].'</td>
<!--			<td>'.$res['root'].'</td> -->
			<td>'.$res['selectorWord'].'</td>
			<td>'.$res['col15'].'</td>
			<td>'.$res['selectorMI'].'</td>			
			<td>'.$res['match_value'].'</td>
			<td>'.$res['match_count'].'</td>
			<td>'.str_replace(".", ",",$res['percen_true']).'</td>
			<td>'.str_replace(".", ",",$res['percent_false']).'</td>
			</tr>
			'; // str_replace("%body%", "black", "<body text='%body%'>");
			// $nombre_format_francais = number_format($number, 2, ',', ' ');
		}
		echo '</table>';
	}

	function action_update()
	{
//		echo 'Update table: '.MIReestr;
		$result=$this->model->addRecordsInTable();
		echo $result; //'<br>Update DONE';

	}
}