<?php
/**
 * Created by kaleb.
 * User: kaleb
 * Date: 21/01/19
 * Time: 11:21
 */

namespace Jobs\Scheduled;


use Jobs\Job;

abstract class KernelScheduled
{
  /**
   * @var array
   */
  public $jobs= [];
  /**
   * @var
   */
  private $scheduled;

  /**
   * @param Schedule $schedule
   */
  abstract protected function scheduled(&$schedule);

  /**
   *
   */
  public function runJobs(){
    $this->scheduled = new Schedule();
    $this->scheduled( $this->scheduled);
    $tasksRunning = $this->scheduled->prepareRun();

    foreach($tasksRunning as $job){
      /** @var Job $task */
      $task =  new $this->jobs[$job]();
      $task->run();
    }

  }
}
