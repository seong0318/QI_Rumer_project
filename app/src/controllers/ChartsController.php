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
        $this->view->render($response, 'charts_realtime.twig');
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

    public function aqiDataTrans($no2, $o3, $co, $so2, $pm25)
    {
        $aqiAirData = array();
        $airData = array($no2, $o3, $co, $so2, $pm25);
        $aqiGood = array(0, 50);
        $aqiModerate = array(51, 50);
        $aqiUnhealthyFor = array(101, 50);
        $aqiUnhealthy = array(151, 50);
        $aqiVery = array(201, 100);
        $aqiHazardous = array(301, 100);
        $aqi2Hazardous = array(401, 100);

        for ($i = 0; $i < 5; $i++) {
            switch ($i) {
                case '0':
                    if ($airData[$i] < 53) {
                        $clow = 0;
                        $chigh = 53;
                        $resulteAqi = ($aqiGood[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiGood[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 100) {
                        $clow = 54;
                        $chigh = 100;
                        $resulteAqi = ($aqiModerate[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiModerate[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 360) {
                        $clow = 101;
                        $chigh = 360;
                        $resulteAqi = ($aqiUnhealthyFor[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiUnhealthyFor[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 649) {
                        $clow = 361;
                        $chigh = 649;
                        $resulteAqi = ($aqiUnhealthy[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiUnhealthy[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 1249) {
                        $clow = 650;
                        $chigh = 1249;
                        $resulteAqi = ($aqiVery[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiVery[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 1649) {
                        $clow = 1250;
                        $chigh = 1649;
                        $resulteAqi = ($aqiHazardous[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiHazardous[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 2049) {
                        $clow = 1650;
                        $chigh = 2049;
                        $resulteAqi = ($aqi2Hazardous[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqi2Hazardous[0];
                        $aqiAirData[$i] = $resulteAqi;
                    }
                    break;
                case '1':
                    if ($airData[$i] < 54) {
                        $clow = 0;
                        $chigh = 54;
                        $resulteAqi = ($aqiGood[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiGood[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 124) {
                        $clow = 55;
                        $chigh = 124;
                        $resulteAqi = ($aqiModerate[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiModerate[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 164) {
                        $clow = 125;
                        $chigh = 164;
                        $resulteAqi = ($aqiUnhealthyFor[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiUnhealthyFor[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 204) {
                        $clow = 165;
                        $chigh = 204;
                        $resulteAqi = ($aqiUnhealthy[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiUnhealthy[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 404) {
                        $clow = 205;
                        $chigh = 404;
                        $resulteAqi = ($aqiVery[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiVery[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 504) {
                        $clow = 405;
                        $chigh = 504;
                        $resulteAqi = ($aqiHazardous[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiHazardous[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 604) {
                        $clow = 505;
                        $chigh = 604;
                        $resulteAqi = ($aqi2Hazardous[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqi2Hazardous[0];
                        $aqiAirData[$i] = $resulteAqi;
                    }
                    break;
                case '2':
                    //co
                    if ($airData[$i] < 4.4) {
                        $clow = 0;
                        $chigh = 4.4;
                        $resulteAqi = ($aqiGood[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiGood[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 9.4) {
                        $clow = 4.5;
                        $chigh = 9.4;
                        $resulteAqi = ($aqiModerate[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiModerate[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 12.4) {
                        $clow = 9.5;
                        $chigh = 12.4;
                        $resulteAqi = ($aqiUnhealthyFor[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiUnhealthyFor[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 15.4) {
                        $clow = 12.5;
                        $chigh = 15.4;
                        $resulteAqi = ($aqiUnhealthy[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiUnhealthy[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 30.4) {
                        $clow = 15.5;
                        $chigh = 30.4;
                        $resulteAqi = ($aqiVery[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiVery[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 40.4) {
                        $clow = 30.5;
                        $chigh = 40.4;
                        $resulteAqi = ($aqiHazardous[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiHazardous[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 50.4) {
                        $clow = 40.5;
                        $chigh = 50.5;
                        $resulteAqi = ($aqi2Hazardous[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqi2Hazardous[0];
                        $aqiAirData[$i] = $resulteAqi;
                    }
                    break;
                case '3':
                    //so2
                    if ($airData[$i] < 35) {
                        $clow = 0;
                        $chigh = 35;
                        $resulteAqi = ($aqiGood[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiGood[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 75) {
                        $clow = 36;
                        $chigh = 75;
                        $resulteAqi = ($aqiModerate[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiModerate[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 185) {
                        $clow = 76;
                        $chigh = 185;
                        $resulteAqi = ($aqiUnhealthyFor[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiUnhealthyFor[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 304) {
                        $clow = 186;
                        $chigh = 304;
                        $resulteAqi = ($aqiUnhealthy[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiUnhealthy[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 604) {
                        $clow = 305;
                        $chigh = 604;
                        $resulteAqi = ($aqiVery[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiVery[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 804) {
                        $clow = 605;
                        $chigh = 804;
                        $resulteAqi = ($aqiHazardous[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiHazardous[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 1004) {
                        $clow = 805;
                        $chigh = 1004;
                        $resulteAqi = ($aqi2Hazardous[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqi2Hazardous[0];
                        $aqiAirData[$i] = $resulteAqi;
                    }
                    break;
                case '4':
                    //pm25
                    if ($airData[$i] < 54) {
                        $clow = 0;
                        $chigh = 54;
                        $resulteAqi = ($aqiGood[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiGood[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 154) {
                        $clow = 55;
                        $chigh = 154;
                        $resulteAqi = ($aqiModerate[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiModerate[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 254) {
                        $clow = 155;
                        $chigh = 254;
                        $resulteAqi = ($aqiUnhealthyFor[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiUnhealthyFor[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 354) {
                        $clow = 255;
                        $chigh = 354;
                        $resulteAqi = ($aqiUnhealthy[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiUnhealthy[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 424) {
                        $clow = 355;
                        $chigh = 424;
                        $resulteAqi = ($aqiVery[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiVery[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 504) {
                        $clow = 425;
                        $chigh = 504;
                        $resulteAqi = ($aqiHazardous[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqiHazardous[0];
                        $aqiAirData[$i] = $resulteAqi;
                    } elseif ($airData[$i] < 604) {
                        $clow = 505;
                        $chigh = 604;
                        $resulteAqi = ($aqi2Hazardous[1] * ($airData[$i] - $clow) / $chigh - $clow) + $aqi2Hazardous[0];
                        $aqiAirData[$i] = $resulteAqi;
                    }
                    break;
                default:
                    echo "몬가 잘못됨";
                    break;
            }
            if ($i == 4) {
                return $aqiAirData;
            }
        }
    }
}
