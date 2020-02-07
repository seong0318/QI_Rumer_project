<?php
function heatData()
{
    /*  사용자의 이름으로 중복된 ID가 존재하는지 확인
        **  반환값은 찾은 ID 수로 반환
        */
    $sql = "select * from heatData";
    $stmt = $this->em->getConnection()->prepare($sql);
    $params['username'] = $username;
    if (!$stmt->execute($params)) return -1;
    $result = $stmt->fetch();
    return $result['count(usn)'];
}
