<?

class Tag{

    private static function isNameAvailable($name){

        if (Helper::validNewTag($name)){
            /*
             * checks if the name is already in use
             */
            if (MDB::alloc()->{COLL_TAG}->findOne(
                array(FIELD_title   => $name),
                array('_id'         => 1)
            )) return array(
                'error' => 1,
                'list'  => array(
                    'tagDuplicate'  => 1
                )
            );
        } else return array(
            'error' => 1,
            'list'  => array(
                'tagInvalid'  => 1
            )
        );
        return true;
    }


    public static function create(array $arr){

        $error = array();


        $res = Tag::isNameAvailable($arr['name']);
        if ($res['error'] === 1) $error = array_merge($error, $res['list']);


        $authorID       = UserCurrent::currentID();
        if (!$authorID) $error['loggedIn']  = 1;

        if (sizeof($error)){
            return array(
                'error' => 1,
                'list'  => $error
            );
        }

        $id = new MongoId();
        MDB::alloc()->{COLL_TAG}->insert(
            array(
                '_id'               => $id,
                FIELD_title         => $arr['name'],
                FIELD_short         => $arr['short'],
                FIELD_descr         => $arr['descr'],
                FIELD_unverified    => 1,
                FIELD_authorID      => $authorID
            )
        );

        return array(
            'error' => 0,
            'tagID' => $id->{'$id'}
        );
    }


    public static function getListBrief(){

        $res = MDB::alloc()->{COLL_TAG}->find(
            array(),
            array(
                FIELD_name      => 1,
                FIELD_short     => 1
            )
        );


        $tags = array();
        foreach($res as $i){
            $tags[] = array(
                'ID'    => $i['_id']->{'$id'},
                'name'  => $i[FIELD_name],
                'short' => $i[FIELD_short]
            );
        }
        return array(
            'error' => 0,
            'list'  => $tags
        );
    }


    public static function verify($tagID){

        $authorID= UserCurrent::currentID();
        if (!$authorID) return array(
            'error' => 1,
            'list'  => array(
                'loggedIn'  => 1
            )
        );

        MDB::alloc()->{COLL_TAG}->update(
            array(
                '_id'               => new MongoId($tagID),
                FIELD_unverified    => 1
            ),
            array(
                '$unset'    => array(
                    FIELD_unverified=> 1
                )
            )
        );
        return array(
            'error'     => 0
        );
    }


    public static function delete($tagID){

        $authorID= UserCurrent::currentID();
        if (!$authorID) return array(
            'error' => 1,
            'list'  => array(
                'loggedIn'  => 1
            )
        );

        MDB::alloc()->{COLL_TAG}->remove(
            array(
                '_id'               => new MongoId($tagID),
                FIELD_unverified    => 1
            )
        );

        return array(
            'error'     => 0
        );
    }

    public static function update(array $arr){

        $authorID= UserCurrent::currentID();
        if (!$authorID) return array(
            'error' => 1,
            'list'  => array(
                'loggedIn'  => 1
            )
        );

        MDB::alloc()->{COLL_TAG}->update(
            array(
                '_id'       => new MongoId($arr['tagID'])
            ),
            array(
                '$set'                  => array(
                    FIELD_TAG_name      => $arr['name'],
                    FIELD_TAG_short     => $arr['short'],
                    FIELD_TAG_descr     => $arr['descr']
                )
            )
        );

        return array(
            'error'     => 0
        );
    }


    public static function mapper($tags){
        $arr = array();
        foreach($tags as $i){
            $arr[] = $i->{'$id'};
        }
        return $arr;
    }
}