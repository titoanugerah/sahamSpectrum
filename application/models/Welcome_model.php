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
    $url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=".$stock."&apikey=QT2PLXB57HD123EU&datatype=csv";
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
    if ($stock=="null") {
      $data['view_name'] = 'percobaan0';
    } else {
      $data['list'] = $this->getStock($stock);
//      var_dump($data['list']['row'][2]);die;
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
    for ($i=1; $i <$data['count']; $i++) {
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$i, $data['csv']);
    }

    //FORMATING
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=stock.xls");
    header('Cache-Control: max-age=0');
    header ('Expires: Mon, 26 Jul 2019 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    return true;
  }

}
 ?>
