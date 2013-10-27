define(function() {
    'use strict';

    var ErrorInfo = (function() {
        var values = {
            LOGIN_ERR_CODE: 1,
            VALIDATION_ERR_CODE: 2,
            CRISIS_ERR_CODE : 3,
            CLAIM_ERR_CODE: 4,
            EVIDENCE_ERR_CODE: 5,
            COMMENT_ERR_CODE: 6,
            SIGN_IN_ERR_CODE: 7,
            
            LOGIN_ERR_MSG: 'Email/password wrong',
            CRISIS_ERR_MSG: 'Oops, something went wrong on the server, please try again',
            CLAIM_ERR_MSG: 'Oops, something went wrong on the server, please try again',
            EVIDENCE_ERR_MSG: 'Oops, something went wrong on the server, please try again',
            COMMENT_ERR_MSG: 'Oops, something went wrong on the server, please try again',
            SIGN_IN_ERR_MSG: 'Please sign in to create content',
            
            login: 'Please enter a valid email',
            password: 'Password is too short',
            title: 'Please enter a valid title',
            descr: 'Please enter a valid description',
            crisisID: 'Content is invalid/corrupt; please refresh your browser',
            claimID: 'Content is invalid/corrupt; please refresh your browser',
            evidenceID: 'Content is invalid/corrupt; please refresh your browser',
            lat: 'Please enter valid Geographical coordinates',
            lng: 'Please enter valid Geographical coordinates',
            street: 'Please enter a valid street location',
            cityID: 'Please enter a valid city',
            countryID: 'Please enter a valid country',
            support: 'Please choose a stand: true or false',
            markers: 'Please select a valid Geographical area',
            type: 'Content is invalid/corrupt; please refresh your browser'
        };

        return {
            get: function(name) {
                return values[name];
            }
        };
    }());

    return ErrorInfo;
});