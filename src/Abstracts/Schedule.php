<?php
/**
 * Created by kaleb.
 * User: kaleb
 * Date: 21/01/19
 * Time: 10:55
 */

namespace Jobs\Scheduled;
class Schedule
{
  /**
   * @var
   */
  private $job;
  /**
   * @var array
   */
  private $scheduleds;
  /**
   * @var
   */
  private $dbScheduler;

  /**
   * Schedule constructor.
   */
  public function __construct()
  {
    $this->scheduleds = [];
    $this->changeDbManagerScheduler();
  }

  /**
   *
   */
  private function changeDbManagerScheduler(){
    if (file_exists(__DIR__.'/scheduled.json')) {
      $file = file_get_contents(__DIR__.'/scheduled.json','r');
      $this->dbScheduler = json_decode($file,true);
    } else {
      $this->dbScheduler = [];
    }
  }

  /**
   * @param $job
   * @return $this
   */
  public function add($job)
  {
    $this->job = $job;
    return $this;
  }

  /**
   * @return array
   */
  public function getConfScheduled(){
    return $this->scheduleds;
  }

  /**
   * @return $this
   */
  public function everyDay()
  {
    $this->scheduleds[$this->job]['day'] = 'ever';
    return $this;
  }

  /**
   * @param $hour
   */
  public function atHour($hour)
  {
    $this->scheduleds[$this->job]['hour'] = $hour;
  }

  /**
   * @return array
   */
  public function prepareRun()
  {
    $retRunNow =[];

    foreach($this->scheduleds as $task => $sched){
      if(isset($sched['day'])){
        if($this->isRunToday($task,$sched['day'])){
          if($this->isRunHourNow($task,$sched['hour'])){
            $retRunNow[]=$task;
            $this->writeLastRun($task);
          }
        }
      }
    }
    return $retRunNow;
  }


  /**
   * @param $task
   * @param $day
   * @return bool
   */
  private function isRunToday($task, $day)
  {

    if(empty($day)) return true;

    if($day=="ever") return true;

    if(is_numeric($day)){
      $day_today = date('d');
      if($day_today==$day){
        if(!empty($this->dbScheduler[$task]['lastrun'])){
          $today = date_create(date('Y-m-d'));
          $date_last_run = date_create(
            date('Y-m-d',intval(strtotime($this->dbScheduler[$task]['lastrun'])))
          );
          $diff = date_diff($today,$date_last_run);
          if($diff->invert == 0 and $diff->d > 0){
            return true;
          }
          return false;
        }
        return true;
      }
    }
    return false;
  }

  /**
   * @param $task
   * @param $hour
   * @return bool
   */
  private function isRunHourNow($task, $hour)
  {
    if(empty($hour)) false;

    $now_hour = date('H:i');

    if($now_hour == $hour) {
      $now = date_create(date('Y-m-d H:i'));
      if (!empty($this->dbScheduler[$task]['lastrun'])) {
        $hour_last_run = date_create(
          date('Y-m-d H:i', intval($this->dbScheduler[$task]['lastrun']))
        );
        $diff = date_diff($now, $hour_last_run);
        if ($diff->h > 22) {
          return true;
        }
      }else{
        return true;
      }
    }
    return false;
  }

  /**
   * @param $task
   */
  private function writeLastRun($task)
  {
    $run_now = date('Y-m-d H:i:s');

    $file = fopen(__DIR__.'/scheduled.json','w+');
    $this->dbScheduler[$task] = [
      "lastrun"=>strtotime($run_now)
    ];
    fwrite($file,json_encode($this->dbScheduler));
    fclose($file);
  }


}
