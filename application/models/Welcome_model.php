<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome_model extends CI_Model {

  public function __construct()
  {
    $this->load->library('Excel');
  }

  public function getStock($stock)
  {
    //AAON
    $url = "https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol=".$stock."&interval=5min&apikey=QT2PLXB57HD123EU&datatype=csv";
    $data['csv'] = explode("\n",file_get_contents($url));
    for ($i=0; $i < (count($data['csv'])-1); $i++) {
      $data['row'][$i] = explode(",",$data['csv'][$i]);
      $data['count'] = $i;
    }
    return $data;
  }

  public function getContent($stock)
  {
    $data['title'] = 'Saham';
    if ($stock=="") {
      $data['view_name'] = 'percobaan0';
    } else {
      $data['list'] = $this->getStock($stock);
//      var_dump($data['list']['row'][2]);die;
      $data['stock'] = $stock;
      $data['view_name'] = 'percobaan1';
      $data['title'] = 'Data Saham '.$stock;
    }
    $data['notification'] = 'no';
    return $data;
  }

  public function downloadStock($stock)
  {
    redirect('http://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol='.$stock.'&apikey=QT2PLXB57HD123EU&datatype=csv');
  }

  public function downloadStock1($stock)
  {
    $objPHPExcel = new PHPExcel();

    //INFO AND DETAILS
    $objPHPExcel->getProperties()
    ->setCreator("Tito Anugerah")
    ->setLastModifiedBy("Tito Anugerah")
    ->setTitle("Contoh Stock")
    ->setSubject($stock)
    ->setDescription("Template Stock")
    ->setKeywords("Tekkom")
    ->setCategory("private");

    $data = $this->getStock($stock);
//    var_dump($data['csv']);die;
    for ($i=0; $i < $data['count']; $i++) {
      $f = $data['row'][$i][5];
      $g = sprintf("%.2f", ($data['row'][1][2]-$data['row'][1][4]));
      //var_dump($g);die;
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$i,$data['row'][$i][0])
      ->setCellValue('B'.$i,$data['row'][$i][1])
      ->setCellValue('C'.$i,$data['row'][$i][2])
      ->setCellValue('D'.$i,$data['row'][$i][3])
      ->setCellValue('E'.$i,$data['row'][$i][4])
      ->setCellValue('F'.$i,$f)
      ->setCellValue('G'.$i,$g)

      ;

    }

    /*
    $i = 1;
    foreach ($data['csv'] as $item) {
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$i, str_replace("/n", "", strval($item)));
      $i++;
    }
    */

    //FORMATING
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=stock.csv");
    header('Cache-Control: max-age=0');
    header ('Expires: Mon, 26 Jul 2019 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'csv');
//    $objWriter->save(base_url('./assets/stock/'.$stock.'.csv'));
    $objWriter->save('php://output');
    return true;
  }

}
 ?>
