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
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1','HIGH')
    ->setCellValue('B1','LOW')
    ->setCellValue('C1','CLOSE')
    ->setCellValue('D1','VOLUME')
    ->setCellValue('E1','PP')
    ->setCellValue('F1','R1')
    ->setCellValue('G1','R2')
    ->setCellValue('H1','R3')
    ->setCellValue('I1','S1')
    ->setCellValue('J1','S2')
    ->setCellValue('K1','S3')
    ->setCellValue('L1','CLASS')
    ;


    //var_dump(($data['csv']));die;
    for ($i=2; $i < $data['count']; $i++) {
      $date = $data['row'][$i][0];
      //var_dump($data['row'][$i][0]);die;
      $open = floatval(number_format($data['row'][$i][1],2));
       $high = floatval(number_format($data['row'][$i][2],2));
        $low = floatval(number_format($data['row'][$i][3],2));
        $close = floatval(number_format($data['row'][$i][4],2));
        $volume = ((int)$data['row'][$i][5]);
//        var_dump(floatval(number_format($data['row'][$i][1],2)));die;

      //Parsing Data Into Excel
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$i,$high)
      ->setCellValue('B'.$i,$low)
      ->setCellValue('C'.$i,$close)
      ->setCellValue('D'.$i,$volume)
      ;


      //Orange Indicator
      $PP = ($high + $low + $close)/3;
      $R1 = (2*$PP) - $low;
      $R2 = $PP+($high-$low);
      $R3 = $high + (2*($PP-$low));
      $S1 = (2*$PP) - $high;
      $S2 = $PP - ($high - $low);
      $S3 = $low - (2*($high - $PP));
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('E'.$i,$PP)
      ->setCellValue('F'.$i,$R1)
      ->setCellValue('G'.$i,$R2)
      ->setCellValue('H'.$i,$R3)
      ->setCellValue('I'.$i,$S1)
      ->setCellValue('J'.$i,$S2)
      ->setCellValue('K'.$i,$S3)

      ;

      if ($i>4) {
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('L'.$i,'HOLD');
      }

    }

    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('L2','SELL')
    ->setCellValue('L3','BUY')
    ->setCellValue('L4','HOLD')  ;




//    $a = $objPHPExcel->getActiveSheet()->getCell('B8')->getValue();
//    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A101', $a);

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
