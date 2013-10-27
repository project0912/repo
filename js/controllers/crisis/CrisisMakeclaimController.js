define(['ember', "models/Errors", 'utils/utils', 'jquery'],
        function(Ember, Errors, Utils, $) {
            "use strict";
            var CrisisMakeclaimController = Ember.ObjectController.extend({
                needs: ['crisis', 'application'],
                tags: '',
                city: '',
                imageIDs: '',
                init: function() {
                    this.set('tags', []);
                    this.set('imageIDs', []);
                    this.set('city', '');
                    this.set('error', Errors.create());
                    this._super.apply(this, arguments);
                },
                getTagIDs: function() {
                    var tags = this.get('tags'),
                            tagsPackage = this.get('tagsData'),
                            tagIDs = [];

                    if (tags && tagsPackage) {
                        tagsPackage.forEach(function(tagPackage) {
                            if ($.inArray(tagPackage.name, tags) !== -1) {
                                tagIDs.push(tagPackage.id);
                            }
                        });
                    }

                    return tagIDs;
                },
                pushPayloadToServer: function(payload) {
                    var that = this,
                            validation = Utils.validatePayload(payload),
                            valErrCode = Utils.ERRORS.get('VALIDATION_ERR_CODE'),
                            callback = function(data) {

                        var claimErrCode = Utils.ERRORS.get('CLAIM_ERR_CODE'),
                                claimErrMsg = Utils.ERRORS.get('CLAIM_ERR_MSG');
                        if (data.error) {
                            that.error.addError(claimErrCode, claimErrMsg);
                        } else {
                            that.error.resolveError(claimErrCode);
                            that.set('model.id', data.ID);
                            that.transitionToRoute('claim', that.get('model'));
                        }
                    };

                    if (validation.valid) {
                        this.error.resolveError(valErrCode);
                        Utils.ajax.makeAJAXPostCall('router.php', payload, callback);
                    } else {
                        this.error.addError(valErrCode, validation.message);
                    }
                },
                reset: function() {
                    this.get('error').clearAllErrors();
                },
                actions: {
                    close: function() {
                        var parentContext;

                        parentContext = this.get('controllers.crisis.content');
                        this.transitionToRoute('crisis', parentContext);
                    },
                    makeClaim: function() {
                        //process payload here

                        var payload = {
                            type: 'claimCreate',
                            crisisID: this.get('parentCrisisID'),
                            title: this.get('title'),
                            descr: this.get('descr'),
                            lat: this.get('lat'),
                            lng: this.get('lng'),
                            street: this.get('street'),
                            cityID: this.get('cityID'),
                            countryID: this.get('countryID'),
                            tags: this.getTagIDs(),
                            images: this.get('imageIDs')
                        };

                        this.pushPayloadToServer(payload);
                    },
                    updateLocationInfo: function(locationInfo) {
                        Utils.assert(!Ember.isNone(locationInfo), "Location Info is undefined!");

                        this.set('mapCentreLat', locationInfo.lat);
                        this.set('mapCentreLng', locationInfo.lng);
                        this.set('country', locationInfo.country);
                        this.set('countryID', locationInfo.countryID);
                        this.set('cityID', locationInfo.id);
                    }
                },
                parentCrisisID: function() {
                    return this.get('controllers.crisis.crisisID');
                }.property('controllers.crisis.crisisID'),
                parentCrisisName: function() {
                    return this.get('controllers.crisis.title');
                }.property('controllers.crisis.title'),
                mapCentreLat: function() {
                    var centreLat = this.get('centreLat') || this.get('controllers.crisis.lat');
                    return centreLat;
                }.property('controllers.crisis.title', 'this.centreLat'),
                mapCentreLng: function() {
                    var centreLng = this.get('centreLng') || this.get('controllers.crisis.lng')
                    return centreLng;
                }.property('controllers.crisis.title', 'this.centreLng'),
                geoData: function() {
                    return this.get('controllers.application.geoData');
                }.property('controllers.application.geoData'),
                tagsData: function() {
                    return this.get('controllers.application.tags');
                }.property('controllers.application.tags')
            });
            return CrisisMakeclaimController;
        });