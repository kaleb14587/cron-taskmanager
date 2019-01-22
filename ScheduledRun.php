<?php
/**
 * Created by kaleb.
 * User: kaleb
 * Date: 21/01/19
 * Time: 11:20
 */

try{

  $kernelScheduler = new \Jobs\Kernel();
  $kernelScheduler ->runJobs();
}catch (\Exception $e){
  $run_now = date('Y-m-d H:i:s');

  $file = fopen(__DIR__.'/error.log','a+');
  fwrite($file,"\r\n[$run_now] error running file: ".
    $e->getFile()." (line ".$e->getLine().") : message".
    $e->getMessage());
  fclose($file);
}
