<?

class Image{
    public static $size = array(
        'huge'  => array(
            'name'  => 'h',
            'width' => 640,
            'height'=> 480
        ),
        'big'  => array(
            'name'  => 'b',
            'width' => 275,
            'height'=> 175
        ),
        'medium'  => array(
            'name'  => 'm',
            'width' => 220,
            'height'=> 125
        ),
        'small'  => array(
            'name'  => 's',
            'width' => 75,
            'height'=> 75
        )
    );
    public static $uploadDir= 'img/upload/';
    public static $tempDir  = 'img/upload/temp/';


    public static function uploadTemp(){
        if (!UserCurrent::currentID()){
            return array(
                'error'     => 1,
                'list'      => array(
                    'loggedIn'  => 1
                )
            );
        }

        if (!isset($_FILES['uploadedFile'])){
            return array(
                'error'     => 1,
                'list'      => array(
                    'loggedIn'  => 1
                )
            );
        }
        $file = $_FILES['uploadedFile'];


        if (!$file['name']){
            return array(
                'error' => 1,
                'list'  => array(
                    'fileName'  => 1
                )
            );
        }


        if ($file['error']){
            return array(
                'error' => 1,
                'list'  => array(
                    'fileUpload' => 1
                )
            );
        }

        if (!Helper::validExtension($file['name'])){
            return array(
                'error' => 1,
                'list'  => array(
                    'fileExtension' => 1
                )
            );
        }


        if ($file['size'] > (1024 * 1024 * 6) || $file['size'] < 1024 * 10){
            return array(
                'error' => 1,
                'list'  => array(
                    'fileSize' => 1
                )
            );
        }

        $image = new SimpleImage();
        $image->load($file['tmp_name']);
        $tmp = new MongoId();
        $photoID = $tmp->{'$id'};

        $image->save(Image::$tempDir.$photoID.'.jpg');
        return array(
            'error'     => 0,
            'photoID'   => $photoID

        );
    }


    public static function resizeAndMoveFromTemp($list, $crisisID, $claimID, $evidenceID){
        $image = new SimpleImage();
        $evidenceDir = Image::$uploadDir . $crisisID . '/' . $claimID . '/' . $evidenceID . '/';
        if(!file_exists($evidenceDir) || !is_dir($evidenceDir)){
            mkdir($evidenceDir, 0777, true);    // TODO make normal restrictions
        }

        $tmp = Helper::validPhotoIDs($list);

        foreach($tmp['strings'] as $photoID){

            $image->load(Image::$tempDir . $photoID . '.jpg');
            foreach(Image::$size as $params){
                $image->resize($params['width'], $params['height']);
                $image->save($evidenceDir.$params['name'].'_'.$photoID.'.jpg');
            }
        }

        return $tmp['mongoIDs'];
    }


    public static function imageLink($crisisID, $claimID, $evidenceID, $size){
        $evidenceDir = Image::$uploadDir . $crisisID . '/' . $claimID . '/' . $evidenceID . '/';

        if (isset(Image::$size[$size])){
            $photoID = Image::$size[$size]['name'];

            $file = $evidenceDir.$photoID.'.jpg';
            if (file_exists($file)) return $file;
        }
        return '/img/no_image.jpg';
    }


    public static function mapper($images){
        $structure = array();
        foreach($images as $i){
            $structure[] = $i->{'$id'};
        }
        return $structure;
    }
}