<?

define('MONGO_noSQL',                   'verily');


define('COLL_USER',                     'user');
define('COLL_USER_BRIEF',               'userBrief');
define('COLL_CRISIS',                   'crisis');
define('COLL_CLAIM',                    'claim');
define('COLL_EVIDENCE',                 'evidence');
define('COLL_TAG',                      'tag');
define('COLL_STATISTICS_CRISIS',        'statsCrisis');
define('COLL_STATISTICS_CLAIM',         'statsClaim');
define('COLL_STATISTICS_EVIDENCE',      'statsEvidence');
define('COLL_OBSERVER_ERROR',           'observerError');
define('COLL_OBSERVER_CORRECT',         'observerCorrect');
define('COLL_SHARE',                    'share');

define('COLL_HISTORY_CRISIS',           'historyCrisis');
define('COLL_HISTORY_CLAIM',            'historyClaim');
define('COLL_HISTORY_EVIDENCE',         'historyEvidence');


define('FIELD_title',                   'title');
define('FIELD_short',                   'short');
define('FIELD_descr',                   'descr');
define('FIELD_location',                'location');
    define('FIELD_location_lat',        'lat');
    define('FIELD_location_lng',        'lng');
    define('FIELD_location_country',    'country');
    define('FIELD_location_city',       'city');
    define('FIELD_location_street',     'street');
    define('FIELD_location_markers',    'markers');
define('FIELD_authorID',                'authorID');
define('FIELD_crisisID',                'crisisID');
define('FIELD_claimID',                 'claimID');
define('FIELD_support',                 'support');
define('FIELD_stats',                   'stats');
    define('FIELD_stats_numViews',      'numViews');
    define('FIELD_stats_numClaims',     'numClaims');
    define('FIELD_stats_numEvidences',  'numEvidences');
    define('FIELD_stats_numBookmarks',  'numBookmarks');
    define('FIELD_stats_numAgrees',     'numAgrees');
    define('FIELD_stats_numDisagrees',  'numDisagrees');
    define('FIELD_stats_numComments',   'numComments');
    define('FIELD_stats_numImages',     'numImages');
    define('FIELD_stats_creationTime',  'creationTime');
    define('FIELD_stats_lastActive',    'lastActive');
    define('FIELD_stats_lastEdit',      'lastEdit');
    define('FIELD_stats_sumSeverity',   'avgSeverity');
    define('FIELD_stats_numSeverity',   'numSeverity');
define('FIELD_tags',                    'tags');
define('FIELD_flags',                   'flags');
define('FIELD_history',                 'history');
define('FIELD_cover',                   'cover');
    define('FIELD_cover_media',         'media');
    define('FIELD_cover_video',         'video');
    define('FIELD_cover_imgSrc',        'imgSrc');
    define('FIELD_cover_photoID',       'photoID');
    define('FIELD_cover_evidenceID',    'photoID');

define('FIELD_comments',                'comments');
    define('FIELD_commentID',           'commentID');
define('FIELD_attachments',                     'attachments');
    define('FIELD_attachments_images',          'imgs');
    define('FIELD_attachments_videos',          'videos');
        define('FIELD_attachments_videos_url',  'url');
        define('FIELD_attachments_videos_type', 'type');
            define('VIDEO_youtube',             'youtube');
define('FIELD_unverified',              'unverified');
define('FIELD_stats_viewsIP',           'viewsIP');
define('FIELD_stats_viewsID',           'viewsID');
define('FIELD_stats_agreesID',          'agreesID');
define('FIELD_stats_disagreesID',       'disagreesID');
define('FIELD_stats_bookmarksID',       'bookmarksID');
define('FIELD_stats_severity',          'severity');

define('FIELD_type',                    'type');
define('FIELD_IP',                      'IP');
define('FIELD_ID',                      'ID');
define('FIELD_time',                    'time');
define('FIELD_userAgent',               'userAgent');

define('FIELD_params',                  'params');


define('FIELD_ERROR_missingParams',     'missing');
define('FIELD_ERROR_noUser',            'noUser');
define('FIELD_ERROR_country',           'country');
define('FIELD_ERROR_city',              'city');
define('FIELD_ERROR_street',            'street');
define('FIELD_ERROR_lat',               'lat');
define('FIELD_ERROR_lng',               'lng');
define('FIELD_ERROR_zoom',              'zoom');
define('FIELD_ERROR_markers',           'markers');
define('FIELD_ERROR_title',             'title');
define('FIELD_ERROR_descr',             'descr');
define('FIELD_ERROR_support',           'support');
define('FIELD_ERROR_crisisID',          'crisisID');
define('FIELD_ERROR_claimID',           'claimID');
define('FIELD_ERROR_evidenceID',        'evidenceID');
define('FIELD_ERROR_commentID',         'commentID');
define('FIELD_ERROR_elementID',         'elementID');
define('FIELD_ERROR_elementType',       'elementType');
define('FIELD_ERROR_flag',              'flag');
define('FIELD_ERROR_tags',              'tags');



define('FIELD_userID',                  'userID');
define('FIELD_elementID',               'elementID');


define('FIELD_LOG_crisisID',            'crisisID');
define('FIELD_LOG_claimID',             'claimID');
define('FIELD_LOG_evidenceID',          'evidenceID');
define('FIELD_LOG_commentID',           'commentID');



define('FIELD_userBasicInfo',           'basicInfo');
    define('FIELD_name',                'name');
    define('FIELD_surname',             'surname');
    define('FIELD_nickname',            'nickname');
    define('FIELD_birthday',            'birthday');
    define('FIELD_languages',           'langs');
    define('FIELD_languagesNative',     'langsNative');
    define('FIELD_about',               'about');
    define('FIELD_avatar',              'avatar');
define('FIELD_userContactInfo',         'contactInfo');
    define('FIELD_twitter',             'twitter');
    define('FIELD_facebook',            'facebook');
    define('FIELD_website',             'website');
define('FIELD_userRating',              'ratingInfo');
    define('FIELD_rating',              'rating');
    define('FIELD_medal1',              'medal1');
    define('FIELD_medal2',              'medal2');
    define('FIELD_medal3',              'medal3');
define('FIELD_userElements',            'elements');
    define('FIELD_crisisIDs',           'crisisIDs');
    define('FIELD_claimIDs',            'claimIDs');
    define('FIELD_evidenceIDs',         'evidenceIDs');

define('FIELD_email',                   'email');
define('FIELD_password',                'password');



function p($arr){
    if (is_array($arr) || is_object($arr)){
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    } else {
        var_dump($arr);
    }
}