<?php
/**
 * Created by kaleb.
 * User: kaleb
 * Date: 21/01/19
 * Time: 10:50
 */
namespace Jobs;

abstract class Job
{

  /**
   * @return mixed
   */
  public abstract function run();

  /**
   * Job constructor.
   */
  public function __construct(){}
}
