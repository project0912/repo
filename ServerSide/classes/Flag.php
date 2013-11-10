<?

class Flag{

    static private $flagsClaim = array(
        array(
            'key'   => 1,
            'text'  => 'Not a claim',
            'descr' => 'description of what is a claim'
        ),
        array(
            'key'   => 2,
            'text'  => 'not a well-written claim',
            'descr' => 'It шs difficult to tell what is being asked to verify here. This claim is ambiguous, vague, incomplete, overly broad, or rhetorical and cannot be reasonably answered in its current form. It may also have severe formatting or content problems'
        ),
        array(
            'key'   => 3,
            'text'  => 'duplicate',
            'descr' => 'This claim has been sent for verification before'
        ),
        array(
            'key'   => 4,
            'text'  => 'off topic',
            'descr' => 'This claim is not related to the crisis'
        ),
        array(
            'key'   => 5,
            'text'  => 'not objective/not factual',
            'descr' => 'As it currently stands, this claim solicits debate and its veracity can not be easily supported by facts, references, or specific expertise'
        ),
        array(
            'key'   => 6,
            'text'  => 'spam/promotional',
            'descr' => 'This question is effectively an advertisement with no disclosure. It is not useful or relevant, but promotional'
        ),
        array(
            'key'   => 7,
            'text'  => 'profanity/hate speech',
            'descr' => 'This question contains content that a reasonable person would consider offensive, abusive, or hate speech'
        )
    );

    static private $flagsEvidence = array(
        array(
            'key'   => 1,
            'text'  => 'Not an evidence',
            'descr' => 'description of what is an evidence'
        ),
        array(
            'key'   => 2,
            'text'  => 'not a well-written evidence',
            'descr' => 'It шs difficult to tell what is being asked to verify here. This evidence is ambiguous, vague, incomplete, overly broad, or rhetorical and cannot be reasonably answered in its current form. It may also have severe formatting or content problems'
        ),
        array(
            'key'   => 3,
            'text'  => 'duplicate',
            'descr' => 'This evidence has been sent for verification before'
        ),
        array(
            'key'   => 4,
            'text'  => 'off topic',
            'descr' => 'This evidence is not related to the claim'
        ),
        array(
            'key'   => 5,
            'text'  => 'not objective/not factual',
            'descr' => 'As it currently stands, this claim solicits debate and its veracity can not be easily supported by facts, references, or specific expertise'
        ),
        array(
            'key'   => 6,
            'text'  => 'spam/promotional',
            'descr' => 'This question is effectively an advertisement with no disclosure. It is not useful or relevant, but promotional'
        ),
        array(
            'key'   => 7,
            'text'  => 'profanity/hate speech',
            'descr' => 'This question contains content that a reasonable person would consider offensive, abusive, or hate speech'
        )
    );


    static private $flagsComment = array(
        array(
            'key'   => 1,
            'text'  => 'off-topic'
        ),
        array(
            'key'   => 2,
            'text'  => 'spam/promotional'
        ),
        array(
            'key'   => 3,
            'text'  => 'profanity/hate speech'
        )
    );


    static function add(array $arr){

        $authorID = UserCurrent::currentID();
        if (!$authorID){
            Observer::save('ERROR', array(
                'type'      => 'flagAdd',
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

        $elemIDm = Helper::validMongoID($arr['elementID']);
        if (!$elemIDm){
            Observer::save('ERROR', array(
                'type'      => 'flagAdd',
                'params'    => array(
                    FIELD_ERROR_elementID    => Helper::safe($arr['elementID'])
                )
            ));

            return array(
                'error'     => 1,
                'list'      => array(
                    'elementID'  => 1
                )
            );
        }

        switch($arr['elementType']){
            case 'claim':
                $collection = COLL_CLAIM;
                $variable = Flag::$flagsClaim;
                $observerType = 'addFlagClaim';
                $observerParams = array(
                    FIELD_LOG_claimID   => $elemIDm
                );
                break;
            case 'evidence':
                $collection = COLL_EVIDENCE;
                $variable = Flag::$flagsEvidence;
                $observerType = 'addFlagEvidence';
                $observerParams = array(
                    FIELD_LOG_evidenceID    => $elemIDm
                );
                break;
            case 'comment':
                if (isset($arr['commentID'])){
                    $collection     = COLL_EVIDENCE;
                    $variable       = Flag::$flagsComment;
                    $commentIDm     = Helper::validMongoID($arr['commentID']);

                    $observerParams = array(
                        FIELD_LOG_evidenceID    => $elemIDm,
                        FIELD_LOG_commentID     => $commentIDm
                    );
                    if (!$commentIDm) $errorCommentID = 1;
                } else $errorCommentID = 1;

                if (isset($errorCommentID)){
                    Observer::save('ERROR', array(
                        'type'      => 'flagAdd',
                        'params'    => array(
                            FIELD_ERROR_commentID    => Helper::safe($arr['commentID'])
                        )
                    ));

                    return array(
                        'error'     => 1,
                        'list'      => array(
                            'commentID'  => 1
                        )
                    );
                }
                $observerType = 'addFlagComment';
                break;
            default:
                Observer::save('ERROR', array(
                    'type'      => 'flagAdd',
                    'params'    => array(
                        FIELD_ERROR_elementType => Helper::safe($arr['elementType'])
                    )
                ));

                return array(
                    'error'     => 1,
                    'list'      => array(
                        'elementType'  => 1
                    )
                );
        }


        $validFlagsID = array();
        foreach ($variable as $i) $validFlagsID[] = (int)$i['key'];
        if (!in_array($arr['flag'], $validFlagsID)){
            Observer::save('ERROR', array(
                'type'      => 'flagAdd',
                'params'    => array(
                    FIELD_ERROR_flag => Helper::safe($arr['flag'])
                )
            ));
            $arr['flag'] = (int)$arr['flag'];
            return array(
                'error'     => 1,
                'list'      => array(
                    'wrongFlag'  => 1
                )
            );
        }


        if (Helper::validMongoID($arr['commentID']) && $arr['elementType'] == 'comment'){
            $find = array(
                '_id'                               => $elemIDm,
                FIELD_comments.'.'.FIELD_commentID  => $commentIDm
            );
            $update = array(
                '$push' => array(
                    FIELD_comments.'.$.'.FIELD_flags=> array(
                        FIELD_authorID  => $authorID,
                        FIELD_flags     => $arr['flag']
                    )
                )
            );
        } else {
            $find = array(
                '_id'       => $elemIDm
            );
            $update = array(
                '$push' => array(
                    FIELD_flags => array(
                        FIELD_authorID  => $authorID,
                        FIELD_flags     => (int)$arr['flag']
                    )
                )
            );
        }
        $res = MDB::alloc()->{$collection}->update($find, $update, array('safe' => true));

        if ($res['n'] === 1){
            Observer::save('LOG', array(
                'type'      => $observerType,
                'params'    => $observerParams
            ));
            return array(
                'error' => 0
            );
        } else {
            Observer::save('ERROR', array(
                'type'      => 'flagAdd',
                'params'    => array(
                    FIELD_ERROR_commentID => Helper::safe($arr['commentID'])
                )
            ));
            return array(
                'error' => 1,
                'list'  => array(
                    'commentID' => 1
                )
            );
        }
    }


    static public function getList($type){
        switch ($type){
            case 'claim':
                $return = array(
                    'error' => 0,
                    'list'  => Flag::$flagsClaim
                );
                break;
            case 'evidence':
                $return = array(
                    'error' => 0,
                    'list'  => Flag::$flagsEvidence
                );
                break;
            case 'comment':
                $return = array(
                    'error' => 0,
                    'list'  => Flag::$flagsComment
                );
                break;
            default:
                Observer::save('ERROR', array(
                    'type'      => 'flagGetList',
                    'params'    => array(
                        FIELD_ERROR_elementType => Helper::safe($type)
                    )
                ));
                return array(
                    'error' => 1,
                    'list'  => array(
                        'flagList'  => 1
                    )
                );
        }

        Observer::save('LOG', array(
            'type'      => 'flagGetList',
            'params'    => array(
                FIELD_ERROR_elementType => $type
            )
        ));
        return $return;

    }

    static public function hasPersonFlagged($flagInfo){
        $authorID       = UserCurrent::currentID();
        if (!$authorID) return 0;

        foreach ($flagInfo as $i) {
            if ($i[FIELD_authorID]->{'$id'} === $authorID->{'$id'}) return 1;
        }
        return 0;
    }
}