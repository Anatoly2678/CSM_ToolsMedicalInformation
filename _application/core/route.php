<?php
/*
Класс-маршрутизатор для определения запрашиваемой страницы.
> цепляет классы контроллеров и моделей;
> создает экземпляры контролеров страниц и вызывает действия этих контроллеров.
*/
class Route
{
	static function start($url=null)
	{
		// контроллер и действие по умолчанию
		$controller_name = 'Invoice';
		$action_name = 'index';
		$routes = explode('/', $_SERVER['REQUEST_URI']);

		// получаем имя контроллера
		if ( !empty($routes[1]) && $routes[1] !='index.php')
		{	
			$controller_name = $routes[1];
		}
		
		// получаем имя экшена
		if ( !empty($routes[2]) )
		{
			$action_name = explode('?', $routes[2])[0];
		}

		// добавляем префиксы
		$model_name = 'Model_'.$controller_name;
		$controller_name = 'Controller_'.$controller_name;
		$action_name = 'action_'.$action_name;

		// подцепляем файл с классом модели (файла модели может и не быть)
		$model_file = strtolower($model_name).'.php';
		$model_path = "_application/models/".$model_file;
		if(file_exists($model_path))
		{
			include "_application/models/".$model_file;
		}

		// подцепляем файл с классом контроллера
		$controller_file = strtolower($controller_name).'.php';
		$controller_path = "_application/controllers/".$controller_file;

		if(file_exists($controller_path))
		{
			include "_application/controllers/".$controller_file;
		}
		else
		{
//			echo ('Error подцепляем файл с классом контроллера');
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			Route::ErrorPage404();
		}
      
		// создаем контроллер
		$controller = new $controller_name;
		$action = $action_name;
		
		if(method_exists($controller, $action))
		{
			// вызываем действие контроллера
			$controller->$action();
		}
		else
		{
//			echo ('Error создаем контроллер');
			// здесь также разумнее было бы кинуть исключение
			Route::ErrorPage404();
		}
	
	}

	function ErrorPage404()
	{
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'404');
        }
        
//        function GoPage($url) {
//            header('Location:'.$url);
//        }
//
}
