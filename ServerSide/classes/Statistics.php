<?

class Statistics{

    public static function structure($type){
        $currentTime = new MongoDate();


        $structure = array(
            FIELD_stats_numViews        => 0,
            FIELD_stats_numBookmarks    => 0,
            FIELD_stats_creationTime    => $currentTime,
            FIELD_stats_lastActive      => $currentTime,
            FIELD_stats_lastEdit        => $currentTime
        );


        switch($type){
            case 'crisis':
                $structure[FIELD_stats_numEvidences]= 0;
                $structure[FIELD_stats_numClaims]   = 0;
                $structure[FIELD_stats_sumSeverity] = 0;
                $structure[FIELD_stats_numSeverity] = 0;
                break;
            case 'claim':
                $structure[FIELD_stats_numEvidences]= 0;
                $structure[FIELD_stats_numAgrees]   = 0;
                $structure[FIELD_stats_numDisagrees]= 0;
                break;
            case 'evidence':
                $structure[FIELD_stats_numComments] = 0;
                $structure[FIELD_stats_numAgrees]   = 0;
                $structure[FIELD_stats_numDisagrees]= 0;
                $structure[FIELD_stats_numImages]   = 0;
                break;
            default:
                return array(
                    'error' => 1,
                    'list'  => array(
                        'typeServer'    => 1
                    )
                );
                break;
        }

        return array(
            'error'     => 0,
            'structure' => $structure
        );

    }


    public static function mapper($fields, $stats, $type, $elementID){
        $structure = array();
        if (in_array('numViews',    $fields))  $structure['numViews']      = $stats[FIELD_stats_numViews];
        if (in_array('numBookmarks',$fields))  $structure['numBookmarks']  = $stats[FIELD_stats_numBookmarks];
        if (in_array('numClaims',   $fields))  $structure['numClaims']     = $stats[FIELD_stats_numClaims];
        if (in_array('sumSeverity', $fields))  $structure['sumSeverity']   = $stats[FIELD_stats_sumSeverity];   // TODO change to sumSeverity
        if (in_array('numSeverity', $fields))  $structure['numSeverity']   = $stats[FIELD_stats_numSeverity];
        if (in_array('creationTime',$fields))  $structure['creationTime']  = $stats[FIELD_stats_creationTime]->sec;
        if (in_array('lastActive',  $fields))  $structure['lastActive']    = $stats[FIELD_stats_lastActive]->sec;
        if (in_array('lastEdit',    $fields))  {
            // TODO remove this in the new database
            if (isset($stats[FIELD_stats_lastEdit])){
                $structure['lastEdit']      = $stats[FIELD_stats_lastEdit]->sec;
            } else {
                $structure['lastEdit']      = $stats[FIELD_stats_creationTime]->sec;
            }

        }
        if (in_array('numEvidences',$fields))  $structure['numEvidences']  = $stats[FIELD_stats_numEvidences];
        if (in_array('numAgrees',   $fields))  $structure['numAgrees']     = $stats[FIELD_stats_numAgrees];
        if (in_array('numDisagrees',$fields))  $structure['numDisagrees']  = $stats[FIELD_stats_numDisagrees];
        if (in_array('numComments', $fields))  $structure['numComments']   = $stats[FIELD_stats_numComments];

        /*
         * gives information has a person done something with statistics
         * TODO it searches it in a terrible way
         */
        $authorID = UserCurrent::currentID();
        if ($authorID) {
            switch($type){
                case 'crisis':
                    $collection = COLL_STATISTICS_CRISIS;
                    $projection = array(
                        '_id'                                   => 0,
                        FIELD_stats_bookmarksID                 => 1,
                        FIELD_stats_severity.'.'.FIELD_authorID => 1
                    );
                    break;
                case 'claim':
                    $collection = COLL_STATISTICS_CLAIM;
                    $projection = array(
                        '_id'                       => 0,
                        FIELD_stats_bookmarksID     => 1,
                        FIELD_stats_agreesID        => 1,
                        FIELD_stats_disagreesID     => 1
                    );
                    break;
                case 'evidence':
                    $collection = COLL_STATISTICS_EVIDENCE;
                    $projection = array(
                        '_id'                       => 0,
                        FIELD_stats_bookmarksID     => 1,
                        FIELD_stats_agreesID        => 1,
                        FIELD_stats_disagreesID     => 1
                    );
                    break;
                default:
                    return false;
            }

            $res = MDB::alloc()->{$collection}->findOne(
                array(
                    '_id'   => $elementID
                ),
                $projection
            );

            if (isset($res[FIELD_stats_bookmarksID])){
                $structure['castedBookmark'] = 0;
                foreach($res[FIELD_stats_bookmarksID] as $i){
                    if ($i->{'$id'} === $authorID->{'$id'}){
                        $structure['castedBookmark'] = 1;
                        break;
                    }
                }
            }

            if (isset($res[FIELD_stats_agreesID])){
                $structure['castedAgree'] = 0;
                foreach($res[FIELD_stats_agreesID] as $i){
                    if ($i->{'$id'} === $authorID->{'$id'}){
                        $structure['castedAgree'] = 1;
                        break;
                    }
                }
            }

            if (isset($res[FIELD_stats_disagreesID])){
                $structure['castedDisagree'] = 0;
                foreach($res[FIELD_stats_disagreesID] as $i){
                    if ($i->{'$id'} === $authorID->{'$id'}){
                        $structure['castedDisagree'] = 1;
                        break;
                    }
                }
            }

            if (isset($res[FIELD_stats_severity])){
                $structure['castedSeverity'] = 0;
                foreach($res[FIELD_stats_severity] as $i){
                    if ($i[FIELD_authorID]->{'$id'} === $authorID->{'$id'}){
                        $structure['castedSeverity'] = 1;
                        break;
                    }
                }
            }

        }

        return $structure;
    }

    public static function increaseStats($collection, $IDm, $field){
        switch($collection){
            case 'crisis':
                $collection = COLL_CRISIS;
                break;
            case 'claim':
                $collection = COLL_CLAIM;
                break;
            default:
                p('wrong collection in stats');
                return false;
        }

        switch($field){
            case 'c':
                $field = FIELD_stats_numClaims;
                break;
            case 'e':
                $field = FIELD_stats_numEvidences;
                break;
            default:
                p('wrong field in stats');
                return false;
        }
        return Statistics::changeNumberInStats($collection, $IDm, $field, 1, true);
    }

    public static function decreaseStats($collection, $IDm, $field){
        switch($collection){
            case 'crisis':
                $collection = COLL_CRISIS;
                break;
            case 'claim':
                $collection = COLL_CLAIM;
                break;
            default:
                p('wrong collection in stats');
                return false;
        }

        switch($field){
            case 'c':
                $field = FIELD_stats_numClaims;
                break;
            case 'e':
                $field = FIELD_stats_numEvidences;
                break;
            default:
                p('wrong field in stats');
                return false;
        }
        return Statistics::changeNumberInStats($collection, $IDm, $field, -1, true);
    }


    public static function view($collection, $IDm){
        return Statistics::changeNumberInStats($collection, $IDm, FIELD_stats_numViews, 1, false);
    }


    public static function bookmark($collection, $IDm, $field, $diff){
        return Statistics::changeNumberInStats($collection, $IDm, FIELD_stats_numBookmarks, $diff, false);
    }


    public static function agreeDisagree($collection, $IDm, $agree, $diff){
        $field = ($agree) ? FIELD_stats_numAgrees : FIELD_stats_numDisagrees;
        return Statistics::changeNumberInStats($collection, $IDm, $field, $diff, false);
    }


    private static function changeNumberInStats($collection, $IDm, $field, $difference, $updateTime = false){
        $update = array(
            '$inc'  => array(
                FIELD_stats.'.'.$field => (int)$difference
            )
        );

        if ($updateTime){
            $update['$set'] = array(
                FIELD_stats.'.'.FIELD_stats_lastActive => new MongoDate()
            );
        }

        MDB::alloc()->{$collection}->update(
            array('_id'   => $IDm),
            $update
        );

        return true;
    }
}