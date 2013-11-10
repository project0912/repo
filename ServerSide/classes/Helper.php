<?

class Helper{

    public static function safe($str){
        if (gettype($str) == 'array' || gettype($str) == 'object'){
            return serialize($str);
        } else return htmlspecialchars($str);
    }


    public static function getIP(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){
            //check ip from share internet
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //to check ip is pass from proxy
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return ip2long($ip);
    }


    public static function issetCheck($arr, $type = false){
        $error = array();

        foreach($arr as $i){
            if (!isset($_POST[$i])) $error['lostParams'][] = $i;
        }


        if (sizeof($error)){
            if ($type){
                Observer::save('ERROR', array(
                    'type'      => $type,
                    'params'    => array(
                        FIELD_ERROR_missingParams   => $error['lostParams']
                    )
                ));
            }

            return array(
                'error' => 1,
                'list'  => $error
            );
        }

        return array(
            'error' => 0
        );
    }

    public static function validCrisisTitle($crisisTitle){
        if ( filter_var($crisisTitle, FILTER_VALIDATE_FLOAT) ) return false;
        $len = strlen($crisisTitle);
        if ( $len >= 10 && $len <= 140) return true;
        return false;
    }


    public static function validCrisisDescription($crisisDescription){
        if ( filter_var($crisisDescription, FILTER_VALIDATE_FLOAT) ) return false;
        $len = strlen($crisisDescription);
        if ( $len >= 20 && $len <= 2024) return true;
        return false;
    }


    public static function validClaimTitle($claimTitle){
        return Helper::validCrisisTitle($claimTitle);
    }


    public static function validClaimDescription($claimDescription){
        return Helper::validCrisisDescription($claimDescription);
    }


    public static function validEvidenceTitle($evidenceTitle){
        return Helper::validCrisisTitle($evidenceTitle);
    }


    public static function validEvidenceDescription($evidenceDescription){
        return Helper::validCrisisDescription($evidenceDescription);
    }


    public static function validZoom($zoom){
        if (ctype_digit($zoom) && $zoom > 0 && $zoom < 21) return true;
        return false;
    }


    public static function validSupport($int){
        if (!ctype_digit($int)) return false;
        if (in_array($int, array(0, 1))) return true;
        return false;
    }


    public static function validComment($comment){
        return Helper::validCrisisDescription($comment);
    }


    public static function validMongoID($mongoString){

        if (strlen($mongoString) == 24){
            $mid = new MongoId($mongoString);
            $regex = '/^[0-9a-f]{24}$/';
            /*
             * moreover it has only hex digits
             */
            if ( ($mid->{'$id'} === $mongoString) && preg_match($regex, $mongoString) ){
                return $mid;
            }
        }
        return false;
    }


    public static function is_list($array) {
        return !(bool)count(array_filter(array_keys($array), 'is_string'));
    }


    public static function validMongoIDs($arr){
        if (is_array($arr) && Helper::is_list($arr)){
            $mongoIDs = array();
            foreach($arr as $i){
                $element = Helper::validMongoID($i);
                if ($element) $mongoIDs[] = $element;
            }

            if (sizeof($mongoIDs)) return $mongoIDs;
            else false;
        } else return false;
    }


    public static function validCrisisID($mongoString){
        $id = Helper::validMongoID($mongoString);
        if ($id){
            $res = MDB::alloc()->{COLL_CRISIS}->findOne(
                array('_id'   => $id),
                array('_id'   => 1)
            );
            if ($res) return $id;
        }

        return false;
    }


    public static function validClaimID($mongoString){

        $id = Helper::validMongoID($mongoString);
        if ($id){
            $res = MDB::alloc()->{COLL_CLAIM}->findOne(
                array('_id'   => $id),
                array('_id'   => 1)
            );
            if ($res) return $id;
        }

        return false;
    }


    public static function validEvidenceID($mongoString){

        $id = Helper::validMongoID($mongoString);
        if ($id){
             $res = MDB::alloc()->{COLL_EVIDENCE}->findOne(
                array('_id'   => $id),
                array('_id'   => 1)
            );
            if ($res) return $id;
        }

        return false;
    }


    public static function validCrisisClaimID($mongoStringCrisisID, $mongoStringClaimID){

        $crisisIDm = Helper::validMongoID($mongoStringCrisisID);
        $claimIDm = Helper::validMongoID($mongoStringClaimID);

        if($crisisIDm && $claimIDm){

            $res = MDB::alloc()->{COLL_CLAIM}->findOne(
                array(
                    '_id'               => $claimIDm,
                    FIELD_crisisID      => $crisisIDm
                ),
                array('_id'   => 1)
            );
            if ($res) return array(
                'crisisIDm'     => $crisisIDm,
                'claimIDm'      => $claimIDm
            );
        }
        return false;
    }


    public static function validCountry($countryID){
        if (ctype_digit($countryID) && $countryID > 0 && $countryID< 255) return (int)$countryID;
        return false;
    }


    public static function validCity($cityID, $required = true){
        if (!$required && $cityID === 0 || $cityID === '0') return 0;
        if (ctype_digit($cityID) && $cityID > 0 && $cityID< 36255) return (int)$cityID;
        return false;
    }


    public static function validLatLng($lat, $lng){
        if ( !($lat && $lng)) return false;
        if ( filter_var($lat, FILTER_VALIDATE_FLOAT) && filter_var($lng, FILTER_VALIDATE_FLOAT) && $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) return true;
        return false;
    }


    public static function validMarkers($markers){
        if (!is_array($markers)) return false;
        if (sizeof($markers) < 5) return false;

        $validMarkers = array();
        foreach($markers as $marker){
            if (sizeof($marker) !== 2) return false;
            if (!Helper::validLatLng($marker[0], $marker[1])) return false;
            else $validMarkers[] = array((float)$marker[0], (float)$marker[1]);
        }
        return $validMarkers;
    }


    public static function validStreet($streetName){
        if ($streetName == '') return true;
        if (strlen($streetName) >= 4 && strlen($streetName) <= 16) return true;
        return false;
    }


    public static function validTags($tags){

        if (!is_array($tags)) return false;


        $tagsNew = array();
        $res = MDB::alloc()->{COLL_TAG}->find(
            array(),
            array(
                '_id'   => 1
            )
        );
        foreach($res as $i)     $tagsNew[] = $i['_id']->{'$id'};


        $intersect = array_intersect($tagsNew, $tags);


        if (sizeof($tags) > 5) return false;


        $tagIDm = array();
        foreach($intersect as $i) $tagIDm[] = new MongoId($i);

        if (sizeof($tagIDm)) return $tagIDm;
        return false;
    }


    public static function validNewTag($name){
        $len = strlen($name);
        if ( $len > 2 && $len < 10) return true;
        return false;
    }


    public static function validSeverity($severity){
        if (is_int($severity) && $severity > 0 && $severity < 6) return true;
        return false;
    }


    public static function validEmail($mail){
        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) return $mail;
        else return false;
    }

    public static function validPassword($password){
        if (strlen($password) > 10 && strlen($password) < 30) return true;
        else return false;
    }


    public static function getHash($password){
        return md5( md5($password) + md5('Sl4o&4.zs04l-423mo&dki') );
    }


    public static function validExtension($filename){
        $extensions = array('jpg', 'jpeg', 'png');

        $arr = explode('.', $filename);
        if ( in_array(strtolower(end($arr)), $extensions) ){
            return true;
        } else return false;

    }


    public static function validPhotoIDs($photoIDsUnverified){
        if (is_array($photoIDsUnverified)){
            $mongoIDs = array();
            $strings = array();
            foreach($photoIDsUnverified as $i){
                $element = Helper::validMongoID($i);

                if ($element && file_exists(Image::$tempDir.$i.'.jpg')){
                    $mongoIDs[] = $element;
                    $strings[] = $i;
                }
            }

            if (sizeof($mongoIDs)) return array(
                'strings'   => $strings,
                'mongoIDs'  => $mongoIDs
            );
        }

        return array(
            'strings'   => array(),
            'mongoIDs'  => array()
        );
    }


    public static function validUserID($userID){

        $id = Helper::validMongoID($userID);
        if ($id){
            /*
             * makes the call to find out if the ID for such userID exists
             */
            $res = MDB::alloc()->{COLL_USER}->findOne(
                array('_id'   => $id),
                array('_id'   => 1)
            );
            if ($res) return $id;
        }

        return false;
    }


    public static function validName($string){
        $string = preg_replace( "/\s+/", " ", $string);
        if (strlen($string) < 3 || strlen($string) > 15){
            return false;
        }

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }


    public static function validSurname($string){
        return Helper::validName($string);
    }


    public static function validNickname($string){
        return Helper::validName($string);
    }


    public static function validDate($YYYY, $MM, $DD){
        $year = (int)$YYYY;
        $day = (int)$DD;
        $month = (int)$MM;

        if ($year < 1900 || $year > (date("Y") - 15)) return false;
        if (!checkdate($month, $day, $year)) return false;

        return strtotime($year.'-'.$month.'-'.$day);
    }


    public static function validLanguages($arr, $maxNum = 10){
        $integerIDs = array_map('intval', $arr);
        $integerIDs = array_filter($arr, function($i){
            return ($i > 0) && ($i < 300);
        });

        if (sizeof($integerIDs) && sizeof($integerIDs) <= $maxNum) return array_unique($integerIDs);
        else return false;
    }


    public static function validAboutMe($string){
        $str = strip_tags(trim($string));
        if (strlen($str) > 20 && strlen($str) < 500) return $str;
        else return false;
    }


    public static function is_assoc($array){
        return array_values($array)!==$array;
    }


    public static function validURL($url){
        if (filter_var($url, FILTER_VALIDATE_URL)) return $url;
        else return false;
    }
}
