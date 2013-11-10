<?

class Share{

    public static function view($arr){
        assert('sizeof($arr) == 3');
        if (!isset($arr['type']) || !isset($arr['elementID']) || !isset($arr['userID']) ){
            return array(
                'error' => 1,
            );
        }


        $userIDm = Helper::validUserID($arr['userID']);
        if (!$userIDm){
            return array(
                'error' => 1,
                'list'  => array(
                    'wrongUser' => 1
                )
            );
        }


        switch($arr['type']){
            case '1':

                $elementIDm = Helper::validCrisisID($arr['elementID']);
                if (!$elementIDm){
                    return array(
                        'error' => 1,
                        'list'  => array(
                            'crisisID' => 1
                        )
                    );
                }

                $return = array(
                    'error'     => 0,
                    'element'   => array(
                        'crisisID'  => $arr['elementID']
                    )
                );
                break;
            case '2':

                $error = array(
                    'error' => 1,
                    'list'  => array(
                        'claimID' => 1
                    )
                );

                $elementIDm = Helper::validMongoID($arr['elementID']);
                if (!$elementIDm) return $error;

                p($elementIDm);
                $res = MDB::alloc()->{COLL_CLAIM}->findOne(
                    array(
                        '_id'   => $elementIDm
                    ),
                    array(
                        '_id'   => 0,
                        FIELD_crisisID   => 1
                    )
                );

                if (!$res) return $error;

                $return = array(
                    'error'     => 0,
                    'element'   => array(
                        'crisisID'  => $res[FIELD_crisisID]->{'$id'},
                        'claimID'   => $arr['elementID']
                    )
                );
                break;
            case '3':

                $error = array(
                    'error' => 1,
                    'list'  => array(
                        'evidenceID' => 1
                    )
                );

                $elementIDm = Helper::validEvidenceID($arr['elementID']);
                if (!$elementIDm) return $error;

                $res = MDB::alloc()->{COLL_EVIDENCE}->findOne(
                    array(
                        '_id'   => $elementIDm
                    ),
                    array(
                        '_id'   => 0,
                        FIELD_claimID   => 1,
                        FIELD_crisisID  => 1
                    )
                );
                if (!$res) return $error;
                $return = array(
                    'error'     => 0,
                    'element'   => array(
                        'crisisID'  => $res[FIELD_crisisID]->{'$id'},
                        'claimID'   => $res[FIELD_claimID]->{'$id'},
                        'evidenceID'=> $arr['elementID']
                    )
                );
                break;
            default:
                return array(
                    'error' => 1,
                    'list'  => array(
                        'shareType' => 1
                    )
                );
        }



        $res = MDB::alloc()->{COLL_SHARE}->findOne(
            array(
                FIELD_userID    => $userIDm,
                FIELD_elementID => $elementIDm,
                FIELD_type      => (int)$arr['type']
            ),
            array(
                FIELD_type  => 1,
                '_id'       => 0
            )
        );


        $IP = Helper::getIP();
        if (!$res){

            MDB::alloc()->{COLL_SHARE}->insert(
                array(
                    FIELD_userID        => $userIDm,
                    FIELD_elementID     => $elementIDm,
                    FIELD_type          => (int)$arr['type'],
                    FIELD_stats_numViews=> 1,
                    FIELD_stats_viewsIP => array($IP)
                )
            );
        } else {

            MDB::alloc()->{COLL_SHARE}->update(
                array(
                    FIELD_userID        => $userIDm,
                    FIELD_elementID     => $elementIDm,
                    FIELD_type          => (int)$arr['type'],
                    FIELD_stats_viewsIP => array(
                        '$ne'           => $IP
                    )
                ),
                array(
                    '$addToSet'             => array(
                        FIELD_stats_viewsIP => $IP
                    ),
                    '$inc'                  => array(
                        FIELD_stats_numViews=> 1
                    )
                )
            );
        }

        return $return;
    }

    public static function getMy(){
        $authorID       = UserCurrent::currentID();
        if (!$authorID){
            return array(
                'error'     => 1,
                'list'      => array(
                    'loggedIn'  => 1
                )
            );
        }

        $res = MDB::alloc()->{COLL_SHARE}->find(
            array(
                FIELD_userID    => $authorID
            ),
            array(
                FIELD_elementID => 1,
                FIELD_type      => 1,
                FIELD_stats_numViews    => 1,
                '_id'           => 0
            )
        )->sort(
            array(
                FIELD_stats_numViews    => -1
            )
        );

        $crisisIDsm     = array();
        $claimIDsm      = array();
        $evidenceIDsm   = array();

        $result = array();
        foreach($res as $i){
            switch($i['type']){
                case 1:
                    $crisisIDsm[] = $i['elementID'];
                    break;
                case 2:
                    $claimIDsm[] = $i['elementID'];
                    break;
                case 3:
                    $evidenceIDsm[] = $i['elementID'];
                    break;
            }
            $result[$i['elementID']->{'$id'}] = array(
                'type'      => $i['type'],
                'numViews'  => $i['numViews']
            );
        }

        if (sizeof($crisisIDsm)){
            $res = MDB::alloc()->{COLL_CRISIS}->find(
                array(
                    '_id'   => array(
                        '$in'   => $crisisIDsm
                    )
                ),
                array(
                    FIELD_title => 1
                )
            );

            foreach($res as $i){
                $result[$i['_id']->{'$id'}]['title'] = $i[FIELD_title];
                $result[$i['_id']->{'$id'}]['crisisID'] = $i['_id']->{'$id'};
            }
        }

        if (sizeof($claimIDsm)){
            $res = MDB::alloc()->{COLL_CLAIM}->find(
                array(
                    '_id'   => array(
                        '$in'   => $claimIDsm
                    )
                ),
                array(
                    FIELD_title     => 1,
                    FIELD_crisisID  => 1
                )
            );

            foreach($res as $i){
                $result[$i['_id']->{'$id'}]['title'] = $i[FIELD_title];
                $result[$i['_id']->{'$id'}]['claimID'] = $i['_id']->{'$id'};
                $result[$i['_id']->{'$id'}]['crisisID'] = $i[FIELD_crisisID]->{'$id'};
            }
        }

        if (sizeof($evidenceIDsm)){
            $res = MDB::alloc()->{COLL_EVIDENCE}->find(
                array(
                    '_id'   => array(
                        '$in'   => $evidenceIDsm
                    )
                ),
                array(
                    FIELD_title     => 1,
                    FIELD_claimID   => 1,
                    FIELD_crisisID  => 1
                )
            );

            foreach($res as $i){
                $result[$i['_id']->{'$id'}]['title'] = $i[FIELD_title];
                $result[$i['_id']->{'$id'}]['evidenceID'] = $i['_id']->{'$id'};
                $result[$i['_id']->{'$id'}]['claimID'] = $i[FIELD_claimID]->{'$id'};
                $result[$i['_id']->{'$id'}]['crisisID'] = $i[FIELD_crisisID]->{'$id'};
            }
        }
        return array(
            'error' => 0,
            'list'  => array_values($result)
        );
    }
}