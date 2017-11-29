<?php       
      $lat = $_GET["lat"];
      $long = $_GET["longs"];
      $lat2 = $_GET["x_lat"];
      $long2 = $_GET["x_long"];
      // $lat2 = 3.5906838;
      // $long2 = 98.6763334;

      $latFrom = deg2rad($lat2);
      $lonFrom = deg2rad($long2);
      $latTo = deg2rad($lat);
      $lonTo = deg2rad($long);
      
      $lonDelta = $lonTo - $lonFrom;
      $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
      $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
      
      $angle = atan2(sqrt($a), $b);
      $jarak = $angle * 6371000;
      $km = ceil($jarak / 1000);
      $ongkirs = ceil($km * 50000);
      
      if($km < 1) {
            $ongkir = 50000;
      } else {
            $ongkir = ceil($km * 50000);
      }


$data = array();
$data['distance'] = $km;
$data['shipping'] = $ongkir;

echo json_encode($data);
exit();


?>