<?php
namespace application\Controllers;
class ControllerNumberOfStudents extends \application\core\Controller
{

    public function __construct()
    {
        $this->model = new \application\models\ModelNumberOfStudents;
        $this->view = new \application\core\View;
    }
    public function actionIndex()
    {
        $this->view->generate('ViewNumberOfStudents.php', 'ViewTemplateNumberOfStudents.php');
    }

    public function actionAllTeachers()
    {
        $data = $this->model->allTeachers();
        echo json_encode($data);
    }

    public function actionDatepicker()
    {
        $data = $this->model->datepicker();
        echo json_encode($data);
    }
}