<?

class History {

    public static function create($type, $IDm){
        $tmp = History::getCollection($type);
        if (!$tmp) return false;

        MDB::alloc()->{$tmp['collection']}->insert(array(
            '_id'           => $IDm,
            FIELD_history   => array()
        ));

        return true;
    }


    public static function addVersion($type, $IDm, $old){
        $tmp = History::getCollection($type);
        if (!$tmp) return false;

        $structure = array(
            '_id'                   => new MongoId(),
            FIELD_title             => $old[FIELD_title],
            FIELD_descr             => $old[FIELD_descr],
            FIELD_stats_lastEdit    => $old[FIELD_stats][FIELD_stats_lastEdit],
            FIELD_location          => array(
                FIELD_location_country  => $old[FIELD_location][FIELD_location_country],
                FIELD_location_city     => $old[FIELD_location][FIELD_location_city],
                FIELD_location_lat      => $old[FIELD_location][FIELD_location_lat],
                FIELD_location_lng      => $old[FIELD_location][FIELD_location_lng],
            )
        );

        switch($type){
            case 'crisis':
                $structure[FIELD_location][FIELD_location_markers] = $old[FIELD_location][FIELD_location_markers];
                $structure[FIELD_tags] = $old[FIELD_tags];
                break;
            case 'claim':
                $structure[FIELD_location][FIELD_location_street] = $old[FIELD_location][FIELD_location_street];
                $structure[FIELD_tags] = $old[FIELD_tags];
                break;
            case 'evidence':
                $structure[FIELD_support] = $old[FIELD_support];
                $structure[FIELD_location][FIELD_location_street] = $old[FIELD_location][FIELD_location_street];
                break;
        }

        MDB::alloc()->{$tmp['collection']}->update(
            array(
                '_id'   => $IDm
            ),
            array(
                '$push' => array(
                    FIELD_history => $structure
                )
            ),
            array(      // TODO remove this later with new change to DB
                'upsert'=>true
            )
        );



        Observer::save('LOG', array(
            'type'      => $type.'Edit',
            'params'    => array(
                $tmp['LOG'] => $IDm
            )
        ));
    }


    private static function getCollection($type){
        switch ($type){
            case 'crisis':
                $collection = array(
                    'collection'    => COLL_HISTORY_CRISIS,
                    'LOG'           => FIELD_LOG_crisisID
                );
                break;
            case 'claim':
                $collection = array(
                    'collection'    => COLL_HISTORY_CLAIM,
                    'LOG'           => FIELD_LOG_claimID
                );
                break;
            case 'evidence':
                $collection = array(
                    'collection'    => COLL_HISTORY_EVIDENCE,
                    'LOG'           => FIELD_LOG_evidenceID
                );
                break;
            default:
                return false;
        }

        return $collection;
    }
}