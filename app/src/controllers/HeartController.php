<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HeartController extends BaseController
{
    public function getAllHeartDataList($usn)
    {
        /** usn으로 heart data의 모든 column 내용을 가져옴
         ** 
         */
        $sql = "select * from sensor natural join heart_data where usn = :usn";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['usn' => $usn];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetchall();
        return $execResult;
    }

    public function getHeartDataList($usn, $sensorId)
    {
        /** usn과 sensor_id로 모은 column 내용을 가져옴
         **
         */
        $sql = "select * from sensor natural join heart_data where usn = :usn and sensor_id = :sensor_id";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'usn' => $usn,
            'sensor_id' => $sensorId
        ];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetchall();
        return $execResult;
    }


    public function hearthistory(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'heart_history.twig');
        return $response;
    }
    public function heartrealtime(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'heart_realtime.twig');
        return $response;
    }

    public function heartHandle(Request $request, Response $response, $args)
    {
        /** sensorId가 0일 경우 모든 sensorId 선택한 것, 음수일 경우 잘못된 값 입력
         ** 이외에는 각 값이 sensor_id 
         ** 정상일 경우 result => 0, data => air data 값 전부, 
         ** 로그인 되어 있지 않는 경우 result => -5,
         ** sensor_id가 잘못 입력될 경우 result => -2 반환
         */
        $isDevice = $args['isDevice'];
        $sensorId = $_POST['sensor_id'];

        if ($isDevice == 0)
            $usn = $_SESSION['usn'];
        else if ($isDevice == 1)
            $usn = $_GET['usn'];
        else {
            echo json_encode(array('result' => -5));
            return;
        }

        if ($sensorId == 0) {
            $resultExec = $this->getAllHeartDataList($usn);
            if ($resultExec == -1) {
                echo json_encode(array('result' => -1));
                return;
            }
        } else if ($sensorId < 0) {
            echo json_encode(array('result' => -2));
            return;
        } else {
            $resultExec = $this->getHeartDataList($usn, $sensorId);
            if ($resultExec == -1) {
                echo json_encode(array('result' => -1));
                return;
            }
        }

        echo json_encode(array(
            'result' => 0,
            'data' => $resultExec
        ));
        return;
    }
    public function getRecentlyHeartDataList($usn, $sensorId)
    {
        /** usn으로 heart data의 sensor id 별 가장 최신의 
         ** 모든 column 내용을 가져옴
         */
        $sql = "select *
                from heart_data natural join sensor
                where usn = :usn and sensor_id = :sensor_id
                order by measured_time desc
                limit 1";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'usn' => $usn,
            'sensor_id' => $sensorId
        ];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetchall();
        return $execResult;
    }
    public function heartRealTimeHandle(Request $request, Response $response, $args)
    {
        /** 정상일 경우 result => 0, data => air data 값 전부, 
         ** 로그인 되어 있지 않는 경우 result => -5 반환
         */
        $isDevice = $args['isDevice'];
        $sensorId = $_POST['sensor_id'];

        if ($isDevice == 0)
            $usn = $_SESSION['usn'];
        else if ($isDevice == 1)
            $usn = $_POST['usn'];
        else {
            echo json_encode(array('result' => -5));
            return;
        }

        $resultExec = $this->getRecentlyHeartDataList($usn, $sensorId);
        echo json_encode(array(
            'result' => 0,
            'data' => $resultExec
        ));
        return;
    }
}
