<?
class Video{

    public static function create($videoList){
        $videos = array();

        foreach($videoList as $i){
            if (isset($i['type']) && isset($i['url'])){
                switch ($i['type']){
                    case 'youtube':
                        $videos[] = array(
                            FIELD_attachments_videos_type   => VIDEO_youtube,
                            FIELD_attachments_videos_url    => $i['url']
                        );
                }
            } else return $videos;
        }

        return $videos;
    }
}