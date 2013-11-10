<?

class Comment{

    static function add($commentInfo){

        $authorID       = UserCurrent::currentID();
        if (!$authorID){
            Observer::save('ERROR', array(
                'type'      => 'commentAdd',
                'params'    => array(
                    FIELD_ERROR_noUser    => 1
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'loggedIn'  => 1
                )
            );
        }

        if (!Helper::validComment($commentInfo['descr'])){
            Observer::save('ERROR', array(
                'type'      => 'commentAdd',
                'params'    => array(
                    FIELD_ERROR_descr   => Helper::safe($commentInfo['descr'])
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'descr' => 1
                )
            );
        }
        $evidenceIDm = Helper::validEvidenceID($commentInfo['evidenceID']);
        if (!$evidenceIDm){
            Observer::save('ERROR', array(
                'type'      => 'commentAdd',
                'params'    => array(
                    FIELD_ERROR_evidenceID  => Helper::safe($commentInfo['evidenceID'])
                )
            ));

            return array(
                'error'         => 1,
                'list'          => array(
                    'evidenceID'=> 1
                )
            );
        }


        $commentIDm = new MongoId();
        $comment = array(
            FIELD_commentID             => $commentIDm,
            FIELD_authorID              => $authorID,
            FIELD_stats_creationTime    => new MongoDate(),
            FIELD_descr                 => $commentInfo['descr'],
            FIELD_flags                 => array()
        );

        MDB::alloc()->{COLL_EVIDENCE}->update(
            array(
                '_id'                                       => $evidenceIDm
            ),
            array(
                '$push'                                     => array(
                    FIELD_comments                          => $comment
                ),
                '$inc'                                      => array(
                    FIELD_stats.'.'.FIELD_stats_numComments => 1
                ),
                '$set'  => array(
                    FIELD_stats.'.'.FIELD_stats_lastActive  => new MongoDate()
                )
            )
        );


        Observer::save('LOG', array(
            'type'      => 'commentAdd',
            'params'    => array(
                FIELD_LOG_evidenceID    => $evidenceIDm,
                FIELD_LOG_commentID     => $evidenceIDm
            )
        ));
        return array(
            'error' => 0,
            'ID'    => $commentIDm->{'$id'}
        );
    }


    static function mapper($comments){
        $structure = array();
        foreach(array_reverse($comments) as $i){
            $structure[] = array(
                'commentID'     => $i[FIELD_commentID]->{'$id'},
                'authorID'      => $i[FIELD_authorID]->{'$id'},
                'creationTime'  => $i[FIELD_stats_creationTime]->sec,
                'descr'         => $i[FIELD_descr],
                'flagged'       => Flag::hasPersonFlagged($i[FIELD_flags]),
            );
        }
        return $structure;
    }
}