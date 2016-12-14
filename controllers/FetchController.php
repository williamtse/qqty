<?php
namespace app\controllers;
use yii;
use yii\web\Controller;
use app\models\Schedule;
define('SCHEDULE_URL', 'http://m.qqty.com/Schedule/index.html');

class FetchController extends Controller
{
    /**
     * 采集qqty某一天的赛程
     * @param string $date 日期
     */
    public function actionSchedule()
    {
        
    }
}

