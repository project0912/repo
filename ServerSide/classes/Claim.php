<?

class Claim {

    public function create($claimInfo){

        $authorID       = UserCurrent::currentID();
        if (!$authorID){
            Observer::save('ERROR', array(
                'type'      => 'claimCreate',
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

        $location = Location::structure('claim', array(
            'street'    => $claimInfo['street'],
            'countryID' => $claimInfo['countryID'],
            'cityID'    => $claimInfo['cityID'],
            'lat'       => $claimInfo['lat'],
            'lng'       => $claimInfo['lng']
        ));
        if ($location['error']){
            Observer::save('ERROR', array(
                'type'      => 'claimCreate',
                'params'    => $location['observer']
            ));

            return array(
                'error'     => 1,
                'list'      => $location['list']
            );
        }

        $statistics = Statistics::structure('claim');
        if ($statistics['error']) return array(
            'error'     => 1,
            'list'      => $statistics['list']
        );


        if (!Helper::validClaimTitle($claimInfo['title'])){
            Observer::save('ERROR', array(
                'type'      => 'claimCreate',
                'params'    => array(
                    FIELD_ERROR_title   => Helper::safe($claimInfo['title'])
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'title' => 1
                )
            );
        }
        if (!Helper::validClaimDescription($claimInfo['descr'])){
            Observer::save('ERROR', array(
                'type'      => 'claimCreate',
                'params'    => array(
                    FIELD_ERROR_descr   => Helper::safe($claimInfo['descr'])
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'descr' => 1
                )
            );
        }

        $crisisIDm = Helper::validCrisisID($claimInfo['crisisID']);
        if (!$crisisIDm){
            Observer::save('ERROR', array(
                'type'      => 'claimCreate',
                'params'    => array(
                    FIELD_ERROR_crisisID   => Helper::safe($claimInfo['crisisID'])
                )
            ));

            return array(
                'error'         => 1,
                'list'          => array(
                    'crisisID'  => 1
                )
            );
        }

        $tagIDs = Helper::validTags($claimInfo['tags']);
        if (!$tagIDs){
            Observer::save('ERROR', array(
                'type'      => 'claimCreate',
                'params'    => array(
                    FIELD_ERROR_tags   => Helper::safe($claimInfo['tags'])
                )
            ));

            return array(
                'error'         => 1,
                'list'          => array(
                    'tags'      => 1
                )
            );
        }


        $claimIDm = new MongoId();
        $structure = array(
            '_id'                   => $claimIDm,
            FIELD_title             => $claimInfo['title'],
            FIELD_descr             => $claimInfo['descr'],
            FIELD_crisisID          => $crisisIDm,
            FIELD_authorID          => $authorID,
            FIELD_stats             => $statistics['structure'],
            FIELD_location          => $location['structure'],
            FIELD_tags              => $tagIDs,
            FIELD_flags             => array(),
            FIELD_cover             => Cover::structure(0, array())
        );
        MDB::alloc()->{COLL_CLAIM}->insert($structure);


        Statistics::increaseStats('crisis', $crisisIDm, 'c');
        StatisticsBackend::create('claim',  $claimIDm);
        History::create('claim', $claimIDm);

        Observer::save('LOG', array(
            'type'      => 'claimCreate',
            'params'    => array(
                FIELD_LOG_crisisID  => $crisisIDm,
                FIELD_LOG_claimID   => $claimIDm
            )
        ));

        return array(
            'error' => 0,
            'ID'    => $claimIDm->{'$id'}
        );
    }


    public function getInfo($claimID){

        $error = array(
            'error' => 1,
            'list'  => array(
                'claimID'       => 1
            )
        );


        $claimIDm = Helper::validMongoID($claimID);
        if (!$claimIDm) {
            Observer::save('ERROR', array(
                'type'      => 'claimGetInfo',
                'params'    => array(
                    FIELD_ERROR_claimID   => Helper::safe($claimID)
                )
            ));
            return $error;
        }

        $res = MDB::alloc()->{COLL_CLAIM}->findOne(
            array(
                '_id'               => $claimIDm
            ),
            array(
                FIELD_title         => 1,
                FIELD_crisisID      => 1,
                FIELD_authorID      => 1,
                FIELD_descr         => 1,
                FIELD_stats         => 1,
                FIELD_location      => 1,
                FIELD_tags          => 1,
                FIELD_flags         => 1,
            )
        );
        if (!$res) {
            Observer::save('ERROR', array(
                'type'      => 'claimGetInfo',
                'params'    => array(
                    FIELD_ERROR_claimID   => Helper::safe($claimID)
                )
            ));
            return $error;
        }


        $viewed = StatisticsBackend::view('claim', $claimIDm);


        Observer::save('LOG', array(
            'type'      => 'claimGetInfo',
            'params'    => array(
                FIELD_LOG_claimID   => $claimIDm
            )
        ));

        return array(
            'error'     => 0,
            'element'   => array(
                'claimID'   => $res['_id']->{'$id'},
                'crisisID'  => $res[FIELD_crisisID]->{'$id'},
                'title'     => $res[FIELD_title],
                'descr'     => $res[FIELD_descr],
                'authorID'  => $res[FIELD_authorID]->{'$id'},
                'tags'      => isset($res[FIELD_tags]) ? Tag::mapper($res[FIELD_tags]) : array(),   // TODO  remove this
                'stats'     => Statistics::mapper(array(
                    'numAgrees',
                    'numDisagrees',
                    'numBookmarks',
                    'numViews',
                    'numEvidences',
                    'creationTime',
                    'lastActive',
                    'lastEdit'
                ), $res[FIELD_stats], 'claim', $res['_id']),
                'location'  => Location::mapper(array(
                    'country',
                    'city',
                    'lat',
                    'lng'
                ), $res[FIELD_location]),
                'flagged'   => Flag::hasPersonFlagged($res[FIELD_flags]),
                'viewed'    => (int)$viewed
            )


        );
    }


    public function getEvidences($claimID){

        $error = array(
            'error' => 1,
            'list'  => array(
                'claimID'       => 1
            )
        );


        $claimIDm = Helper::validClaimID($claimID);
        if (!$claimIDm) {
            Observer::save('ERROR', array(
                'type'      => 'claimGetEvidences',
                'params'    => array(
                    FIELD_ERROR_claimID   => Helper::safe($claimID)
                )
            ));

            return $error;
        }

        $res = MDB::alloc()->{COLL_EVIDENCE}->find(
            array(
                FIELD_claimID       => $claimIDm
            ),
            array(
                FIELD_title         => 1,
                FIELD_descr         => 1,
                FIELD_authorID      => 1,
                FIELD_stats         => 1,
                //FIELD_tags          => 1,
                FIELD_location      => 1,
                FIELD_cover         => 1,
                FIELD_support       => 1
            )
        );

        $output = array();
        foreach($res as $i){

            $output[] = array(
                'evidenceID'=> $i['_id']->{'$id'},
                'title'     => $i[FIELD_title],
                'descr'     => $i[FIELD_descr],
                'cover'     => Cover::mapper($i[FIELD_cover]),
                'support'   => $i[FIELD_support],
                'stats'     => Statistics::mapper(array(
                    'numAgrees',
                    'numDisagrees',
                    'numComments'
                ), $i[FIELD_stats], 'claim', $i['_id'])
            );
        }

        Observer::save('LOG', array(
            'type'      => 'claimGetEvidences',
            'params'    => array(
                FIELD_LOG_claimID   => $claimIDm
            )
        ));

        return array(
            'error'     => 0,
            'list'      => $output
        );
    }


    public function edit($claimInfo){

        $authorID       = UserCurrent::currentID();
        if (!$authorID){
            Observer::save('ERROR', array(
                'type'      => 'claimEdit',
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

        $location = Location::structure('claim', array(
            'street'    => $claimInfo['street'],
            'countryID' => $claimInfo['countryID'],
            'cityID'    => $claimInfo['cityID'],
            'lat'       => $claimInfo['lat'],
            'lng'       => $claimInfo['lng']
        ));
        if ($location['error']){
            Observer::save('ERROR', array(
                'type'      => 'claimEdit',
                'params'    => $location['observer']
            ));

            return array(
                'error'     => 1,
                'list'      => $location['list']
            );
        }


        if (!Helper::validClaimTitle($claimInfo['title'])){
            Observer::save('ERROR', array(
                'type'      => 'crisisEdit',
                'params'    => array(
                    FIELD_ERROR_title   => Helper::safe($crisisInfo['title'])
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'title' => 1
                )
            );
        }
        if (!Helper::validClaimDescription($claimInfo['descr'])){
            Observer::save('ERROR', array(
                'type'      => 'crisisEdit',
                'params'    => array(
                    FIELD_ERROR_descr   => Helper::safe($crisisInfo['descr'])
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'descr' => 1
                )
            );
        }

        $claimIDm      = Helper::validMongoID($claimInfo['ID']);
        $claimIDmError  = array(
            'error'     => 1,
            'list'      => array(
                'claimID'  => 1
            )
        );

        if (!$claimIDm){
            Observer::save('ERROR', array(
                'type'      => 'claimEdit',
                'params'    => array(
                    FIELD_ERROR_claimID    => Helper::safe($claimInfo['ID'])
                )
            ));
            return $claimIDmError;
        }


        $tagIDs = Helper::validTags($claimInfo['tags']);
        if (!$tagIDs){
            Observer::save('ERROR', array(
                'type'      => 'claimEdit',
                'params'    => array(
                    FIELD_ERROR_tags   => Helper::safe($claimInfo['tags'])
                )
            ));

            return array(
                'error'         => 1,
                'list'          => array(
                    'tags'      => 1
                )
            );
        }

        $old = MDB::alloc()->{COLL_CLAIM}->findOne(
            array('_id'   => $claimIDm),
            array(
                '_id'               => 0,
                FIELD_title         => 1,
                FIELD_descr         => 1,
                FIELD_location      => 1,
                FIELD_stats.'.'.FIELD_stats_lastEdit => 1,
                FIELD_tags          => 1
            )
        );
        if (!$old){
            Observer::save('ERROR', array(
                'type'      => 'claimEdit',
                'params'    => array(
                    FIELD_ERROR_claimID   => Helper::safe($claimInfo['ID'])
                )
            ));
            return $claimIDmError;
        }

        MDB::alloc()->{COLL_CLAIM}->update(array(
            '_id'               => $claimIDm,
        ),array(
            '$set'              => array(
                FIELD_title     => $claimInfo['title'],
                FIELD_descr     => $claimInfo['descr'],
                FIELD_location  => $location['structure'],
                FIELD_stats.'.'.FIELD_stats_lastActive  => new MongoDate(),
                FIELD_stats.'.'.FIELD_stats_lastEdit    => new MongoDate(),
                FIELD_tags      => $tagIDs
            )
        ));

        History::addVersion('claim', $claimIDm, $old);

        return array(
            'error' => 0
        );
    }
}