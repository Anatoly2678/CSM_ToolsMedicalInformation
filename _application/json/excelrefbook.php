<?php
/**
 * Created by PhpStorm.
 * User: Анатоли
 * Date: 20.06.2016
 * Time: 4:11
 */
?>
<table border="1">
    <tr>
        <th>Раздел</th>
        <th>Подраздел</th>
        <th>Код</th>
        <th>Наименование</th>
        <th>Описание</th>
        <th>Подгруппа</th>
    </tr>
    <?php
    while($row = $result->fetch_array()) {

        echo '
		<tr>
			<td>'.$row['Section'].'</td>
			<td>'.$row['SubSection'].'</td>
			<td>'.$row['col1'].'</td>
			<td>'.$row['col3'].'</td>
			<td>'.$row['col4'].'</td>
			<td>'.$row['col3_first_word'].'</td>
		</tr>
		';
    }
    ?>
</table>
