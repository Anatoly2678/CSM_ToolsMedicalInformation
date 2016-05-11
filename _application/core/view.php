<?php

class View
{
	
	//public $template_view; // здесь можно указать общий вид по умолчанию.
	
	/*
	$content_file - виды отображающие контент страниц;
	$template_file - общий для всех страниц шаблон;
	$data - массив, содержащий элементы контента страницы. Обычно заполняется в модели.
	*/
	function generate($content_view, $template_view=null, $data = null)
	{
		
		/*
		if(is_array($data)) {
			extract($data);			// преобразуем элементы массива в переменные
		}
		*/
		
		/*
		динамически подключаем общий шаблон (вид),
		внутри которого будет встраиваться вид
		для отображения контента конкретной страницы.
		*/
            if ($template_view !== null) {
                include '_application/views/'.$template_view;
            } else {
                include '_application/views/'.$content_view;
            }
	}
	
	function getjsondate($date = null)
	{
		return $date;
	}
}
