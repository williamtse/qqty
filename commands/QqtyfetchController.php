<?php

/**
 * 全球体育数据采集-足球
 * 
 */

namespace app\commands;
set_time_limit(0);
use yii;
use app\models\Schedule;
use app\components\Simpledom;
use app\components\Multicurl;
use yii\console\Controller;
use app\models\Ratio;
use app\models\Chupan;

define('SCHEDULE_URL', 'http://m.qqty.com/Schedule/index.html');
define('SCHEDULE_RESULT', 'http://m.qqty.com/ScheduleResult/index.html');

class QqtyfetchController extends Controller {

    const SCHEDULE = 1;
    const LIVE = 2;
    const RESULT = 3;

    function cbProcessFunc($r, $args) {
        $args['finished']++;
        echo "start:{$args['finished']}/{$args['total']}\n";
        $pan = $args['pan'];
        $scheid = $args['scheid'];
        $class = "tb_odds_$pan";
        $dom = Simpledom::get_dom_from_string($r['content']);
        if ($dom) {
            //检查是否已经完场
            $status = trim($dom->find('div.score_main',0)
                    ->find('div.s_center',0)
                    ->find('p',0)->plaintext);
            echo "  $status\n";
            if($status == '完场'){
                $sche = Schedule::findOne($scheid);
                $sche->ended = 1;
                $sche->update();
                echo "Ended {$args['url']}\n";
                return;
            }
            foreach ($dom->find('tr') as $idx => $tr) {
                if ($idx > 0) {
                    $ratio = new Ratio();

                    $tds = $tr->find('td');
                    $company = $tds[0]->plaintext;

                    $latest_row = Ratio::find()->where(['pan' => $pan, 'company' => $company, 'match_id' => $scheid])->orderBy('id desc')->one();
                    $chupan = Chupan::findOne(['pan' => $pan, 'company' => $company, 'scheid' => $scheid]);
                    if ($chupan) {
                        $chupan->setIsNewRecord(false);
                    } else {
                        $chupan = new Chupan();
                    }
                    $check = false;
                    switch ($pan) {
                        case 'euro'://欧盘
                        case 'asia'://亚盘
                            $zs = $tds[2]->find('div'); //主胜
                            $ks = $tds[4]->find('div'); //客胜
                            if (count($zs) > 1 && (count($ks) > 1)) {
                                $cp_zs = floatval($zs[0]->plaintext); //初盘主胜
                                $jp_zs = floatval($zs[1]->plaintext); //及盘主胜

                                $pj = $tds[3]->find('div'); //平局|让球|大小
                                $cp_pj = $pj[0]->plaintext; //初盘平局
                                $js_pj = $pj[1]->plaintext; //欧盘及时平局|亚盘让球盘口|大小盘口

                                $cp_ks = floatval($ks[0]->plaintext); //初盘客胜
                                $js_ks = floatval($ks[1]->plaintext); //及时客胜
                                $check = true;
                            }
                            break;
                        case 'bigs'://大小
                            $cp_zs = floatval($tds[1]->plaintext);
                            $cp_pj = $tds[2]->plaintext;
                            $cp_ks = floatval($tds[3]->plaintext);
                            $jp_zs = floatval($tds[4]->plaintext);
                            $js_pj = $tds[5]->plaintext;
                            $js_ks = floatval($tds[6]->plaintext);
                            $check=true;
                            break;
                        default:
                            continue;
                            break;
                    }

                    if ($check) {
                        $chupan->pan = $pan;
                        $chupan->h_value = $jp_zs;

                        $chupan->c_value = $js_ks;
                        $chupan->pankou = $js_pj;
                        $chupan->scheid = $scheid;
                        $chupan->company = $company;
                        if (!$chupan->save()) {
                            foreach ($chupan->errors as $k => $e) {
                                echo "Error:$k:\n";
                                foreach ($e as $et) {
                                    echo "    $et {$chupan->h_value} given\n";
                                }
                            }
                        }

                        if ($latest_row) {
                            if (!($latest_row->h_value == $jp_zs && $latest_row->c_value == $js_ks && $latest_row->pankou = $js_pj)) {
                                $ratio->c_value = $js_ks;
                                $ratio->match_id = $scheid;
                                $ratio->pan = $pan;
                                $ratio->company = $company;
                                $ratio->h_value = $jp_zs;
                                $ratio->pankou = $js_pj;
                                $ratio->create_at = time();
                                if (!$ratio->insert()) {
                                    foreach ($ratio->errors as $k => $e) {
                                        echo "Error:$k:\n";
                                        foreach ($e as $et) {
                                            echo "    $et {$chupan->h_value} given\n";
                                        }
                                    }
                                }
                            }
                        } else {
                            $ratio->c_value = $js_ks;
                            $ratio->match_id = $scheid;
                            $ratio->pan = $pan;
                            $ratio->company = $company;
                            $ratio->h_value = $jp_zs;
                            $ratio->pankou = $js_pj;
                            $ratio->create_at = time();
                            if (!$ratio->insert()) {
                                foreach ($ratio->errors as $k => $e) {
                                    echo "Error:$k:\n";
                                    foreach ($e as $et) {
                                        echo "    $et {$chupan->h_value} given\n";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            Yii::error("No Response:" . $args['url'], "system.fetch");
        }
        echo "Finish url:{$args['url']}\n";
    }

    public function actionIndex($type) {
        if (method_exists($this, $type))
            $this->$type();
    }

    public function actionSchedule($date) {
        echo $date . "\n";
        $url = SCHEDULE_URL . '?date=' . $date;
        $date_formate = date('Y-m-d', strtotime($date));
        $this->query(self::SCHEDULE, $url, $date, $date_formate);
    }

    private function schedule() {
        //$date = Yii::$app->request->get('date');
        $date = date('Ymd', strtotime('+7 days'));
        $date_formate = date('Y-m-d', strtotime('+7 days'));
        echo $date . "\n";
        $url = SCHEDULE_URL . '?date=' . $date;
        $this->query(self::SCHEDULE, $url, $date, $date_formate);
    }

    private function live() {
        $curl = new Multicurl ();
        $curl->maxThread = 100;
        $curl->class = $this;
        $date = date('Ymd', time());
        //所有未结束的比赛（所有未开始的比赛，和，所有已开始但未结束的比赛）
        $sches = Schedule::find()->where(['ended' => 0])->all();
        $date_formate = date('Y-m-d', time());
        $pan = ['asia', 'euro', 'bigs'];
        $total = count($sches);
        $finished = 0;
        foreach ($sches as $sche) {
            $scheid = $sche->id;
            foreach ($pan as $p) {
                $url = "http://m.qqty.com/Details/odds_$p/scheid/$scheid.html";
                $curl->add(array(
                    'url' => $url,
                    'args' => array(
                        'pan' => $p,
                        'scheid' => $scheid,
                        'url' => $url,
                        'total'=>$total,
                        'finished'=>&$finished
                    )
                        ), 'cbProcessFunc');
                echo "add $url \n";
            }
        }
        $curl->start();
    }

    private function result() {
        $date = date('Ymd', strtotime('-1 days'));
        $date_formate = date('Y-m-d', strtotime('-1 days'));
        echo $date . "\n";
        $url = SCHEDULE_RESULT . '?date=' . $date;
        $this->query(self::RESULT, $url, $date, $date_formate);
    }

    private function query($type, $url, $date, $date_formate) {
        $dom = Simpledom::get_dom($url);
        echo $url . "\n";
        $matchDivs = $dom->find('div.match');
        var_dump($matchDivs);
        $pre_hour = 0;
        foreach ($matchDivs as $idx => $matchDiv) {
            $data_url = $matchDiv->attr['data-url'];
            $scheid = substr($data_url, strripos($data_url, '/') + 1);
            echo "scheid:$data_url\n";
            if (!$sche = Schedule::findOne($scheid)) {
                $sche = new Schedule();
                $sche->setIsNewRecord(true);
                $sche->id = $scheid;
            } else {
                $sche->setIsNewRecord(false);
            }
            $top = $matchDiv->find('div.top', 0);

            //杯赛名称
            $sche->cup_name = $top->find('span.match_name', 0)->plaintext;

            if ($timeTag = $top->find('td.js_mach_time ', 0)) {
                if (trim($timeTag->plaintext) == '完场') {
                    $sche->ended = 1;
                }
            }

            //杯赛时间
            $mach_will_time = $top->find('em.mach_will_time', 0)->plaintext;
            $hour = substr($mach_will_time, 0, strpos($mach_will_time, ':'));
            if ($hour < $pre_hour) {
                $date++;
                $date_formate = date('Y-m-d', strtotime($date));
            } else {
                $pre_hour = $hour;
            }

            $sche->start_time = strtotime($date_formate . ' ' . $mach_will_time . ':00');
            $sche->start_date = $date;
            $bottom = $matchDiv->find('div.bottom', 0);
            $tds = $bottom->find('td');

            //主队rank
            $hrankSpan = $tds[0]->find('span.teamRank', 0);
            if (is_object($hrankSpan)) {
                $hrankSpanText = trim($hrankSpan->plaintext);
                $sche->hrank = substr($hrankSpanText, 1, strlen($hrankSpanText) - 2);
            }

            //主队名称
            $sche->hteam = $tds[0]->find('span.homeTeamName', 0)->plaintext;



            //客队名称
            $sche->cteam = $tds[2]->find('span.guestTeamName', 0)->plaintext;

            //客队rank
            $crankSpan = $tds[2]->find('span.teamRank', 0);
            if (is_object($crankSpan)) {
                $crankSpanText = trim($crankSpan->plaintext);
                $sche->crank = substr($crankSpanText, 1, strlen($crankSpanText) - 2);
            }

            if ($type == self::LIVE || $type == self::RESULT) {
                //主队红牌
                $hredcard = $tds[0]->find('span.red_card', 0);
                if (is_object($hredcard)) {
                    $hredcardText = trim($hredcard->plaintext);
                    $sche->hred_card = $hredcardText;
                }

                //主队黄牌
                $hyelcard = $tds[0]->find('span.yel_card', 0);
                if (is_object($hyelcard)) {
                    $hyelcardText = trim($hyelcard->plaintext);
                    $sche->hyel_card = $hyelcardText;
                }

                //客队红牌
                $credcard = $tds[2]->find('span.red_card', 0);
                if (is_object($credcard)) {
                    $credcardText = trim($credcard->plaintext);
                    $sche->cred_card = $credcardText;
                }

                //客队黄牌
                $cyelcard = $tds[2]->find('span.yel_card', 0);
                if (is_object($cyelcard)) {
                    $cyelcardText = trim($cyelcard->plaintext);
                    $sche->cyel_card = $cyelcardText;
                }

                //比分
                $score = $tds[1]->find('span', 0)->plaintext;
                if (strpos($score, '-')) {
                    $scores = explode('-', $score);
                    $sche->hscore = $scores[0];
                    $sche->cscore = $scores[1];
                }

                //盘口，赔率，上半场比分
                $rtd = $tds[3]->find('div.rf', 0); //让球
                $haf = $tds[4]->plaintext; //半场比分
                $dxtd = $tds[5]->find('div.dx', 0); //大小
                if (is_object($rtd)) {
                    $rfSpans = $rtd->find('span');
                    if (count($rfSpans) == 4) {
                        $sche->hratio_r = $rtd->find('span', 1)->plaintext;
                        $sche->r_pankou = $rtd->find('span', 2)->plaintext;
                        $sche->cratio_r = $rtd->find('span', 3)->plaintext;
                    }
                    if (strpos($haf, ':')) {
                        $hafScore = trim(str_replace('(', '', str_replace(')', '', $haf)));
                        $hafScoreArr = explode(':', $hafScore);
                        $sche->h_haf_score = $hafScoreArr[0];
                        $sche->c_haf_score = $hafScoreArr[1];
                    }
                    if (count($dxtd->find('span')) == 4) {
                        $sche->hratio_dx = $dxtd->find('span', 1)->plaintext;
                        $sche->dx_pankou = $dxtd->find('span', 2)->plaintext;
                        $sche->cratio_dx = $dxtd->find('span', 3)->plaintext;
                    }
                }
            }
            if ($sche->save()) {
                echo "Fetch success:{$sche->id}\n";
            } else {
                echo "Fetch failed:{$sche->id}\n";
            }
        }
    }

}
