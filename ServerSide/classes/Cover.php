<?

class Cover{

    public static function structure($type, $info){
        switch ($type){
            case 1:
                return array(
                    FIELD_cover_media   => 1,
                    FIELD_cover_video   => $info['videoLink']
                );
            case 2:
                return array(
                    FIELD_cover_media   => 2,
                    FIELD_cover_video   => $info['videoLink']
                );
            case 3:
                return array(
                    FIELD_cover_media       => 3,
                    FIELD_cover_photoID     => $info['photoID'],
                );
            case 4:
                return array(
                    FIELD_cover_media   => 4,
                    FIELD_cover_imgSrc  => $info['imageLink']
                );
            default:
                return array(
                    FIELD_cover_media   => 0
                );

        }
    }


    public static function mapper($cover){
        if (isset($cover[FIELD_cover_media])){
            switch ($cover[FIELD_cover_media]){
                case 1:
                    return array(
                        'mediaType' => 1,
                        'videoLink' => $cover[FIELD_cover_video]
                    );
                case 2:
                    return array(
                        'mediaType' => 2,
                        'videoLink' => $cover[FIELD_cover_video]
                    );
                case 3:
                    $list = array(
                        'mediaType' => 3,
                        'photoID'   => $cover[FIELD_cover_photoID]->{'$id'}
                    );
                    if (isset($cover[FIELD_cover_evidenceID])) $list['evidenceID'] = $cover[FIELD_cover_photoID]->{'$id'};
                    return $list;
                case 4:
                    return array(
                        'mediaType' => 4,
                        'imgLink'   => $cover[FIELD_cover_imgSrc]
                    );
                default:
                    return array(
                        'mediaType' => 0
                    );

            }
        } else return array(
            'mediaType' => 0
        );
    }

}