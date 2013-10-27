/**
 * Put this module in a require block, since it does not have a return value, it just sets up the utils for the application.
 *
 * The nested namespaces/utility classes are defined by BIGCAPITALS in order to attract attention for their usage.
 */
define(['ember',
    'utils/ajax',
    'utils/ErrorInfo',
    'utils/asserts',
    'utils/constants',
    'utils/validator',
    'models/Comment',
    'jquery'],
        function(Ember,
                ajax,
                ErrorInfo,
                asserts,
                constants,
                validator,
                Comment,
                $) {

            "use strict";
            var Utils = {
                ajax: ajax,
                ERRORS: ErrorInfo,
                assert: asserts,
                CONSTANTS: constants,
                validator: validator,
                setModelProperties: function(model, jsonPayload) {
                    //Expects the payload to be put into the MODEL class only

                    asserts(!Ember.isNone(model), "Model is undefined");
                    asserts(!Ember.isNone(jsonPayload), "jsonPayload is undefined");
                    
                    var stats = jsonPayload.stats, //used in claims,crisi
                            location = jsonPayload.location, //used in claims,crisis, evidence
                            support = jsonPayload.support, //used in evidence
                            comments = jsonPayload.comments,
                            attachments = jsonPayload.attachments,
                            mediaCover = jsonPayload.cover,
                            tags = jsonPayload.tags;
                    
                    if (stats) {
                        model.setProperties(stats);
                        delete jsonPayload.stats; //Remove it to avoid overwritting in model
                    }

                    if (location) {
                        model.setProperties(location);
                        delete jsonPayload.location; //Remove it to avoid overwritting in model
                    }

                    if (comments) {
                        var commentObjs = $.map(comments, function(comment){
                            return Comment.create(comment);
                        });
                        
                        model.setProperties({'comments': commentObjs});
                        delete jsonPayload.comments; //Remove it to avoid overwritting in model
                    }
                    
                    if (mediaCover){
                        model.setProperties({
                            'coverPhotoID': mediaCover.photoID,
                            'coverMediaType': mediaCover.mediaType
                        });
                        
                        if(mediaCover.evidenceID){
                            model.setProperties({'coverEvidenceID': mediaCover.evidenceID});
                        }
                        
                        delete jsonPayload.cover;
                    }

                    //TODO restructure the rest of this method.
                    if (typeof(support) === 'number') {
                        asserts(support === 1 || support === 0, 'wrong support property');
                        if (support) {
                            model.setProperties({support: true});
                        } else {
                            model.setProperties({support: false}); //TODO Potential issue with this
                        }
                        delete jsonPayload.support; //Remove it to avoid overwritting in model
                    }

                    if (attachments){
                        if(attachments.images){
                            model.setProperties({'images': attachments.images});
                            delete jsonPayload.attachments.images;
                        }
                        
                        if(attachments.videos){
                            model.setProperties({'videos': attachments.videos});
                            delete jsonPayload.attachments.videos;
                        }
                    }
                    
                    if (tags){
                        model.get('tagIDs').pushObjects(tags);
                        delete jsonPayload.tags;
                    }

                    model.setProperties(jsonPayload);
                },
                validatePayload: function(payload) {

                    var validatorFunctionForKey, key,
                            validatorDispatcher = {
                        login: validator.validEmail,
                        password: validator.validPassword,
                        title: validator.validTitle,
                        descr: validator.validDescription,
                        ID: validator.validMongoID,
                        crisisID: validator.validMongoID,
                        claimID: validator.validMongoID,
                        evidenceID: validator.validMongoID,
                        lat: validator.validLat,
                        lng: validator.validLng,
                        street: validator.validStreet,
                        cityID: validator.validCity,
                        countryID: validator.validCountry,
                        support: validator.validSupport,
                        markers: validator.validMarkers,
                        type: validator.validType,
                        tags: validator.validTags,
                        images: validator.validImage
                    };
                    // for field in payload, if flag is already false, return
                    // object, else get error message

                    for (key in payload) {
                        validatorFunctionForKey = validatorDispatcher[key];
                        if (!validatorFunctionForKey(payload[key])) {
                            return {
                                valid: false,
                                message: ErrorInfo.get(key)
                            };
                        }
                    }

                    return {
                        valid: true,
                        message: ""
                    };
                },
                timeAgo: function(time) {
                    // Shows how much time has elapsed since a timestamp

                    asserts(!Ember.isNone(time), "Time is undefined");
                    time *= 1000; //JavaScript time conversion requires this

                    switch (typeof time) {
                        case 'number':
                            break;
                        case 'string':
                            time = +new Date(time);
                            break;
                        case 'object':
                            if (time.constructor === Date) {
                                time = time.getTime();
                            }
                            break;
                        default:
                            time = +new Date();
                    }

                    var i, format,
                            time_formats = [
                        [60, 'seconds', 1], // 60
                        [120, '1 minute ago', '1 minute from now'], // 60*2
                        [3600, 'minutes', 60], // 60*60, 60
                        [7200, '1 hour ago', '1 hour from now'], // 60*60*2
                        [86400, 'hours', 3600], // 60*60*24, 60*60
                        [172800, 'Yesterday', 'Tomorrow'], // 60*60*24*2
                        [604800, 'days', 86400], // 60*60*24*7, 60*60*24
                        [1209600, 'Last week', 'Next week'], // 60*60*24*7*4*2
                        [2419200, 'weeks', 604800], // 60*60*24*7*4, 60*60*24*7
                        [4838400, 'Last month', 'Next month'], // 60*60*24*7*4*2
                        [29030400, 'months', 2419200], // 60*60*24*7*4*12, 60*60*24*7*4
                        [58060800, 'Last year', 'Next year'], // 60*60*24*7*4*12*2
                        [2903040000, 'years', 29030400], // 60*60*24*7*4*12*100, 60*60*24*7*4*12
                        [5806080000, 'Last century', 'Next century'], // 60*60*24*7*4*12*100*2
                        [58060800000, 'centuries', 2903040000] // 60*60*24*7*4*12*100*20, 60*60*24*7*4*12*100
                    ],
                            seconds = (+new Date() - time) / 1000,
                            token = 'ago',
                            list_choice = 1;
                    if (seconds === 0) {
                        return 'Just now';
                    }

                    if (seconds < 0) {
                        seconds = Math.abs(seconds);
                        token = 'from now';
                        list_choice = 2;
                    }

                    i = 0;
                    format = time_formats[i];
                    while (format) {

                        format = time_formats[i];
                        i = i + 1;
                        if (seconds < format[0]) {
                            if (typeof format[2] === 'string') {
                                return format[list_choice];
                            } else {
                                return Math.floor(seconds / format[2]) + ' ' + format[1] + ' ' + token;
                            }
                        }
                    }
                    return time;
                },
                formatTime: function(date) {
                    var properDate, year, month, day, time;
                    date *= 1000; //required by javascript date conversion
                    properDate = new Date(date);
                    year = properDate.getUTCFullYear();
                    month = properDate.getUTCMonth();
                    day = properDate.getUTCDate();
                    time = properDate.toLocaleTimeString();
                    return year + '-' + month + '-' + day + ' ' + time;
                },
                broadcastMinuteUpdates: function() {
                    // Publish a message every 60s; this broadcast is used to update times.
                    //Calls PubSub object that's made on jQuery object
                    var id, minuteUpdates;
                    minuteUpdates = function() {
                        $.publish('minute');
                    };
                    id = setInterval(minuteUpdates, constants.get('minute'));
                },
                getCityName: function(cityID, citiesArray) {
                    asserts(!Ember.isNone(cityID), "CityID is undefined");
                    asserts(!Ember.isNone(citiesArray), "CitiesArray is undefined");

                    var i = 0,
                            len = citiesArray.length;

                    for (; i < len; i++) {
                        if (citiesArray[i].id === cityID) {
                            return citiesArray[i].city;
                        }
                    }

                    return "";
                }

            };
            return Utils;
        });