<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SensorDeregistController extends BaseController {
    public function sensorDelete($sensorId) {
        /** sensor_id 값으로 sensor를 삭제함
         ** 정상일 경우 0, sql 에러일 경우 -1, 삭제가 안될 경우 -2, 그 외의 -3을 반환
         */
        $sql = "delete from sensor where sensor_id = :sensorId";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['sensorId' => $sensorId];
        if (!$stmt->execute($params)) return -1;

        $numDeletedRow = $stmt->rowCount();

        if ($numDeletedRow == 1) return 0;
        else if ($numDeletedRow == 0) return -2;
        else return -3;
    }

    public function sensorDeregist(Request $request, Response $response, $args) {
        $execResult = $this->sensorDelete($_POST['sensor_id']);
        echo json_encode(array('result' => $execResult));
        return;
    }
}
