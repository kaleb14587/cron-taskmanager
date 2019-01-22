<?php
/**
 * Created by Kaleb .
 * User: kaleb
 * Date: 21/01/19
 * Time: 10:49
 */

namespace Jobs;


use Jobs\Scheduled\KernelScheduled;
use Jobs\Scheduled\Schedule;

class Kernel extends KernelScheduled {

  /**
   * @var array
   */
  public $jobs=[
    //add jobs here 
    // Ex.: 'new_job'=>my_job_here::class
  ];

  /**
   * @param Schedule $schedule
   */
  protected function scheduled(&$schedule)
  {
    $schedule->add('new_job')->everyDay()->atHour('12:45');
  }
}
