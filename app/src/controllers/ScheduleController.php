<?php

namespace App\Controller;

final class ScheduleController extends BaseController {
    public function schedule($usn) {
        /* mike가 알려준 것을 이해한 대로 일단 만들어보았음
        ** event schedule을 구현한 것
        */
        $sql = "
        create event if not exists delete_temp_data
            on schedule 
                every 1 second
            do BEGIN
                delete from user where DATE_SUB(NOW(), interval 1 minute) > register_date and verify_state = 0;
                delete from temp_user where DATE_SUB(NOW(), interval 1 minute) > register_date;
            END;
        ";
        $stmt = $this->em->getConnection()->prepare($sql);
        if (!$stmt->execute()) {
            echo json_encode(array('result' => -1));
            return;
        }

        echo json_encode(array('result' => 0));
        return;
    }
}
