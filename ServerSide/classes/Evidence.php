<?

class Evidence{

    public function create($evidenceInfo){

        $authorID       = UserCurrent::currentID();
        if (!$authorID){
            Observer::save('ERROR', array(
                'type'      => 'evidenceCreate',
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


        $location = Location::structure('evidence', array(
            'countryID' => $evidenceInfo['countryID'],
            'cityID'    => $evidenceInfo['cityID'],
            'street'    => $evidenceInfo['street'],
            'lat'       => $evidenceInfo['lat'],
            'lng'       => $evidenceInfo['lng']
        ));
        if ($location['error']){
            Observer::save('ERROR', array(
                'type'      => 'evidenceCreate',
                'params'    => $location['observer']
            ));

            return array(
                'error'     => 1,
                'list'      => $location['list']
            );
        }


        $statistics = Statistics::structure('evidence');
        if ($statistics['error']) return array(
            'error'     => 1,
            'list'      => $statistics['list']
        );


        if (!Helper::validClaimTitle($evidenceInfo['title'])){
            Observer::save('ERROR', array(
                'type'      => 'evidenceCreate',
                'params'    => array(
                    FIELD_ERROR_title   => Helper::safe($evidenceInfo['title'])
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'title' => 1
                )
            );
        }
        if (!Helper::validClaimDescription($evidenceInfo['descr'])){
            Observer::save('ERROR', array(
                'type'      => 'evidenceCreate',
                'params'    => array(
                    FIELD_ERROR_descr   => Helper::safe($evidenceInfo['descr'])
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'descr' => 1
                )
            );
        }
        if (!Helper::validSupport($evidenceInfo['support'])){
            Observer::save('ERROR', array(
                'type'      => 'evidenceCreate',
                'params'    => array(
                    FIELD_ERROR_support => Helper::safe($evidenceInfo['support'])
                )
            ));

            return array(
                'error'         => 1,
                'list'          => array(
                    'support'   => 1
                )
            );
        }
        $arrID = Helper::validCrisisClaimID($evidenceInfo['crisisID'], $evidenceInfo['claimID']);
        if (!$arrID){
            Observer::save('ERROR', array(
                'type'      => 'evidenceCreate',
                'params'    => array(
                    FIELD_ERROR_crisisID    => Helper::safe($evidenceInfo['crisisID']),
                    FIELD_ERROR_claimID     => Helper::safe($evidenceInfo['claimID'])
                )
            ));

            return array(
                'error'             => 1,
                'list'              => array(
                    'crisisClaim'   => 1
                )
            );
        }

        $evidenceIDm = new MongoId();

        $images = Image::resizeAndMoveFromTemp($evidenceInfo['images'], $arrID['crisisIDm']->{'$id'}, $arrID['claimIDm']->{'$id'}, $evidenceIDm->{'$id'});
        if (sizeof($images)){

            $cover = Cover::structure(3, array(
                'photoID'   => $images[0],
                'evidenceID'=> $evidenceIDm
            ));
            $coverExist = true;
        } else {
            $cover = Cover::structure(0, array());
            $coverExist = false;
        }

        $structure = array(
            '_id'                   => $evidenceIDm,
            FIELD_title             => $evidenceInfo['title'],
            FIELD_descr             => $evidenceInfo['descr'],
            FIELD_crisisID          => $arrID['crisisIDm'],
            FIELD_claimID           => $arrID['claimIDm'],
            FIELD_support           => (int)(bool)(int)$evidenceInfo['support'],
            FIELD_authorID          => $authorID,
            FIELD_stats             => $statistics['structure'],
            FIELD_location          => $location['structure'],
            FIELD_tags              => array(),//$tagsID,           // TODO if we do not need it - remove this field
            FIELD_flags             => array(),
            FIELD_comments          => array(),
            FIELD_attachments       => array(
                FIELD_attachments_images => $images,
                FIELD_attachments_videos => Video::create($evidenceInfo['videos'])
            ),
            FIELD_cover             => $cover
        );
        MDB::alloc()->{COLL_EVIDENCE}->insert($structure);

        if ($coverExist){
            $claimRes = MDB::alloc()->{COLL_CLAIM}->findOne(
                array(
                    '_id' => $arrID['claimIDm']
                ),
                array(
                    FIELD_cover => 1
                )
            );

            if (!$claimRes[FIELD_cover][FIELD_cover_media]){
                $cover[FIELD_cover_evidenceID] = $evidenceIDm;
                MDB::alloc()->{COLL_CLAIM}->update(
                    array(
                        '_id' => $arrID['claimIDm']
                    ),
                    array(
                        '$set' => array(
                            FIELD_cover => $cover
                        )
                    )
                );
            }
        }

        Statistics::increaseStats('crisis', $arrID['crisisIDm'],    'e');
        Statistics::increaseStats('claim',  $arrID['claimIDm'],     'e');
        StatisticsBackend::create('evidence', $evidenceIDm);
        History::create('evidence', $evidenceIDm);

        Observer::save('LOG', array(
            'type'      => 'claimCreate',
            'params'    => array(
                FIELD_LOG_crisisID      => $arrID['crisisIDm'],
                FIELD_LOG_claimID       => $arrID['claimIDm'],
                FIELD_LOG_evidenceID    => $evidenceIDm
            )
        ));

        return array(
            'error' => 0,
            'ID'    => $evidenceIDm->{'$id'}
        );
    }

    public function getInfo($evidenceID){
        $error = array(
            'error' => 1,
            'list'  => array(
                'evidenceID'    => 1
            )
        );

        $evidenceIDm = Helper::validMongoID($evidenceID);
        if (!$evidenceIDm){
            Observer::save('ERROR', array(
                'type'      => 'evidenceGetInfo',
                'params'    => array(
                    FIELD_ERROR_evidenceID   => Helper::safe($evidenceID)
                )
            ));
            return $error;
        }

        $res = MDB::alloc()->{COLL_EVIDENCE}->findOne(
            array(
                '_id' => $evidenceIDm
            ),
            array(
                FIELD_title         => 1,
                FIELD_descr         => 1,
                FIELD_crisisID      => 1,
                FIELD_claimID       => 1,
                FIELD_authorID      => 1,
                FIELD_location      => 1,
                FIELD_stats         => 1,
                FIELD_comments      => array(
                    '$slice'        => -10
                ),
                FIELD_attachments   => 1,
                FIELD_flags         => 1,
                FIELD_support       => 1
            )
        );
        if (!$res){
            Observer::save('ERROR', array(
                'type'      => 'evidenceGetInfo',
                'params'    => array(
                    FIELD_ERROR_evidenceID   => Helper::safe($evidenceID)
                )
            ));
            return $error;
        }

        $viewed = StatisticsBackend::view('evidence', $evidenceIDm);
        Observer::save('LOG', array(
            'type'      => 'evidenceGetInfo',
            'params'    => array(
                FIELD_LOG_evidenceID    => $evidenceIDm
            )
        ));
        return array(
            'error'     => 0,
            'element'   => array(
                'evidenceID'    => $res['_id']->{'$id'},
                'crisisID'      => $res[FIELD_crisisID]->{'$id'},
                'claimID'       => $res[FIELD_claimID]->{'$id'},
                'authorID'      => $res[FIELD_authorID]->{'$id'},
                'title'         => $res[FIELD_title],
                'descr'         => $res[FIELD_descr],
                'stats'         => Statistics::mapper(array(
                    'numAgrees',
                    'numDisagrees',
                    'numViews',
                    'numBookmarks',
                    'creationTime',
                    'lastActive',
                    'lastEdit',
                    'numComments'
                ), $res[FIELD_stats], 'evidence', $res['_id']),
                'location'      => Location::mapper(array(
                    'street',
                    'lat',
                    'lng',
                ), $res[FIELD_location]),
                'tags'          => array(),
                'comments'      => Comment::mapper($res[FIELD_comments]),
                'attachments'   => array(
                    'images'    => Image::mapper($res[FIELD_attachments][FIELD_attachments_images]),
                    'videos'    => array()
                ),
                'viewed'        => (int)$viewed,
                'flagged'       => Flag::hasPersonFlagged($res[FIELD_flags]),
                'support'       => $res[FIELD_support]
            )
        );
    }

    public function edit($evidenceInfo){
        $authorID       = UserCurrent::currentID();
        if (!$authorID){
            Observer::save('ERROR', array(
                'type'      => 'evidenceEdit',
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

        $location = Location::structure('evidence', array(
            'street'    => $evidenceInfo['street'],
            'countryID' => $evidenceInfo['countryID'],
            'cityID'    => $evidenceInfo['cityID'],
            'lat'       => $evidenceInfo['lat'],
            'lng'       => $evidenceInfo['lng']
        ));
        if ($location['error']){
            Observer::save('ERROR', array(
                'type'      => 'evidenceEdit',
                'params'    => $location['observer']
            ));

            return array(
                'error'     => 1,
                'list'      => $location['list']
            );
        }

        if (!Helper::validEvidenceTitle($evidenceInfo['title'])){
            Observer::save('ERROR', array(
                'type'      => 'evidenceEdit',
                'params'    => array(
                    FIELD_ERROR_title   => Helper::safe($evidenceInfo['title'])
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'title' => 1
                )
            );
        }
        if (!Helper::validEvidenceDescription($evidenceInfo['descr'])){
            Observer::save('ERROR', array(
                'type'      => 'evidenceEdit',
                'params'    => array(
                    FIELD_ERROR_descr   => Helper::safe($evidenceInfo['descr'])
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'descr' => 1
                )
            );
        }

        $evidenceIDm      = Helper::validMongoID($evidenceInfo['ID']);
        $evidenceIDmError  = array(
            'error'     => 1,
            'list'      => array(
                'evidenceID'  => 1
            )
        );

        if (!$evidenceIDm){
            Observer::save('ERROR', array(
                'type'      => 'evidenceEdit',
                'params'    => array(
                    FIELD_ERROR_evidenceID    => Helper::safe($evidenceInfo['ID'])
                )
            ));
            return $evidenceIDmError;
        }

        $old = MDB::alloc()->{COLL_EVIDENCE}->findOne(
            array('_id'   => $evidenceIDm),
            array(
                '_id'               => 0,
                FIELD_title         => 1,
                FIELD_descr         => 1,
                FIELD_location      => 1,
                FIELD_support       => 1,
                FIELD_stats.'.'.FIELD_stats_lastEdit => 1
            )
        );
        if (!$old){
            Observer::save('ERROR', array(
                'type'      => 'evidenceEdit',
                'params'    => array(
                    FIELD_ERROR_evidenceID  => Helper::safe($evidenceInfo['ID'])
                )
            ));
            return $evidenceIDmError;
        }

        MDB::alloc()->{COLL_EVIDENCE}->update(array(
            '_id'               => $evidenceIDm,
        ),array(
            '$set'              => array(
                FIELD_title     => $evidenceInfo['title'],
                FIELD_descr     => $evidenceInfo['descr'],
                FIELD_support   => $evidenceInfo['support'],
                FIELD_location  => $location['structure'],
                FIELD_stats.'.'.FIELD_stats_lastActive  => new MongoDate(),
                FIELD_stats.'.'.FIELD_stats_lastEdit    => new MongoDate()
            )
        ));

        History::addVersion('evidence', $evidenceIDm, $old);

        return array(
            'error' => 0
        );
    }
}