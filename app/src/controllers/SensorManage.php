<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SensorManage extends BaseController
{
    /** mac으로 장치의 사용자를 찾음
     ** 정상일 경우 usn, sql 오류일 경우 -1 반환
     */
    private function sensorDuplicateCheck($mac)
    {
        $sql = "select usn from sensor where mac_address = :mac";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['mac' => $mac];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetch();
        return $execResult;
    }

    private function sensorInsert($name, $type, $mac, $usn)
    {
        /** 새로운 센서 등록
         ** 정상일 경우 0, sql 에러일 경우 -1, 삽입이 안될 경우 -2
         */
        $sql = "insert into sensor (sensor_name, type, mac_address, usn) values (:name, :type, :mac, :usn)";

        $stmt = $this->em->getConnection()->prepare($sql);

        $params = [
            'name' => $name,
            'type' => $type,
            'mac' => $mac,
            'usn' => $usn
        ];

        if (!$stmt->execute($params)) return -1;

        $numInsertedRow = $stmt->rowCount();

        if ($numInsertedRow == 0) return -2;

        return 0;
    }

    private function sensorDelete($sensorId)
    {
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

    public function sensorDeregist(Request $request, Response $response, $args)
    {
        $execResult = $this->sensorDelete($_POST['sensor_id']);

        echo json_encode(array('result' => $execResult));
        return;
    }

    public function sensorRegist(Request $request, Response $response, $args)
    {
        /** 새로운 센서 등록
         ** 정상일 경우 0, sql 에러일 경우 -1, 삽입이 안될 경우 -2, 이미 등록된 장치일 경우 -3을 반환
         */
        $isDup = $this->sensorDuplicateCheck($_POST['mac']);

        if ($isDup != 0) {
            echo json_encode(array('result' => -3));
            return;
        }

        $execResult = $this->sensorInsert($_POST['name'], $_POST['type'], $_POST['mac'], $_POST['usn']);

        echo json_encode(array('result' => $execResult));
        return;
    }
}
