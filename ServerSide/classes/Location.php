<?

class Location{

    public static function getPoints(array $arr){
        if (
            !Helper::validLatLng($arr['lat1'], $arr['lng1']) ||
            !Helper::validLatLng($arr['lat2'], $arr['lng2'])
        ){
            Observer::save('ERROR', array(
                'type'      => 'getPoints',
                'params'    => array(
                    array(
                        FIELD_ERROR_lat => Helper::safe($arr['lat1']),
                        FIELD_ERROR_lng => Helper::safe($arr['lng1'])
                    ),
                    array(
                        FIELD_ERROR_lat => Helper::safe($arr['lat2']),
                        FIELD_ERROR_lng => Helper::safe($arr['lng2'])
                    ),
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'latLng'    => 1
                )
            );
        }

        if (!Helper::validZoom($arr['zoom'])){
            Observer::save('ERROR', array(
                'type'      => 'getPoints',
                'params'    => array(
                    FIELD_ERROR_zoom    => Helper::safe($arr['zoom']),
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'zoom'  => 1
                )
            );
        }

        $arrFind = array(
            FIELD_location.'.'.FIELD_location_lat  => array(
                '$gte'  => $arr['lat1'],
                '$lte'  => $arr['lat2']
            ),
            FIELD_location.'.'.FIELD_location_lng  => array(
                '$gte'  => $arr['lng1'],
                '$lte'  => $arr['lng2']
            )
        );

        if ($arr['zoom'] < 4){
            $collection = COLL_CRISIS;
            $type = 'crisis';
        } else {
            $collection = COLL_CLAIM;
            $type = 'claim';
        }

        $res = MDB::alloc()->{$collection}->find(
            array(), //$arrFind,    TODO find what is wrong
            array(
                FIELD_title                                 => 1,
                FIELD_descr                                 => 1,
                FIELD_stats.'.'.FIELD_stats_numViews        => 1,
                FIELD_location.'.'.FIELD_location_country   => 1,
                FIELD_location.'.'.FIELD_location_city      => 1,
                FIELD_location.'.'.FIELD_location_lat       => 1,
                FIELD_location.'.'.FIELD_location_lng       => 1
            )
        );

        $elements = array();
        foreach($res as $el){
            $elements[] = array(
                'type'      => $type,
                'elementID' => $el['_id']->{'$id'},
                'title'     => $el[FIELD_title],
                'descr'     => $el[FIELD_descr],
                'numViews'  => $el[FIELD_stats][FIELD_stats_numViews],
                'country'   => $el[FIELD_location][FIELD_location_country],
                'city'      => $el[FIELD_location][FIELD_location_city],
                'lat'       => $el[FIELD_location][FIELD_location_lat],
                'lng'       => $el[FIELD_location][FIELD_location_lng],
            );
        }


        Observer::save('LOG', array(
            'type'      => 'getPoints',
            'params'    => array()
        ));

        return array(
            'error' => 0,
            'list'  => $elements
        );
    }


    public static function structure($type, $location){
        $error      = array();
        $observer   = array();
        $structure  = array();

        /*
         * validates location fields which are common for every type
         */
        if (!Helper::validCountry($location['countryID'])) {
            $error['country']   = 1;
            $observer[FIELD_ERROR_country] = Helper::safe($location['countryID']);
        } else $structure[FIELD_location_country]                 = (int)$location['countryID'];

        if (!Helper::validCity($location['cityID'])){
            $error['city']      = 1;
            $observer[FIELD_ERROR_city] = Helper::safe($location['cityID']);
        } else $structure[FIELD_location_city]                    = (int)$location['cityID'];

        if (!Helper::validLatLng($location['lat'], $location['lng'])){
            $error['latLng']    = 1;
            $observer[FIELD_ERROR_lat] = Helper::safe($location['lat']);
            $observer[FIELD_ERROR_lng] = Helper::safe($location['lng']);
        } else {
            $structure[FIELD_location_lat] = (float)$location['lat'];
            $structure[FIELD_location_lng] = (float)$location['lng'];
        }


        switch($type){
            case 'crisis':
                $validMarkers = Helper::validMarkers($location['markers']);
                if (!$validMarkers){
                    $error['markers']   = 1;
                    $observer[FIELD_ERROR_markers] = $location['markers'];
                } else $structure[FIELD_location_markers]             = $validMarkers;

                break;
            case 'claim':
                if (!Helper::validStreet($location['street'])){
                    $error['street']    = 1;
                    $observer[FIELD_ERROR_street] = Helper::safe($location['street']);
                } else $structure[FIELD_location_street]              = $location['street'];

                break;
            case 'evidence':
                if (!Helper::validStreet($location['street'])){
                    $error['street']    = 1;
                    $observer[FIELD_ERROR_street] = Helper::safe($location['street']);
                } else $structure[FIELD_location_street]              = $location['street'];

                break;
            default:
                $error['typeServer'] = 1;
        }


        if (sizeof($error)) return array(
            'error'     => 1,
            'list'      => $error,
            'observer'  => $observer
        );

        return array(
            'error'     => 0,
            'structure' => $structure
        );
    }


    public static function mapper($fields, $locations){
        $structure = array();
        if (in_array('country', $fields))   $structure['countryID'] = $locations[FIELD_location_country];
        if (in_array('city',    $fields))   $structure['cityID']    = $locations[FIELD_location_city];
        if (in_array('street',  $fields))   $structure['street']    = $locations[FIELD_location_street];
        if (in_array('lat',     $fields))   $structure['lat']       = $locations[FIELD_location_lat];
        if (in_array('lng',     $fields))   $structure['lng']       = $locations[FIELD_location_lng];
        if (in_array('markers', $fields))   $structure['markers']   = $locations[FIELD_location_markers];


        return $structure;
    }
}