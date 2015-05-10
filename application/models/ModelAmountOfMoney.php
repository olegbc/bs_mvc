<?php
namespace application\models;
class ModelAmountOfMoney extends \application\core\model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_data()
    {
    }

    public function amountOfMoney(){
        $datetime1 = new \DateTime($_POST["from"]);
        $datetime2 = new \DateTime($_POST["to"]);
        $interval = $datetime1->diff($datetime2);
        $num_weeks = ($interval->days+1)/7;

        $start_week_range_unix = strtotime($_POST["from"]);

        for($t=0;$t<$num_weeks;$t++){
            $start_week = date('Y-m-d',$start_week_range_unix + (604800*$t));
            $stop_week = date('Y-m-d',$start_week_range_unix + ((604800*($t+1))-86400));

            $data = $this->getSumMoneyForThisWeek($start_week,$stop_week);

            $arr[$t][0]=$data[0];
            $arr[$t][1]=$start_week." - ".$stop_week;
        }
        return $arr;
    }

    /////////////////////////////////////////////////////////   GETTERS/SETTERS   /////////////////////////////////////////////////////////

    public function getSumMoneyForThisWeek($start_week,$stop_week){
        $db = $this->db;
        $sql = "SELECT SUM(`given`) FROM `payment_has` WHERE `date` between '".$start_week."' AND '".$stop_week."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
}