<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class MapController extends BaseController {
    public function getAirDataList($usn) {
        /** usn으로 air data의 sensor id 별 가장 최신의 
         ** 모든 column 내용을 가져옴
         */
        $sql = "select *
        from air_data
        join (
            select any_value(b.air_data_id) as result_id
            from (select *
                  from sensor natural join air_data as a
                  order by a.measured_time desc
            ) as b
            where usn = :usn
            group by b.sensor_id
        ) as c
        on air_data_id = c.result_id";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = ['usn' => $usn];
        if (!$stmt->execute($params)) return -1;
        $execResult = $stmt->fetchall();
        return $execResult;
    }

    public function map(Request $request, Response $response, $args) {
        $this->view->render($response, 'map.twig');
        return $response;
    }

    public function mapHandle(Request $request, Response $response, $args) {
        /** 정상일 경우 result => 0, data => air data 값 전부, 
         ** 로그인 되어 있지 않는 경우 result => -5 반환
         */
        $isDevice = $args['isDevice'];
        
        if ($isDevice == 0)
            $usn = $_SESSION['usn'];
        else if ($isDevice == 1)
            $usn = $_GET['usn'];
        else {
            echo json_encode(array('result' => -5));
            return;
        }
        
        $resultExec = $this->getAirDataList($usn);
        echo json_encode(array(
            'result' => 0, 
            'data' => $resultExec));
        return;
    }
}
