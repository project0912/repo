<?

class Crisis{

    public function create($crisisInfo){
        /*
         * verifies that user exist and returns his MongoID
         * without an author you can not create a crisis
         */
        $authorID       = UserCurrent::currentID();
        if (!$authorID){
            Observer::save('ERROR', array(
                'type'      => 'crisisCreate',
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

        $location = Location::structure('crisis', array(
            'countryID' => $crisisInfo['countryID'],
            'cityID'    => $crisisInfo['cityID'],
            'lat'       => $crisisInfo['lat'],
            'lng'       => $crisisInfo['lng'],
            'markers'   => $crisisInfo['markers']
        ));
        if ($location['error']){
            Observer::save('ERROR', array(
                'type'      => 'crisisCreate',
                'params'    => $location['observer']
            ));

            return array(
                'error'     => 1,
                'list'      => $location['list']
            );
        }


        $statistics = Statistics::structure('crisis');
        if ($statistics['error']) return array(
            'error'     => 1,
            'list'      => $statistics['list']
        );

        if (!Helper::validCrisisTitle($crisisInfo['title'])){
            Observer::save('ERROR', array(
                'type'      => 'crisisCreate',
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
        if (!Helper::validCrisisDescription($crisisInfo['descr'])){
            Observer::save('ERROR', array(
                'type'      => 'crisisCreate',
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


        $tagIDs = Helper::validTags($crisisInfo['tags']);
        if (!$tagIDs){
            Observer::save('ERROR', array(
                'type'      => 'crisisCreate',
                'params'    => array(
                    FIELD_ERROR_tags   => Helper::safe($crisisInfo['tags'])
                )
            ));

            return array(
                'error'         => 1,
                'list'          => array(
                    'tags'      => 1
                )
            );
        }


        $crisisIDm = new MongoId();
        $structure = array(
            '_id'               => $crisisIDm,
            FIELD_title         => $crisisInfo['title'],
            FIELD_descr         => $crisisInfo['descr'],
            FIELD_authorID      => $authorID,
            FIELD_stats         => $statistics['structure'],
            FIELD_tags          => $tagIDs,
            FIELD_location      => $location['structure']
        );

        MDB::alloc()->{COLL_CRISIS}->insert($structure);


        StatisticsBackend::create('crisis', $crisisIDm);
        History::create('crisis', $crisisIDm);

        Observer::save('LOG', array(
            'type'      => 'crisisCreate',
            'params'    => array(
                FIELD_LOG_crisisID  => $crisisIDm
            )
        ));
        return array(
            'error' => 0,
            'ID'    => $crisisIDm->{'$id'}
        );
    }


    public function getInfo($crisisID){
        /*
         * error information
         */
        $error = array(
            'error' => 1,
            'list'  => array(
                'crisisID'      => 1
            )
        );

        $crisisIDm = Helper::validMongoID($crisisID);
        if (!$crisisIDm){
            Observer::save('ERROR', array(
                'type'      => 'crisisGetInfo',
                'params'    => array(
                    FIELD_ERROR_crisisID   => Helper::safe($crisisID)
                )
            ));
            return $error;
        }


        $res = MDB::alloc()->{COLL_CRISIS}->findOne(
            array('_id'   => $crisisIDm),
            array(
                FIELD_title         => 1,
                FIELD_descr         => 1,
                FIELD_stats         => 1,
                FIELD_location      => 1,
                FIELD_tags          => 1
            )
        );
        if (!$res){
            Observer::save('ERROR', array(
                'type'      => 'crisisGetInfo',
                'params'    => array(
                    FIELD_ERROR_crisisID   => Helper::safe($crisisID)
                )
            ));
            return $error;
        }

        $viewed = StatisticsBackend::view('crisis', $crisisIDm);



        Observer::save('LOG', array(
            'type'      => 'crisisGetInfo',
            'params'    => array(
                FIELD_LOG_crisisID  => $crisisIDm
            )
        ));

        return array(
            'error'     => 0,
            'element'   => array(
                'crisisID'  => $res['_id']->{'$id'},
                'title'     => $res[FIELD_title],
                'descr'     => $res[FIELD_descr],
                'stats'     => Statistics::mapper(array(
                    'numViews',
                    'numBookmarks',
                    'numClaims',
                    'sumSeverity',
                    'numSeverity',
                    'creationTime',
                    'lastActive',
                    'lastEdit'
                ), $res[FIELD_stats], 'crisis', $res['_id']),
                'location'  => Location::mapper(array(
                    'country',
                    'city',
                    'lat',
                    'lng',
                    'markers'
                ), $res[FIELD_location]),
                'tags'      => isset($res[FIELD_tags]) ? Tag::mapper($res[FIELD_tags]) : array(),       // TODO remove this with the update of the database
                'viewed'    => (int)$viewed
            ),

        );
    }


    public function getClaims($crisisID){
        /*
         * error information
         */
        $error = array(
            'error' => 1,
            'list'  => array(
                'crisisID'      => 1
            )
        );

        $crisisIDm = Helper::validCrisisID($crisisID);
        if (!$crisisIDm){
            Observer::save('ERROR', array(
                'type'      => 'crisisGetClaims',
                'params'    => array(
                    FIELD_ERROR_crisisID    => Helper::safe($crisisID)
                )
            ));

            return $error;
        }


        $res = MDB::alloc()->{COLL_CLAIM}->find(
            array(
                FIELD_crisisID          => $crisisIDm
            ),
            array(
                FIELD_title         => 1,
                FIELD_descr         => 1,
                FIELD_authorID      => 1,
                FIELD_stats         => 1,
                FIELD_tags          => 1,
                FIELD_location      => 1,
                FIELD_cover         => 1
            )
        );

        $output = array();
        foreach($res as $i){

            $output[] = array(
                'claimID'   => $i['_id']->{'$id'},
                'title'     => $i[FIELD_title],
                'descr'     => $i[FIELD_descr],
                'authorID'  => $i[FIELD_authorID]->{'$id'},
                'tags'      => isset($i[FIELD_tags]) ? Tag::mapper($i[FIELD_tags]) : array(),     // TODO remove when we change a database
                'cover'     => Cover::mapper($i[FIELD_cover]),
                'stats'     => Statistics::mapper(array(
                    'numViews',
                    'numBookmarks',
                    'numEvidences',
                    'creationTime',
                    'lastActive',
                    'numAgrees',
                    'numDisagrees'
                ),$i[FIELD_stats], 'crisis', $crisisIDm),
                'location'  => Location::mapper(array(
                    'city',
                    'street'
                ), $i[FIELD_location])
            );
        }


        Observer::save('LOG', array(
            'type'      => 'crisisGetClaims',
            'params'    => array(
                FIELD_LOG_claimID   => $crisisIDm
            )
        ));

        return array(
            'error'     => 0,
            'list'      => $output
        );
    }


    public function edit($crisisInfo){

        $authorID       = UserCurrent::currentID();
        if (!$authorID){
            Observer::save('ERROR', array(
                'type'      => 'crisisEdit',
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

        $location = Location::structure('crisis', array(
            'countryID' => $crisisInfo['countryID'],
            'cityID'    => $crisisInfo['cityID'],
            'lat'       => $crisisInfo['lat'],
            'lng'       => $crisisInfo['lng'],
            'markers'   => $crisisInfo['markers']
        ));
        if ($location['error']){
            Observer::save('ERROR', array(
                'type'      => 'crisisEdit',
                'params'    => $location['observer']
            ));

            return array(
                'error'     => 1,
                'list'      => $location['list']
            );
        }


        if (!Helper::validCrisisTitle($crisisInfo['title'])){
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
        if (!Helper::validCrisisDescription($crisisInfo['descr'])){
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


        $tagIDs = Helper::validTags($crisisInfo['tags']);
        if (!$tagIDs){
            Observer::save('ERROR', array(
                'type'      => 'crisisEdit',
                'params'    => array(
                    FIELD_ERROR_tags   => Helper::safe($crisisInfo['tags'])
                )
            ));

            return array(
                'error'         => 1,
                'list'          => array(
                    'tags'      => 1
                )
            );
        }

        $crisisIDm      = Helper::validMongoID($crisisInfo['ID']);
        $crisisIDError  = array(
            'error'     => 1,
            'list'      => array(
                'crisisID'  => 1
            )
        );

        if (!$crisisIDm){
            Observer::save('ERROR', array(
                'type'      => 'crisisEdit',
                'params'    => array(
                    FIELD_ERROR_crisisID    => Helper::safe($crisisInfo['ID'])
                )
            ));
            return $crisisIDError;
        }

        $old = MDB::alloc()->{COLL_CRISIS}->findOne(
            array('_id'   => $crisisIDm),
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
                'type'      => 'crisisEdit',
                'params'    => array(
                    FIELD_ERROR_crisisID   => Helper::safe($crisisInfo['ID'])
                )
            ));
            return $crisisIDError;
        }

        MDB::alloc()->{COLL_CRISIS}->update(array(
            '_id'               => $crisisIDm,
        ),array(
            '$set'              => array(
                FIELD_title     => $crisisInfo['title'],
                FIELD_descr     => $crisisInfo['descr'],
                FIELD_location  => $location['structure'],
                FIELD_stats.'.'.FIELD_stats_lastActive  => new MongoDate(),
                FIELD_stats.'.'.FIELD_stats_lastEdit    => new MongoDate(),
                FIELD_tags      => $tagIDs
            )
        ));

        History::addVersion('crisis', $crisisIDm, $old);

        return array(
            'error' => 0
        );
    }
}