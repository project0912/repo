var logger = function(e) {
    console.log(e);
};

var bigList = {
    'crisisCreate': {'type': 'crisisCreate'},
    'crisisGetInfo': {'type': 'crisisGetInfo'},
    'crisisGetClaims': {'type': 'crisisGetClaims'},
    'claimCreate': {'type': 'claimCreate'},
    'claimGetInfo': {'type': 'claimGetInfo'},
    'claimGetEvidences': {'type': 'claimGetEvidences'},
    'evidenceCreate': {'type': 'evidenceCreate'},
    'evidenceGetInfo': {'type': 'evidenceGetInfo'},
    'commentAdd': {'type': 'commentAdd'},
    'getPoints': {'type': 'getPoints'},
    'flagAdd': {'type': 'flagAdd'},
    'flagGetList': {'type': 'flagGetList'},
    'statisticsBookmark': {'type': 'statisticsBookmark'},
    'statisticsAgree': {'type': 'statisticsAgree'},
    'statisticsDisagree': {'type': 'statisticsDisagree'},
    'statisticsCastSeverity': {'type': 'statisticsCastSeverity'},
    'statisticsRevokeSeverity': {'type': 'statisticsRevokeSeverity'}
};

function makeAJAXPostCall(destination, payload, callback) {

    $.ajax({
        url: destination,
        type: "POST",
        data: payload,
        dataType: 'json', //'html',
        success: function(data) {
			console.log(payload);
            if (callback)
                callback(data);
        },
        error: function() {
            console.log("ajaxPostFailed");
        }
    });
}

for (var a in bigList){
    makeAJAXPostCall('router.php', bigList[a], logger);
}
