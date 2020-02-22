<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

final class InsertSensorData extends BaseController
{
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
                    // error
                    return -1;
            }
            if ($i == 4) {
                return $aqiAirData;
            }
        }
    }

    private function polarSql($lat, $lng, $hr, $rr, $mac)
    {
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
        } catch (UniqueConstraintViolationException $e) {
            return -2;
        }

        $updatedRowNum = $stmt->rowCount();
        if ($updatedRowNum != 1) return -3;

        return 0;
    }

    private function insertAirData($temp, $no2, $o3, $co, $so2, $pm25, $mac)
    {
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
        } catch (UniqueConstraintViolationException $e) {
            return -2;
        }

        $updatedRowNum = $stmt->rowCount();
        if ($updatedRowNum != 1) return -3;

        $lastInsertId = $this->em->getConnection()->query("SELECT LAST_INSERT_ID() AS id")->fetch();

        return $lastInsertId['id'];
    }

    private function insertAqiData($lat, $lng, $mac, $temp, $no2, $o3, $co, $so2, $pm25, $airDataId)
    {
        /** 앱에 연결된 Udoo sensor에서 받아온 값을 통해 AQI 값을 계산 후 DB에 저장 
         ** 정상일 경우 삽입된 데이터의 0, sql 에러일 경우 -1, primary key 중복일 경우 -2, 
         ** insert된 개수가 1이 아닐 경우 -3(mac address 오류) 반환
         */
        $aqiArr = $this->aqiDataTrans($no2, $o3, $co, $so2, $pm25);

        $sql = "insert into aqi_data (measured_time, latitude, longitude, co, so2, o3, no2, pm25, temperature, air_data_id, sensor_id)
        select NOW(), :lat, :lng, :co, :so2, :o3, :no2, :pm25, :temp, :air_data_id, sensor_id
        from sensor
        where mac_address = :mac";
        $stmt = $this->em->getConnection()->prepare($sql);
        $params = [
            'lat' => $lat,
            'lng' => $lng,
            'co' => $aqiArr[2],
            'so2' => $aqiArr[3],
            'o3' => $aqiArr[1],
            'no2' => $aqiArr[0],
            'pm25' => $aqiArr[4],
            'temp' => $temp,
            'air_data_id' => $airDataId,
            'mac' => $mac
        ];
        try {
            if (!$stmt->execute($params)) return -1;
        } catch (UniqueConstraintViolationException $e) {
            return -2;
        }

        $updatedRowNum = $stmt->rowCount();
        if ($updatedRowNum != 1) return -3;

        return 0;
    }

    public function insertPolarData(Request $request, Response $response, $args)
    {
        $execResult = $this->polarSql($_POST['lat'], $_POST['lng'], $_POST['hr'], $_POST['rr'], $_POST['mac']);

        echo json_encode(array('result' => $execResult));
        return;
    }

    public function insertUdooData(Request $request, Response $response, $args)
    {
        /** raw air data와 aqi 를 저장함
         ** 정상일 경우 0, 마지막으로 삽입된 id 값이 이상(음수)할 경우 바로 종료
         ** 그 외의 insertAqiData와 에러 공유
         */
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $temp = $_POST['temp'];
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

        $execResult = $this->insertAqiData($lat, $lng, $mac, $temp, $no2, $o3, $co, $so2, $pm25, $lastInsertId);
        echo json_encode(array('result' => $execResult));
        return;
    }
}
