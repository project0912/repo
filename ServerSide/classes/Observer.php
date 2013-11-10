<?

class Observer{

    private static function createLog($errorType, $type, $params){
        switch($errorType){
            case 'ERROR':
                $collection = COLL_OBSERVER_ERROR;
                break;
            case 'LOG':
                $collection = COLL_OBSERVER_CORRECT;
                break;
            default:
                return false;
        }

        $arr = array(
            FIELD_type  => $type,
            FIELD_IP    => Helper::getIP(),
            FIELD_time  => new MongoDate()
        );

        if ($errorType === 'ERROR') $arr[FIELD_userAgent] = $_SERVER['HTTP_USER_AGENT'];

        $userID = UserCurrent::currentID();
        if ($userID)    $arr[FIELD_ID]      = $userID;
        if ($params){
            if ($errorType === 'ERROR') $arr[FIELD_params]  = $params;
            else {
                foreach ($params as $key => $value){
                    $arr[$key] = $value;
                }
            }
        }

        MDB::alloc()->{$collection}->insert($arr);

        return true;
    }


    public static function save($errorType, array $arr){
        switch($arr['type']){
            case 'crisisCreate':
                Observer::createLog($errorType, 200, $arr['params']);
                break;
            case 'crisisEdit':
                Observer::createLog($errorType, 206, $arr['params']);
                break;
            case 'crisisGetInfo':
                Observer::createLog($errorType, 204, $arr['params']);
                break;
            case 'crisisGetClaims':
                Observer::createLog($errorType, 203, $arr['params']);
                break;
            case 'claimCreate':
                Observer::createLog($errorType, 300, $arr['params']);
                break;
            case 'claimEdit':
                Observer::createLog($errorType, 306, $arr['params']);
                break;
            case 'claimGetInfo':
                Observer::createLog($errorType, 304, $arr['params']);
                break;
            case 'claimGetEvidences':
                Observer::createLog($errorType, 303, $arr['params']);
                break;
            case 'evidenceCreate':
                Observer::createLog($errorType, 400, $arr['params']);
                break;
            case 'evidenceEdit':
                Observer::createLog($errorType, 406, $arr['params']);
                break;
            case 'evidenceGetInfo':
                Observer::createLog($errorType, 404, $arr['params']);
                break;
            case 'commentAdd':
                Observer::createLog($errorType, 500, $arr['params']);
                break;
            case 'getPoints':
                Observer::createLog($errorType, 603, $arr['params']);
                break;
            case 'flagAdd':
                Observer::createLog($errorType, 700, $arr['params']);
                break;
            case 'addFlagClaim':
                Observer::createLog($errorType, 701, $arr['params']);
                break;
            case 'addFlagEvidence':
                Observer::createLog($errorType, 702, $arr['params']);
                break;
            case 'addFlagComment':
                Observer::createLog($errorType, 703, $arr['params']);
                break;
            case 'flagGetList':
                Observer::createLog($errorType, 705, $arr['params']);
                break;



            default:
                Observer::createLog('ERROR', 999, array());
                break;

        }
    }
}

