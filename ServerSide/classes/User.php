<?

class User{

    public static function getUsersInfo($userIDs){
        $mongoIDs = Helper::validMongoIDs($userIDs);
        if ($mongoIDs){
            $res = MDB::alloc()->{COLL_USER_BRIEF}->find(
                array(
                    '_id'       => array(
                        '$in'   => $mongoIDs
                    )
                ),
                array(
                    FIELD_userBasicInfo.'.'.FIELD_nickname  => 1,
                    FIELD_userBasicInfo.'.'.FIELD_name      => 1,
                    FIELD_userBasicInfo.'.'.FIELD_surname   => 1,
                )
            );

            $users = array();
            foreach($res as $i){
                $users[] = array(
                    'ID'        => $i['_id']->{'$id'},
                    'name'      => $i[FIELD_userBasicInfo][FIELD_name],
                    'surname'   => $i[FIELD_userBasicInfo][FIELD_surname],
                    'nickname'  => $i[FIELD_userBasicInfo][FIELD_nickname]
                );
            }
            return array(
                'error' => 0,
                'list'  => $users
            );
        } else return array(
            'error'     => 1,
            'list'      => array(
                'wrongUsers'    => 1
            )
        );
    }
}