<?

class UserCurrent{

    public static function logout(){
        if (isset($_SESSION['userID'])){
            session_destroy();
        }
        return 1;
    }


    public static function currentID(){
        return (isset($_SESSION['userID'])) ? new MongoId($_SESSION['userID']) : null;
    }


    public static function login($login, $password){
        if (Helper::validEmail($login) && Helper::validPassword($password)){
            $res = MDB::alloc()->{COLL_USER}->findOne(
                array(
                    FIELD_email     => strtolower($login),
                    FIELD_password  => Helper::getHash($password)
                ),
                array(
                    '_id'   => 1
                )
            );

            if ($res){
                $res = MDB::alloc()->{COLL_USER_BRIEF}->findOne(
                    array(
                        '_id'   => $res['_id']
                    ),
                    array(
                        FIELD_userBasicInfo.'.'.FIELD_nickname  => 1,
                        FIELD_userBasicInfo.'.'.FIELD_name      => 1,
                        FIELD_userBasicInfo.'.'.FIELD_surname   => 1,
                    )
                );

                $_SESSION['userID'] = $res['_id']->{'$id'};

                return array(
                    'error'     => 0,
                    'element'   => array(
                        'ID'        => $res['_id']->{'$id'},
                        'name'      => $res[FIELD_userBasicInfo][FIELD_name],
                        'surname'   => $res[FIELD_userBasicInfo][FIELD_surname],
                        'nickname'  => $res[FIELD_userBasicInfo][FIELD_nickname]
                    )
                );
            }
        }

        return array(
            'error' => 1,
            'list'  => array(
                'loginpassword' => 1,
            )
        );
    }


    public static function isLoggedIn(){
        return (int)(bool)isset($_SESSION['userID']);
    }


    public static function shortInfo(){
        if (UserCurrent::isLoggedIn()){
            $res = MDB::alloc()->{COLL_USER_BRIEF}->findOne(
                array(
                    '_id'   => new MongoId($_SESSION['userID'])
                ),
                array(
                    FIELD_userBasicInfo.'.'.FIELD_nickname  => 1,
                    FIELD_userBasicInfo.'.'.FIELD_name      => 1,
                    FIELD_userBasicInfo.'.'.FIELD_surname   => 1,
                )
            );

            return array(
                'error'     => 0,
                'element'   => array(
                    'ID'        => $res['_id']->{'$id'},
                    'name'      => $res[FIELD_userBasicInfo][FIELD_name],
                    'surname'   => $res[FIELD_userBasicInfo][FIELD_surname],
                    'nickname'  => $res[FIELD_userBasicInfo][FIELD_nickname]
                )
            );
        } else {
            return array(
                'error' => 1,
                'list'  => array(
                    'loggedIn'  => 1
                )
            );
        }
    }


    public static function setBasicInfo($arr){
        assert('isset($arr["name"])');
        assert('isset($arr["surname"])');
        assert('isset($arr["nick"])');
        $error = array(
            'error' => 1,
            'list'  => array()
        );
        $update = array('$set' => array());

        if (!UserCurrent::isLoggedIn()){
            $error['list']['loggedIn'] = 1;
            return $error;
        }

        $validName = Helper::validName($arr['name']);
        if (!$validName){
            $error['list']['wrongName'] = 1;
            return $error;
        }
        $update['$set'][FIELD_userBasicInfo.'.'.FIELD_name] = $validName;


        $validSurname = Helper::validSurname($arr['surname']);
        if (!$validSurname){
            $error['list']['wrongSurname'] = 1;
            return $error;
        }
        $update['$set'][FIELD_userBasicInfo.'.'.FIELD_surname] = $validSurname;

        $validNick = Helper::validNickname($arr['nick']);
        if (!$validNick){
            $error['list']['wrongNick'] = 1;
            return $error;
        }
        $update['$set'][FIELD_userBasicInfo.'.'.FIELD_nickname] = $validNick;

        if (isset($arr['birthdayYYYY']) && isset($arr['birthdayMM']) && isset($arr['birthdayDD'])){
            $validDate = Helper::validDate($arr['birthdayYYYY'], $arr['birthdayMM'], $arr['birthdayDD']);
            if (!$validDate){
                $error['list']['wrongDate'] = 1;
                return $error;
            }
            $update['$set'][FIELD_userBasicInfo.'.'.FIELD_birthday] = new MongoDate($validDate);
        }

        if (isset($arr['languages']) && is_array($arr['languages']) && !Helper::is_assoc($arr['languages'])){
            $validLanguages = Helper::validLanguages($arr['languages'], 10);
            if (!$validLanguages){
                $error['list']['wrongLanguages'] = 1;
                return $error;
            }
            $update['$set'][FIELD_userBasicInfo.'.'.FIELD_languages] = $validLanguages;
        }

        if (isset($arr['nativeLanguages']) && is_array($arr['nativeLanguages']) && !Helper::is_assoc($arr['nativeLanguages'])){
            $validNativeLanguages = Helper::validLanguages($arr['nativeLanguages'], 3);
            if (!$validNativeLanguages){
                $error['list']['wrongNativeLang'] = 1;
                return $error;
            }
            $update['$set'][FIELD_userBasicInfo.'.'.FIELD_languagesNative] = $validNativeLanguages;
        }


        if (isset($arr['about'])){
            $validAboutMe = Helper::validAboutMe($arr['about']);
            if (!$validAboutMe){
                $error['list']['wrongAbout'] = 1;
                return $error;
            }
            $update['$set'][FIELD_userBasicInfo.'.'.FIELD_about] = $validAboutMe;
        }

        MDB::alloc()->{COLL_USER_BRIEF}->update(
            array(
                '_id'   => new MongoId($_SESSION['userID'])
            ),
            $update
        );

        return array(
            'error' => 0
        );
    }


    public static function setContactInfo($arr){
        if (!sizeof($arr)){
            return array(
                'error' => 1
            );
        }

        $error = array(
            'error' => 1,
            'list'  => array()
        );
        $update = array('$set' => array());

        if (!UserCurrent::isLoggedIn()){
            $error['list']['loggedIn'] = 1;
            return $error;
        }

        if (isset($arr['twitter'])){
            $validTwitter = Helper::validURL($arr['twitter']);
            if (!$validTwitter){
                $error['list']['wrongTwitter'] = 1;
                return $error;
            }
            $update['$set'][FIELD_userContactInfo.'.'.FIELD_twitter] = $validTwitter;
        }

        if (isset($arr['facebook'])){
            $validFacebook = Helper::validURL($arr['facebook']);
            if (!$validFacebook){
                $error['list']['wrongFacebook'] = 1;
                return $error;
            }
            $update['$set'][FIELD_userContactInfo.'.'.FIELD_facebook] = $validFacebook;
        }

        if (isset($arr['website'])){
            $validWebsite = Helper::validURL($arr['website']);
            if (!$validWebsite){
                $error['list']['wrongWebsite'] = 1;
                return $error;
            }
            $update['$set'][FIELD_userContactInfo.'.'.FIELD_website] = $validWebsite;
        }

        if (isset($arr['country'])){
            $validCountry = Helper::validCountry($arr['country']);
            if (!$validCountry){
                $error['list']['country'] = 1;
                return $error;
            }
            $update['$set'][FIELD_userContactInfo.'.'.FIELD_location_country] = $validCountry;
        }

        if (isset($arr['city'])){
            $validCity = Helper::validCity($arr['city']);
            if (!$validCity){
                $error['list']['city'] = 1;
                return $error;
            }
            $update['$set'][FIELD_userContactInfo.'.'.FIELD_location_city] = $validCity;
        }

        MDB::alloc()->{COLL_USER_BRIEF}->update(
            array(
                '_id'   => new MongoId($_SESSION['userID'])
            ),
            $update
        );

        return array(
            'error' => 0
        );

    }

    public static function getInfo($userID, $type = 'basic'){
        $userIDm = Helper::validMongoID($userID);
        if (!$userIDm){
            return array(
                'error' => 1,
                'list'  => array(
                    'wrongUser' => 1
                )
            );
        }

        switch ($type){
            case 'basic':
                $field = FIELD_userBasicInfo;
                break;
            case 'contact':
                $field = FIELD_userContactInfo;
                break;
            default:
                return array(
                    'error' => 1
                );
        }

        $res = MDB::alloc()->{COLL_USER_BRIEF}->findOne(
            array(
                '_id'   => $userIDm
            ),
            array(
                '_id'   => 0,
                $field  => 1
            )
        );

        if (!$res){
            return array(
                'error' => 1
            );
        }

        switch ($type){
            case 'basic':
                return array(
                    'error'     => 0,
                    'element'   => array(
                        'about'     => $res[FIELD_userBasicInfo][FIELD_about],
                        'avatar'    => $res[FIELD_userBasicInfo][FIELD_avatar],
                        'birthday'  => $res[FIELD_userBasicInfo][FIELD_birthday]->sec,
                        'languages' => $res[FIELD_userBasicInfo][FIELD_languages],
                        'languagesNative'   => $res[FIELD_userBasicInfo][FIELD_languagesNative],
                        'name'      => $res[FIELD_userBasicInfo][FIELD_name],
                        'surname'   => $res[FIELD_userBasicInfo][FIELD_surname],
                        'nickname'  => $res[FIELD_userBasicInfo][FIELD_nickname],

                    )
                );
            case 'contact':
                return array(
                    'error'     => 0,
                    'element'   => array(
                        'city'      => $res[FIELD_userContactInfo][FIELD_location_city],
                        'country'   => $res[FIELD_userContactInfo][FIELD_location_country],
                        'facebook'  => $res[FIELD_userContactInfo][FIELD_facebook],
                        'twitter'   => $res[FIELD_userContactInfo][FIELD_twitter],
                        'website'   => $res[FIELD_userContactInfo][FIELD_website]
                    )
                );
            default:
                return array(
                    'error' => 1
                );
        }
    }


    public static function getElements($userID, $elementType = 'crisis'){
        switch ($elementType){
            case 'crisis':
                $collection = COLL_CRISIS;
                break;
            case 'claim':
                $collection = COLL_CLAIM;
                break;
            case 'evidence':
                $collection = COLL_EVIDENCE;
                break;
            default:
                return array(
                    'error' => 1,
                    'list'  => array(
                        'wrongType' => 1
                    )
                );
        }

        $userIDm = Helper::validMongoID($userID);
        if (!$userIDm){
            return array(
                'error' => 1,
                'list'  => array(
                    'wrongUser' => 1
                )
            );
        }

        $res = MDB::alloc()->{$collection}->find(
            array(
                FIELD_authorID  => $userIDm
            ),
            array(
                FIELD_title     => 1,
                FIELD_claimID   => 1,
                FIELD_crisisID  => 1,
                FIELD_elementID => 1
            )
        );

        $result = array();
        foreach($res as $i){
            switch ($elementType){
                case 'crisis':
                    $result[] = array(
                        'title'     => $i['title'],
                        'crisisID'  => $i['_id']->{'$id'}
                    );
                    break;
                case 'claim':
                    $result[] = array(
                        'title'     => $i['title'],
                        'crisisID'  => $i[FIELD_crisisID]->{'$id'},
                        'claimID'   => $i['_id']->{'$id'}
                    );
                    break;
                case 'evidence':
                    $result[] = array(
                        'title'     => $i['title'],
                        'crisisID'  => $i[FIELD_crisisID]->{'$id'},
                        'claimID'   => $i[FIELD_claimID]->{'$id'},
                        'evidenceID'=> $i['_id']->{'$id'}
                    );
                    break;
            }
        }

        return array(
            'error' => 0,
            'elements'  => $result
        );
    }
























    public static function helperUnneededRefactorNames(){
        $res = MDB::alloc()->{COLL_USER_BRIEF}->find();
        foreach($res as $i){
            p($i);
            MDB::alloc()->{COLL_USER_BRIEF}->update(
                array(
                    '_id'   => $i['_id']
                ),
                array(
                    FIELD_userBasicInfo     => array(
                        FIELD_about         => '',
                        FIELD_birthday      => '',
                        FIELD_languages     => array(),
                        FIELD_languagesNative   => array(),
                        FIELD_name          => $i['name'],
                        FIELD_nickname      => $i['nickname'],
                        FIELD_surname       => $i['surname'],
                        FIELD_avatar        => ''
                    ),
                    FIELD_userContactInfo   => array(
                        FIELD_location_city => '',
                        FIELD_location_country  => '',
                        FIELD_facebook      => '',
                        FIELD_twitter       => '',
                        FIELD_website       => ''
                    ),
                    FIELD_userRating        => array(
                        FIELD_medal1        => $i['medal1'],
                        FIELD_medal2        => $i['medal2'],
                        FIELD_medal3        => $i['medal3'],
                        FIELD_rating        => $i['rating']
                    ),
                )
            );
        }
    }
}