<?php
/**
 * Created by PhpStorm.
 * User: Анатоли
 * Date: 01.10.2016
 * Time: 22:03
 */
error_reporting(E_ERROR| E_WARNING | E_PARSE); //
define('path', '_application');
define('controller', 'controllers');
define('model', 'models');
define('views', 'views');

if (isset($_GET['name'])) {
    $controllerName=$_GET['name'];
} else {
    echo "* name - controller name<br>";
    echo "viewsName - views name<br><hr>";
    die ('Not Params name');
}
if (isset($_GET['name'])) {
# region Add Controller
    $controller_path = "../" . path . "/" . controller . "/controller_" . $controllerName . ".php";
    print_r($controller_path);
    $myfile = fopen($controller_path, "w");
    $txt = "<?php\r\n";
    $txt .= "class Controller_" . $controllerName . " extends Controller\r\n";
    $txt .= "{\r\n";
    $txt .= "\tfunction __construct()\r\n";
    $txt .= "\t{\r\n";
    $txt .= "\t\t\$this->model=new model_" . $controllerName . "();\r\n";
    $txt .= "\t\t// \$this->view = new View();\r\n";
    // $this->model=new model_morphAnalysis();
    $txt .= "\t}\r\n";
    $txt .= "\tfunction action_index()\r\n";
    $txt .= "\t{\r\n";
    $txt .= "\t\t// \$this->model->get_data();\r\n";
    $txt .= "\t\t// \$this->view->generate('report.php', 'cms_template.php');\r\n";
    $txt .= "\t}\r\n";
    $txt .= "}";
    fwrite($myfile, $txt);
    fclose($myfile);
#endregion
    echo "<br>";
# region Add Model
    $model_path = "../" . path . "/" . model . "/model_" . $controllerName . ".php";
    print_r($model_path);
    $myfile = fopen($model_path, "w");
    $txt = "<?php\r\n";
    $txt .= "class model_" . $controllerName . " extends Model\r\n";
    $txt .= "{\r\n";
    $txt .= "\tpublic function get_data(\$where=null) \r\n";
    $txt .= "\t{\r\n";
    $txt .= "\t\t// ............ \r\n";
    $txt .= "\t}\r\n";
    $txt .= "}";
    fwrite($myfile, $txt);
    fclose($myfile);
#endregion
}
echo "<br>";
if (isset($_GET['viewsName'])) {
# region Add Views
    $views_path = "../" . path . "/" . views . "/".$_GET['viewsName'] . ".php";
    print_r($views_path);
    $myfile = fopen($views_path, "w");
    $txt = "<!-- <div>\r\n";
    $txt .= "....\r\n";
    $txt .= "</div> -->";
    fwrite($myfile, $txt);
    fclose($myfile);
#endregion
}