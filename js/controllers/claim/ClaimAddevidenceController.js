define(['ember', "models/Errors", 'utils/utils'],
        function(Ember, Errors, Utils) {
            "use strict";
            var ClaimAddevidence = Ember.ObjectController.extend({
                citiesDataSource: "js/utils/resources/cities.json",
                needs: ['claim', 'application'],
                city: '',
                imageIDs: '',
                init: function() {
                    this.set('imageIDs', []);
                    this.set('city', '');
                    this.set('error', Errors.create());
                    this._super.apply(this, arguments);
                },
                reset: function() {
                    this.get('error').clearAllErrors();
                },
                actions: {
                    addEvidence: function() {

                        var that = this,
                                payload = {
                            type: 'evidenceCreate',
                            crisisID: this.get('parentCrisisID'),
                            claimID: this.get('parentClaimID'),
                            title: this.get('title'),
                            descr: this.get('descr'),
                            support: ~~this.get('support'), //Cast to integer 0 or 1
                            lat: this.get('lat'),
                            lng: this.get('lng'),
                            street: this.get('street'),
                            cityID: this.get('cityID'),
                            images: this.get('imageIDs'),
                            countryID: this.get('countryID')
                        },
                        validation = Utils.validatePayload(payload),
                                valErrCode = Utils.ERRORS.get('VALIDATION_ERR_CODE'),
                                callback = function(data) {
                            var evidenceErrCode = Utils.ERRORS.get('EVIDENCE_ERR_CODE'),
                                    evidenceErrMsg = Utils.ERRORS.get('EVIDENCE_ERR_MSG');
                            if (data.error) {
                                that.error.addError(evidenceErrCode, evidenceErrMsg);
                            } else {
                                that.error.resolveError(evidenceErrCode);
                                that.set('model.id', data.ID);
                                that.transitionToRoute('evidence', that.get('model'));
                            }
                        };

                        if (validation.valid) {
                            this.error.resolveError(valErrCode);
                            Utils.ajax.makeAJAXPostCall('router.php', payload, callback);
                        } else {
                            this.error.addError(valErrCode, validation.message);
                        }
                    },
                    close: function() {
                        var parentContext = this.get('controllers.claim.content');
                        this.transitionToRoute('claim', parentContext);
                    },
                    updateLocationInfo: function(locationInfo) {
                        Utils.assert(!Ember.isNone(locationInfo), "Location Info is undefined!");


                        this.set('lat', locationInfo.lat);
                        this.set('lng', locationInfo.lng);
                        this.set('country', locationInfo.country);
                        this.set('countryID', locationInfo.countryID);
                        this.set('cityID', locationInfo.id);
                    }
                },
                parentClaimName: function() {
                    return this.get('controllers.claim.title');
                }.property('controllers.claim.title'),
                mapCentreLat: function() {
                    var centreLat = this.get('centreLat') || this.get('controllers.claim.lat');
                    return centreLat;
                }.property('controllers.claim.title', 'this.centreLat'),
                mapCentreLng: function() {
                    var centreLng = this.get('centreLng') || this.get('controllers.claim.lng');
                    return centreLng;
                }.property('controllers.claim.title', 'this.centreLng'),
                parentCrisisID: function() {
                    return this.get('controllers.claim.crisisID');
                }.property('controllers.claim.crisisID'),
                parentClaimID: function() {
                    return this.get('controllers.claim.claimID');
                }.property('controllers.claim.claimID'),
                support: function() {
                    return !!this.get('controllers.claim.evidSupport');
                }.property('controllers.claim.evidSupport'),
                geoData: function() {
                    return this.get('controllers.application.geoData');
                }.property('controllers.application.geoData')
            });

            return ClaimAddevidence;
        });