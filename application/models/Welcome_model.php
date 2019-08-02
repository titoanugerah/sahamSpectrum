<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome_model extends CI_Model {

  public function __construct()
  {
    $this->load->library('Excel');
  }

  public function getAllData($table)
  {
    return $this->db->get($table)->result();
  }

  public function updateData($table, $whereVar, $whereVal, $setVar, $setVal)
  {
    $this->db->where($where = array($whereVar => $whereVal));
    return $this->db->update($table, $data  = array($setVar => $setVal));
  }

  public function getNumRow($table, $whereVar, $whereVal)
  {
    return $this->db->get_where($table, $where = array($whereVar => $whereVal))->num_rows();
  }

  public function deleteData($table, $whereVar, $whereVal)
  {
    return $this->db->delete($table, $where = array($whereVar => $whereVal));
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

  public function uploadFile($filename)
  {
    $config['upload_path'] = realpath('.');
    $config['overwrite'] = TRUE;
    $config['file_name']     = $filename.'.model';
    $config['allowed_types'] = '*';
    $this->load->library('upload', $config);
    if (!$this->upload->do_upload('fileUpload')) {
      $upload['status']=0;
      $upload['message']= "Mohon maaf terjadi error saat proses upload : ".$this->upload->display_errors();
    } else {
      $upload['status']=1;
      $upload['message'] = "File berhasil di upload";
    }
    return $upload;
  }

  public function downloadStock($stock)
  {
    redirect('http://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol='.$stock.'&apikey=QT2PLXB57HD123EU&datatype=csv');
  }

  public function downloadStock1($stock)
  {
    $objPHPExcel = new PHPExcel();
    $x=1; $class = array('BUY', 'HOLD', 'SELL');
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

    $dtest = array();
    $dtest[0] = ('High,Low,Close,Volume,PP,R1,R2,R3,S1,S2,S3,CLASS');

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

      $dtest[$x] = ($high.','.$low.','.$close.','.$volume.','.$PP.','.$R1.','.$R2.','.$R3.','.$S1.','.$S2.','.$S3.','.$class[rand(0,2)]);
      $x++;

    }
    //  var_dump($dtest);die;

    //combak
    // $data = array ('left-weight,left-distance,right-weight,right-distance,class','5,1,3,2,L','4,2,3,1,B','3,5,2,1,R','4,5,1,3,?');
    //save fille .csv
    $fp = fopen('balance_csv.csv', 'w');
    foreach($dtest as $line){
      $val = explode(",",$line);
      fputcsv($fp, $val);
    }
    fclose($fp);//end

    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('L2','SELL')
    ->setCellValue('L3','BUY')
    ->setCellValue('L4','HOLD')  ;





    //    $a = $objPHPExcel->getActiveSheet()->getCell('B8')->getValue();
    //    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A101', $a);

    //FORMATING
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header("Content-Disposition: attachment; filename=stock.csv");
    //     header('Cache-Control: max-age=0');
    //     header ('Expires: Mon, 26 Jul 2019 05:00:00 GMT'); // Date in the past
    //     header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    //     header ('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
    //     header ('Pragma: public'); // HTTP/1.0
    //     $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'csv');
    //
    // //    $objWriter->save(base_url('./assets/stock/'.$stock.'.csv'));
    //   //  $objWriter->save('php://output');
    return true;
  }


  public function downloadStock2($stock)
  {
    $objPHPExcel = new PHPExcel();
    $x=1; $class = array('BUY', 'HOLD', 'SELL');
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

    $dtest = array();
    $dtest[0] = ('HIGH,LOW,CLOSE,VOLUME,PP,R1,R2,R3,S1,S2,S3,CLASS');

    //var_dump(($data['csv']));die;
    for ($i=2; $i < 5; $i++) {
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

      $dtest[$x] = ($high.','.$low.','.$close.','.$volume.','.$PP.','.$R1.','.$R2.','.$R3.','.$S1.','.$S2.','.$S3.','.$class[$x-1]);
      $x++;

    }
    //  var_dump($dtest);die;

    //combak
    // $data = array ('left-weight,left-distance,right-weight,right-distance,class','5,1,3,2,L','4,2,3,1,B','3,5,2,1,R','4,5,1,3,?');
    //save fille .csv
    $fp = fopen('balance_csv.csv', 'w');
    foreach($dtest as $line){
      $val = explode(",",$line);
      fputcsv($fp, $val);
    }
    fclose($fp);//end

    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('L2','SELL')
    ->setCellValue('L3','BUY')
    ->setCellValue('L4','HOLD')  ;





    //    $a = $objPHPExcel->getActiveSheet()->getCell('B8')->getValue();
    //    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A101', $a);

    //FORMATING
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header("Content-Disposition: attachment; filename=stock.csv");
    //     header('Cache-Control: max-age=0');
    //     header ('Expires: Mon, 26 Jul 2019 05:00:00 GMT'); // Date in the past
    //     header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    //     header ('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
    //     header ('Pragma: public'); // HTTP/1.0
    //     $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'csv');
    //
    // //    $objWriter->save(base_url('./assets/stock/'.$stock.'.csv'));
    //   //  $objWriter->save('php://output');
    return true;
  }

  public function getPrediction($stock)
  {
    $this->downloadStock1($stock);
    $this->updateData('stock', 'stock_code', $stock, 'prediction',prediction($stock));
  }

  public function refreshPrediction()
  {
    foreach ($this->getAllData('stock') as $item) {
      $this->getPrediction($item->stock_code);
    }
  }

  public function cPrediction($notification)
  {
    $data['title'] = 'Prediksi Saham';
    $data['view_name'] = 'listStockPrediction';

    $data['stock'] = $this->getAllData('stock');
    $data['notification'] = 'no';
    return $data;
  }

  public function addStock()
  {
    $upload = $this->uploadFile($this->input->post('stock_code'));
    $data = array(
    'stock_name' => $this->input->post('stock_name'),
    'stock_type' => $this->input->post('stock_type'),
    'stock_code' => $this->input->post('stock_code'),
    'model_file' => $this->input->post('stock_code').'.model'
    );
    if($this->getNumRow('stock', 'stock_code', $this->input->post('stock_code'))==0 && $upload['status']==1){return $this->db->insert('stock', $data);}
    else{return false;}
  }

  public function deleteStock($id)
  {
    $this->deleteData('stock', 'id', $id);
  }

}
?>
