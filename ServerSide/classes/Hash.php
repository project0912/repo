<?

class Hash {

    private static $salt = 'Sl4o&4.zs04l-423mo&dki';


    public static function newUser($password){
        return md5( md5($password) + md5(Hash::$salt) );
    }


    public static function codeForUser($login){
        $str = md5($login).md5(time());
        $code = '';
        for ($i=63; $i>=0; $i--){
            if ($str[$i] > 0) $code.= $str[$i];
        }
        return substr($code, 0, 19);
    }
}