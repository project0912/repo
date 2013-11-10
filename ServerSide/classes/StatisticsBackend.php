<?

class StatisticsBackend{

    public static function create($type, $elementIDm){

        $collection = StatisticsBackend::getCollection($type);
        if (!$collection) return false;


        $structure = array(
            '_id'                   => $elementIDm,
            FIELD_stats_viewsIP     => array(),
            FIELD_stats_viewsID     => array(),
            FIELD_stats_bookmarksID => array()
        );

        switch($type){
            case 'crisis':
                $structure[FIELD_stats_severity]    = array();
                break;
            case 'claim':
                $structure[FIELD_stats_agreesID]    = array();
                $structure[FIELD_stats_disagreesID] = array();
                break;
            case 'evidence':
                $structure[FIELD_stats_agreesID]    = array();
                $structure[FIELD_stats_disagreesID] = array();
                break;
        }


        MDB::alloc()->{$collection['backend']}->insert($structure);

        return true;
    }


    public static function view($type, $elementIDm){

        $collection = StatisticsBackend::getCollection($type);
        if (!$collection) return false;


        $IP = Helper::getIP();
        $ID = UserCurrent::currentID();

        if ($ID){

            $find = array(
                '_id'                       => $elementIDm,
                FIELD_stats_viewsID         => array(
                    '$ne'                   => $ID
                )
            );
            $update = array(
                '$addToSet'                 => array(
                    FIELD_stats_viewsID     => $ID
                )
            );
        } else {

            $find = array(
                '_id'                       => $elementIDm,
                FIELD_stats_viewsIP         => array(
                    '$ne'                   => $IP
                )
            );
            $update = array(
                '$addToSet'                 => array(
                    FIELD_stats_viewsIP     => $IP
                )
            );
        }


        $res = MDB::alloc()->{$collection['backend']}->update(
            $find,
            $update,
            array('safe'  => true)
        );

        if($res['n'] === 1){

            Statistics::view($collection['frontend'], $elementIDm);
            return true;
        } return false;
    }


    private static function getCollection($type){
        switch($type){
            case 'crisis':
                $backend    = COLL_STATISTICS_CRISIS;
                $frontend   = COLL_CRISIS;
                break;
            case 'claim':
                $backend    = COLL_STATISTICS_CLAIM;
                $frontend   = COLL_CLAIM;
                break;
            case 'evidence':
                $backend    = COLL_STATISTICS_EVIDENCE;
                $frontend   = COLL_EVIDENCE;
                break;
            default:
                return false;
        }
        return array(
            'backend'   => $backend,
            'frontend'  => $frontend
        );
    }


    public static function bookmark($type, $elementID, $undo = false){

        $ID         = UserCurrent::currentID();
        if (!$ID)   return 0;


        $elementIDm = Helper::validMongoID($elementID);
        if (!$elementIDm)  return 0;

        $collection = StatisticsBackend::getCollection($type);
        if (!$collection) return 0;

        if ($undo){

            $find = array(
                '_id'                       => $elementIDm,
                FIELD_stats_bookmarksID     => $ID
            );
            $update = array(
                '$pull'                     => array(
                    FIELD_stats_bookmarksID => $ID
                )
            );
            $diff = -1;
        } else {

            $find = array(
                '_id'                       => $elementIDm,
                FIELD_stats_bookmarksID     => array(
                    '$ne'                   => $ID
                )
            );
            $update = array(
                '$addToSet'                 => array(
                    FIELD_stats_bookmarksID => $ID
                )
            );
            $diff = 1;
        }

        $res = MDB::alloc()->{$collection['backend']}->update(
            $find,
            $update,
            array('safe'  => true)
        );

        if($res['n'] === 1){

            Statistics::bookmark($collection['frontend'], $elementIDm, 'DUMMY', $diff);
            return 1;
        } return 0;
    }


    public static function agreeDisagree($type, $elementID, $agree = true, $undo = false){

        if (!in_array($type, array('claim', 'evidence'))) return 0;


        $ID         = UserCurrent::currentID();
        if (!$ID)   return 0;


        $elementIDm = Helper::validMongoID($elementID);
        if (!$elementIDm)  return 0;


        $collection = StatisticsBackend::getCollection($type);
        if (!$collection) return 0;

        if ($undo){

            $field = ($agree) ? FIELD_stats_agreesID : FIELD_stats_disagreesID;
            $find = array(
                '_id'   => $elementIDm,
                $field  => $ID
            );


            $update = array(
                '$pull'     => array(
                    $field  => $ID
                )
            );


            $diff = -1;
        } else {

            $find = array(
                '_id'                       => $elementIDm,
                FIELD_stats_agreesID        => array(
                    '$ne'                   => $ID
                ),
                FIELD_stats_disagreesID     => array(
                    '$ne'                   => $ID
                )
            );


            $field = ($agree) ? FIELD_stats_agreesID : FIELD_stats_disagreesID;
            $update = array(
                '$addToSet'     => array(
                    $field      => $ID
                )
            );


            $diff = 1;
        }

        $res = MDB::alloc()->{$collection['backend']}->update(
            $find,
            $update,
            array('safe'  => true)
        );

        if($res['n'] === 1){

            Statistics::agreeDisagree($collection['frontend'], $elementIDm, $agree, $diff);
            return 1;
        } return 0;
    }


    public static function castSeverity($crisisID, $severity){
        $severity = (int)$severity;

        if (!Helper::validSeverity($severity)) return 0;

        $crisisIDm = Helper::validCrisisID($crisisID);
        if (!$crisisIDm)  return 0;

        $ID         = UserCurrent::currentID();
        if (!$ID)   return 0;


        $find = array(
            '_id'                                   => $crisisIDm,
            FIELD_stats_severity.'.'.FIELD_authorID => array(
                '$ne'                               => $ID
            )
        );
        $update = array(
            '$push'     => array(
                FIELD_stats_severity    => array(
                    FIELD_authorID              => $ID,
                    FIELD_stats_numSeverity     => $severity
                )
            )
        );
        $res = MDB::alloc()->{COLL_STATISTICS_CRISIS}->update(
            $find,
            $update,
            array('safe' => true)
        );

        if($res['n'] === 1){

            MDB::alloc()->{COLL_CRISIS}->update(
                array(
                    '_id'           => $crisisIDm,
                ),
                array(
                    '$inc'          => array(
                        FIELD_stats.'.'.FIELD_stats_sumSeverity => $severity,
                        FIELD_stats.'.'.FIELD_stats_numSeverity => 1
                    )
                )
            );

            return 1;
        } return 0;
    }

    public static function revokeSeverity($crisisID){

        $crisisIDm = Helper::validCrisisID($crisisID);
        if (!$crisisIDm)  return array('error' => 1);


        $ID         = UserCurrent::currentID();
        if (!$ID)   return array('error' => 1);

        $res = MDB::alloc()->{COLL_STATISTICS_CRISIS}->findOne(
            array(
                '_id'                                   => $crisisIDm,
                FIELD_stats_severity.'.'.FIELD_authorID => $ID
            ),
            array(
                '_id'                                   => 0,
                FIELD_stats_severity                    => 1
            )
        );
        if (!$res) return array('error' => 1);;
        foreach($res[FIELD_stats_severity] as $i) {
            if ($i[FIELD_authorID] == $ID) {
                $vote = $i[FIELD_stats_numSeverity];
                break;
            }
        }
        if (!isset($vote)) return array(
            'error' => 1
        );

        MDB::alloc()->{COLL_STATISTICS_CRISIS}->update(
            array(
                '_id'                                   => $crisisIDm,
                FIELD_stats_severity.'.'.FIELD_authorID => $ID
            ),
            array(
                '$pull'                                 => array(
                    FIELD_stats_severity                => array(
                        FIELD_authorID                  => $ID
                    )
                )
            )
        );

        MDB::alloc()->{COLL_CRISIS}->update(
            array(
                '_id'           => $crisisIDm,
            ),
            array(
                '$inc'          => array(
                    FIELD_stats.'.'.FIELD_stats_sumSeverity => -$vote,
                    FIELD_stats.'.'.FIELD_stats_numSeverity => -1
                )
            )
        );
        return array(
            'error' => 0,
            'vote'  => $vote
        );
    }
}