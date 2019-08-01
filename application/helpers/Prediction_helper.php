<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 function prediction($stock)
{
  $ci = &get_instance();
  $cmd = 'java -classpath "weka.jar" weka.core.converters.CSVLoader -N "last" balance_csv.csv > stock.arff ';
  exec($cmd,$output);
  var_dump($output);die;

  $cmd = 'java -classpath "weka.jar" weka.classifiers.trees.J48 -t "stock.arff" -d "'.base_url('./assets/upload/').$stock.'.model" -p 12';
  exec($cmd,$output);

  //var_dump($output);die;
  //$data = explode(":",$output);
   //var_dump($data);die;


  for ($i=5;$i<sizeof($output);$i++)
   {
     $data[$i-5]  = explode(",",preg_replace('/\s+/', ',', trim(preg_replace('@[^A-Z\ ]@', "", $output[$i]))).",<br>");
     $out[$i-5] = $data[$i-5][1];
   }
   return $out[0];
   //var_dump($out[1]);die;

}

 ?>
