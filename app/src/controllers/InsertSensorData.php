<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class InsertSensorData extends BaseController {
    private function polarSql($lat, $lng, $hr, $rr, $mac) {
        /** 앱에 연결된 polar sensor에서 받아온 값을 DB에 저장 
         ** 정상일 경우 0, sql 에러일 경우 -1, primary key 중복일 경우 -2, insert된 개수가 1이 아닐 경우 -3 반환
        */
        $sql = "insert into heart_data (measured_time, latitude, longtitude, heart_rate, rr_interval, usn, sensor_id)
        select NOW(), :lat, :lng, :hr, :rr, usn, sensor_id
        from sensor
        where mac_address = :mac";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'lat' => $lat,
            'lng' => $lng,
            'hr' => $hr,
            'rr' => $rr,
            'mac' => $mac
        ];
        try {
            if (!$stmt->execute($params)) return -1;
        }
        catch (UniqueConstraintViolationException $e){
            return -2;
        }

        $updatedRowNum = $stmt->rowCount();
        if ($updatedRowNum != 1) return -3;
        
        return 0;
    }

    private function insertAirData($temp, $no2, $o3, $co, $so2, $pm25, $mac) {
        /** 앱에 연결된 Udoo sensor에서 받아온 값을 DB에 저장 
         ** 정상일 경우 삽입된 데이터의 id값, sql 에러일 경우 -1, primary key 중복일 경우 -2, insert된 개수가 1이 아닐 경우 -3 반환
        */
        $sql = "insert into air_data (co, so2, o3, no2, pm25, temperature, sensor_id)
        select :co, :so2, :o3, :no2, :pm25, :temp, sensor_id
        from sensor
        where mac_address = :mac";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'co' => $co,
            'no2' => $no2,
            'o3' => $o3,
            'so2' => $so2,
            'pm25' => $pm25,
            'temp' => $temp,
            'mac' => $mac
        ];
        try {
            if (!$stmt->execute($params)) return -1;
        }
        catch (UniqueConstraintViolationException $e){
            return -2;
        }

        $updatedRowNum = $stmt->rowCount();
        if ($updatedRowNum != 1) return -3;

        $lastInsertId = $this->em->getConnection()->query("SELECT LAST_INSERT_ID() AS id")->fetch();
        
        return $lastInsertId['id'];
    }

    private function insertAqiData($lat, $lng, $mac, $temp, $no2, $o3, $co, $so2, $pm25, $airDataId) {
        /** 앱에 연결된 Udoo sensor에서 받아온 값을 통해 AQI 값을 계산 후 DB에 저장 
         ** 정상일 경우 삽입된 데이터의 0, sql 에러일 경우 -1, primary key 중복일 경우 -2, 
         ** insert된 개수가 1이 아닐 경우 -3(mac address 오류) 반환
        */
        $sql = "insert into aqi_data (measured_time, latitude, longitude, co, so2, o3, no2, pm25, temperature, air_data_id, sensor_id)
        select NOW(), :lat, :lng, :co, :so2, :o3, :no2, :pm25, :temp, :air_data_id, sensor_id
        from sensor
        where mac_address = :mac";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'lat' => $lat,
            'lng' => $lng,
            'co' => $co,
            'so2' => $so2,
            'o3' => $o3,
            'no2' => $no2,
            'pm25' => $pm25,
            'temp' => $temp,
            'air_data_id' => $airDataId,
            'mac' => $mac
        ];
        try {
            if (!$stmt->execute($params)) return -1;
        }
        catch (UniqueConstraintViolationException $e){
            return -2;
        }

        $updatedRowNum = $stmt->rowCount();
        if ($updatedRowNum != 1) return -3;
        
        return 0;
    }

    public function insertPolarData(Request $request, Response $response, $args) {
        $execResult = $this->polarSql($_POST['lat'], $_POST['lng'], $_POST['hr'], $_POST['rr'], $_POST['mac']);
        
        echo json_encode(array('result' => $execResult));
        return;
    }

    public function insertUdooData(Request $request, Response $response, $args) {
        /** raw air data와 aqi 를 저장함
         ** 정상일 경우 0, 마지막으로 삽입된 id 값이 이상(음수)할 경우 바로 종료
         ** 그 외의 insertAqiData와 에러 공유
         */
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $temp =$_POST['temp'];
        $no2 = $_POST['no2'];
        $o3 = $_POST['o3'];
        $co = $_POST['co'];
        $so2 = $_POST['so2'];
        $pm25 = $_POST['pm25'];
        $mac = $_POST['mac'];
        
        $lastInsertId = $this->insertAirData($temp, $no2, $o3, $co, $so2, $pm25, $mac);

        if ($lastInsertId < 1) {
            echo json_encode(array('result' => $lastInsertId));
            return;
        }

        $execResult = $this->insertAqiData($lat, $lng, $mac, $temp, $no2, $o3, $co, $so2, $pm25, $airDataId);
        echo json_encode(array('result' => $execResult));
        return;
    }
}
