/**
 * validates the title for a crisis
 * TODO decide what do we consider as a valid title for a crisis
 *
 * currently it is considered as valid if
 * it is a string and 
 * its length >= 10 && <=60
 *
 * @param string    $crisisTitle
 * @return bool
 */
define(['jquery', 'utils/constants'],
        function($, CONST) {
            'use strict';

            var Validator,
                    isInt,
                    inRange;

            Validator = {};
            /**
             * Private function to test if a values is integer
             * @param  value to be tested
             * @return boolean
             */
            isInt = function(val) {
                return Math.floor(val) === val && $.isNumeric(val);
            };
            /**
             * Checks if a value is between the two limits, value is inclusive;
             * @param  floor   [lower limit]
             * @param  ceiling [higher limit]
             * @param  value   [value to be checked]
             * @return [boolean]
             */
            inRange = function(floor, ceiling, value) {
                return floor <= value && value <= ceiling;
            };

            Validator.validEmail = function(email) {

                var emailRegEx = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

                if (typeof email === 'string' && email.length > CONST.get('minEmailLen')) {
                    return emailRegEx.test(email);
                }

                return false;
            };

            Validator.validPassword = function(password) {

                if (typeof password === 'string' && password.length > CONST.get('minPasswordLen')) {
                    return true;
                }
                return false;
            };

            Validator.validTitle = function(title) {

                if (typeof title !== 'string') {
                    return false;
                }
                if (title.length < CONST.get('minTitleLen') || title.length > CONST.get('maxTitleLen')) {
                    return false;
                }

                return true;
            };

            Validator.validDescription = function(description) {
                if (typeof description !== 'string') {
                    return false;
                }
                if (description.length < CONST.get('minDescrLen') || description.length > CONST.get('maxDescrLen')) {
                    return false;
                }
                return true;
            };

            Validator.validZoom = function(level) {
                if (!isInt(level)) {
                    return false;
                }
                if (!inRange(CONST.get('minZoomLevel'),CONST.get('maxZoomLevel'), level)) {
                    return false;
                }
                return true;
            };

            Validator.validSupport = function(support) {
                if (!isInt(support)) {
                    return false;
                }
                if (!inRange(0, 1, support)) {
                    return false;
                }
                return true;
            };

            Validator.validComment = function(comment) {
                return Validator.validDescription(comment);
            };

            Validator.validMongoID = function(mongoID) {
                if (typeof mongoID !== 'string') {
                    return false;
                }
                if (mongoID.length !== CONST.get('mongoIDLen')) {
                    return false;
                }
                var validCharacters = new RegExp(/^[a-f0-9]*$/);

                return validCharacters.test(mongoID);
            };

            Validator.validCountry = function(countryID) {
                //TODO Ensure that the id is in our localStorage store
                if (!isInt(countryID)) {
                    return false;
                }
                if (!inRange(CONST.get('minCountryID'), CONST.get('maxCountryID'), countryID)) {
                    return false;
                }
                return true;
            };

            Validator.validCity = function(cityID) {
                //TODO Ensure that the id is in our localStorage store
                if (!isInt(cityID)) {
                    return false;
                }
                if (!inRange(CONST.get('minCityID'),CONST.get('maxCityID'), cityID)) {
                    return false;
                }
                return true;
            };
            
            Validator.validLat = function(lat) {
                if (typeof lat !== 'number') {
                    return false;
                }
                if (!inRange(CONST.get('minLat'), CONST.get('maxLat'), lat) ) {
                    return false;
                }
                return true;
            };
            
            Validator.validLng = function(lng) {
                if (typeof lng !== 'number') {
                    return false;
                }
                if (!inRange(CONST.get('minLng'), CONST.get('maxLng'), lng)) {
                    return false;
                }
                return true;
            };
            
            Validator.validType = function(type) {
                
                //Checks if the proper AJAX type flag is used
                
                var validTypes = ['claimCreate', 'crisisCreate', 
                    'evidenceCreate', 'commentAdd', 'login', 'claimEdit',
                    'crisisEdit', 'evidenceEdit'];
                
                if($.inArray(type, validTypes) === -1){
                    return false;
                }
                
                return true;
            };

            Validator.validLatLng = function(lat, lng) {
                if (typeof lat !== 'number' || typeof lng !== 'number') {
                    return false;
                }
                if (Validator.validLat(lat) && Validator.validLng(lng)) {
                    return true;
                }
                return false;
            };
            
            /**
             * Check if a marker is valid ( it contains at least 5 valid [lat,lng] coordinates )
             * @param  {[array of [lat,lng]] } markers [array of [latitude,longitude] points]
             * @return {[boolean]}         [description]
             */
            Validator.validMarkers = function(markers) {
                var i;
                
                // At least 3 points are neede to make a polygon
                if (markers.length < CONST.get('minMarkerNumber')) {
                    return false;
                }
                for (i = 0; i < markers.length; i += 1) {
                    if (!Validator.validLatLng(markers[i][0], markers[i][1])) {
                        return false;
                    }
                }
                return true;
            };

            Validator.validStreet = function(street) {
                if (typeof street !== 'string') {
                    return false;
                }
                if (street.length < CONST.get('minStreetLen') || street.length > CONST.get('maxStreetLen')) {
                    return false;
                }
                return true;
            };

            Validator.validSeverity = function(severity) {
                if (!isInt(severity)) {
                    return false;
                }
                if (!inRange(CONST.get('minSeverityLevel') ,CONST.get('maxSeverityLevel'), severity)) {
                    return false;
                }
                return true;
            };
            
            Validator.validTags = function(tags) {
                
                //TODO Dummy placeholder function' always true
                
                return true;
            };

            Validator.validImage = function(){
                //TODO Dummy validation function

                return true;                
            }

            return Validator;
        });