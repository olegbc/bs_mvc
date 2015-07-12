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
        $gettersSetters = $this->gettersSetters;
        $datetime1 = new \DateTime($_POST["from"]);
        $datetime2 = new \DateTime($_POST["to"]);
        $interval = $datetime1->diff($datetime2);
        $num_weeks = ($interval->days+1)/7;

        $start_week_range_unix = strtotime($_POST["from"]);

        for($t=0;$t<$num_weeks;$t++){
            $start_week = date('Y-m-d',$start_week_range_unix + (604800*$t));
            $stop_week = date('Y-m-d',$start_week_range_unix + ((604800*($t+1))-86400));

            $data = $gettersSetters->getSumMoneyForThisWeek($start_week,$stop_week);

            if($data[0]==null){
                $arr['amount'][]=0;
            }else{
                $arr['amount'][]=(int)$data[0];
            }
            $arr['weekRange'][]=date('M',$start_week_range_unix + (604800*$t))." :".$start_week." - ".$stop_week;

        }
        return $arr;
    }
}