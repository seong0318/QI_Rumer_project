<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SensorListController extends BaseController {
    public function getSensorList($usn, $type) {
        /** usn으로 Sensor의 모든 column 내용을 가져옴
         ** json의 배열 형식으로 반환
         */
        $sql = "select * from sensor where usn = :usn and type = :type";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'usn' => $usn,
            'type' => $type    
        ];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetchall();
        return $execResult;
    }

    public function getAllSensorList($usn) {
        $sql = "select * from sensor where usn = :usn";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['usn' => $usn];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetchall();
        return $execResult;
    }

    public function sensorList(Request $request, Response $response, $args) {
        $this->view->render($response, 'sensor_list.twig');
        return $response;
    }

    public function sensorListHandle(Request $request, Response $response, $args) {
        /** 정상일 경우 result => 0, data => sensor 값 전부, 
         ** 로그인 되어 있지 않는 경우 result => -5 반환
         */
        $isDevice = $args['isDevice'];
        $type = $_GET['type'];
        
        if ($isDevice == 0)
            $usn = $_SESSION['usn'];
        else if ($isDevice == 1)
            $usn = $_GET['usn'];
        else {
            echo json_encode(array('result' => -5));
            return;
        }

        if ($type == 2) {
            $resultExec = $this->getAllSensorList($usn);
            if ($resultExec == -1) {
                echo json_encode(array('result' => -1));
                return;
            }
        }
        else {
            $resultExec = $this->getSensorList($usn, $type);
            if($resultExec == -1) {
                echo json_encode(array('result' => -1));
                return;
            }
        }

        echo json_encode(array(
            'result' => 0, 
            'data' => $resultExec));
        return;
    }
}
