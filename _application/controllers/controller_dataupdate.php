<?php
/**
 * Created by PhpStorm.
 * User: Анатоли
 * Date: 04.07.2016
 * Time: 21:15
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE); //

class Controller_dataupdate extends Controller {
    function __construct() {
//        require_once '/_application/Phonetic/Phonetic.php';
        $this->model = new model_dataupdate();
        $this->search = new model_search();
        $this->fastSearch = new test_search();
    }

    /** Get Section MI and Split on Section from table "mi_reestr"
     * and Insert distinct record section in table "mi_section"
     */
    function action_createSectionMI() {
        $this->model->connect();
        $this->model->createSectionMI();
        $this->model->MIreestrDistinct();
        $this->model->close();
    }

    /** Set shot MI values in SQL table
    *
    */
    function action_setShotMIValues() {
        $this->model->connect();
        echo "Set shot MI Values in table"+"<br>";
        $this->model->setShotMIValues();
        echo "Set shot MI Values Completed.....";
        // $this->model->MIreestrDistinct();
        $this->model->close();
    }


    function action_fastSearch() {
        $word="Аппарат для санации раневой поверхности";
        $word="Баллон наконечник упаковка";
        print_r($word);
        echo "<br>";
        $test=$word;
        // $test=$this->fastSearch($word);
//        print_r($test);
        // ob_start();
        $this->fastSearch->GetTestSearch($test);
        // ob_end_clean();
    }


    function action_keywords() {
        $this->search->setKeyWords();
    }

    function action_index()
    {

        $word="Аппарат для санации раневой поверхности \"Pulsavac Plus\" с принадлежностямиварианты исполнения: 1. Аппарат для санации раневой поверхности «Pulsavac Plus» (вид 248280). 2. Аппарат для санации раневой поверхности «Pulsavac Plus» с веерной насадкой (вид 248280). 3. Аппарат для санации раневой поверхности «Pulsavac Plus» для обработки тазобедренного сустава (вид 248280). 4. Аппарат для санации раневой поверхности «Pulsavac Plus» с душирующей насадкой (вид 248280). 5. Аппарат для санации раневой поверхности «Pulsavac Plus АС» (вид 248310). 6. Аппарат для санации раневой поверхности «Pulsavac Plus АС» с веерной насадкой (вид 248310). 7. Аппарат для санации раневой поверхности «Pulsavac Plus АС» ";
        $word="ушные вкладыши";
        $word="реклинатор медицинский";

//        echo $word;
//        echo "<br>";
//        $word1=$this->search->searchWord($word);
//        print_r($word1);
//        $search_model
        $this->model->connect();
        $this->model->MIreestrDistinct();
//        $this->model->MIreestrSetSection();
        $this->model->close();
    }

    function action_test() {
        // $this->model->TestMyAlgoritm();
        $this->model->connect();
        $this->model->GetMISimilar();
        $this->model->close();
    }

    function action_mireestrTranslate() {
        print_r("Translate<br>");
        $this->model->connect();
        $this->model->miReestrTransalte();
        $this->model->close();
    }

}