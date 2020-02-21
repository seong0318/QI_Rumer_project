<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class InsertSensorData extends BaseController {
    /** 앱에 연결된 polar sensor에서 받아온 값을 DB에 저장 
     ** 정상일 경우 0, sql 에러일 경우 -1, primary key 중복일 경우 -2, insert된 개수가 1이 아닐 경우 -3 반환
     */
    private function insertSql($lat, $lng, $hr, $rr, $usn, $mac) {
        $sql = "insert into heart_data (measured_time, latitude, longtitude, heart_rate, rr_interval, usn, sensor_id)
        select NOW(), :lat, :lng, :hr, :rr, :usn, sensor_id
        from sensor
        where mac_address = :mac;";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'lat' => $lat,
            'lng' => $lng,
            'hr' => $hr,
            'rr' => $rr,
            'usn' => $usn,
            'mac' => $mac
        ];
        try {
            if ($stmt->execute($params)) return 0;
            else return -1;
        }
        catch (UniqueConstraintViolationException $e){
            return -2;
        }

        $updatedRowNum = $stmt->rowCount();
        if ($updatedRowNum != 1) return -3;
        
        return 0;
    }

    public function insertPolarData(Request $request, Response $response, $args) {
        $execResult = $this->insertSql($_POST['lat'], $_POST['lng'], $_POST['hr'], $_POST['rr'], $_Post['usn'], $_POST['mac']);
        
        echo json_encode(array('result' => $execResult));
        return;
    }
}
