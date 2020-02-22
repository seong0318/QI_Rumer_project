<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ChartsController extends BaseController
{
    public function getAllAirDataList($usn)
    {
        /** usn으로 air data의 모든 column 내용을 가져옴
         ** 
         */
        $sql = "select * from sensor natural join aqi_data where usn = :usn";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['usn' => $usn];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetchall();
        return $execResult;
    }

    public function getAirDataList($usn, $sensorId, $startTime, $endTime)
    {
        /** usn과 sensor_id로 모은 column 내용을 가져옴
         **
         */
        $sql = "select * 
        from sensor natural join aqi_data 
        where usn = :usn and sensor_id = :sensor_id and date(measured_time) between :start and :end";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'usn' => $usn,
            'sensor_id' => $sensorId,
            'start' => $startTime,
            'end' => $endTime
        ];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetchall();

        return $execResult;
    }

    public function charts(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'charts.twig');
        return $response;
    }

    public function chartshistory(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'charts_history.twig');
        return $response;
    }

    public function chartsHandle(Request $request, Response $response, $args)
    {
        /** sensorId가 0일 경우 모든 sensorId 선택한 것, 음수일 경우 잘못된 값 입력
         ** 이외에는 각 값이 sensor_id 
         ** 정상일 경우 result => 0, data => air data 값 전부, 
         ** 로그인 되어 있지 않는 경우 result => -5,
         ** sensor_id가 잘못 입력될 경우 result => -2 반환
         */
        $isDevice = $args['isDevice'];
        $sensorId = $_POST['sensor_id'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];

        if ($isDevice == 0)
            $usn = $_SESSION['usn'];
        else if ($isDevice == 1)
            $usn = $_POST['usn'];
        else {
            echo json_encode(array('result' => -5));
            return;
        }

        if ($sensorId == 0) {
            $resultExec = $this->getAllAirDataList($usn);
            if ($resultExec == -1) {
                echo json_encode(array('result' => -1));
                return;
            }
        } else if ($sensorId < 0) {
            echo json_encode(array('result' => -2));
            return;
        } else {
            $resultExec = $this->getAirDataList($usn, $sensorId, $startTime, $endTime);

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
}
