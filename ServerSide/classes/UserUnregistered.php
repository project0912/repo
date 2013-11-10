<?

class UserUnregistered{

    public function registrationStart(array $arr){
        $error = array();

        if (isset($arr['email'])){
            $email = strtolower($arr['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error['email'] = 1;
        } else {
            $error['email'] = 1;
        }

        if (isset($arr['password']) && strlen($arr['password']) > 6){
            $pass = Hash::newUser($arr['password']);
        } else {
            $error['password'] = 1;
        }

        if (!$this->is_emailUnique($email)){
            $error['emailNotUnique'] = 1;
        }

        if (sizeof($error)){
            return array(
                'error' => 1,
                'list'  => $error
            );
        }

        $id = new MongoId();
        $code   = Hash::codeForUser($email);
        MDB::alloc()->{COLL_USER_UNREGISTERED}->insert(
            array(
                '_id'               => $id,
                FIELD_UNREG_EMAIL   => $email,
                FIELD_UNREG_PASS    => $pass,
                FIELD_UNREG_NAME    => isset($arr['name']) ? trim($arr['name']) : '',
                FIELD_UNREG_SURNAME => isset($arr['surname']) ? trim($arr['surname']) : '',
                FIELD_UNREG_CODE    => $code
            )
        );

        require_once('lib/swift/lib/swift_required.php');
        $transport = Swift_SmtpTransport::newInstance('localhost', 25);
        $mailer = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance('registration ')
            ->setFrom(array('no-reply@crowdcheck.com' => 'crowdcheck'))
            ->setTo(array($email))
            ->addPart(
                'You registered<br>'.
                'Your login: '.$email.'<br>'.
                'To confirm you data - here is your code '. $code.'<br>'.
                'Your ID :'.$id->{'$id'}
                , 'text/html');
        $mailer->send($message);

        return array(
            'error' => 0,
            'code'  => $code
        );
    }


    public function confirm($ID, $code){
        if (isset($ID) && isset($code)){
            $res = MDB::alloc()->{COLL_USER_UNREGISTERED}->findOne(
                array(
                    '_id'               => new MongoId($ID),
                    FIELD_UNREG_CODE    => $code
                )
            );
            if ($res){
                MDB::alloc()->{COLL_USER_UNREGISTERED}->remove(
                    array(
                        '_id'       => new MongoId($ID)
                    )
                );

                MDB::alloc()->{COLL_USER}->insert(
                    array(
                        FIELD_email    => $res[FIELD_UNREG_EMAIL],
                        FIELD_password  => $res[FIELD_UNREG_PASS],
                        FIELD_name      => $res[FIELD_UNREG_NAME],
                        FIELD_surname   => $res[FIELD_UNREG_SURNAME]
                    )
                );
                return array(
                    'error' => 0
                );
            }
        }
        return array(
            'error' => 1
        );
    }


    public function is_emailUnique($email){
        $res1 = MDB::alloc()->{COLL_USER_UNREGISTERED}->findOne(
            array(
                FIELD_UNREG_EMAIL   => $email
            )
        );

        $res2 = MDB::alloc()->{COLL_USER}->findOne(
            array(
                FIELD_email   => $email
            )
        );

        if ((boolean)$res1 || (boolean)$res2){
            return false;
        } else {
            return true;
        }
    }
}