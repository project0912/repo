define(['ember', "models/Errors", "utils/utils"], function(Ember, Errors, Utils) {
    "use strict";

    var CrisesCreateController = Ember.ObjectController.extend({
        needs: ['application'],
        markers: '',
        init: function() {
            this.set('error', Errors.create());
            this._super.apply(this, arguments);
            this.set('markers', []);
        },
        clearMapPolygon: function() {

            this.set('markers', []);
        },
        pushPayloadToServer: function(payload) {
            var that = this,
                    validation = Utils.validatePayload(payload),
                    valErrCode = Utils.ERRORS.get('VALIDATION_ERR_CODE'),
                    callback;

            callback = function(data) {
                var claimErrCode = Utils.ERRORS.get('CLAIM_ERR_CODE'),
                        claimErrMsg = Utils.ERRORS.get('CLAIM_ERR_MSG');
                if (data.error) {
                    that.error.addError(claimErrCode, claimErrMsg);
                } else {
                    that.error.resolveError(claimErrCode);
                    that.set('model.id', data.ID);
                    that.transitionToRoute('crisis', that.get('model'));
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
        calcLatLng: function() {

            if (this.get('markers.length')) {
                var latArray,
                        lngArray,
                        latAvg,
                        lngAvg;
                latArray = this.get('markers').map(function(latLngArrayPair) {
                    return latLngArrayPair[0];
                });
                lngArray = this.get('markers').map(function(latLngArrayPair) {
                    return latLngArrayPair[1];
                });
                latAvg = latArray.reduce(function(a, b) {
                    return a + b;
                }, 0) / latArray.length;
                lngAvg = lngArray.reduce(function(a, b) {
                    return a + b;
                }, 0) / lngArray.length;
                this.set('lat', latAvg);
                this.set('lng', lngAvg);
            }
        }.observes('this.markers'),
        actions: {
            close: function() {
                window.history.back(); //hack to force it to go back
            },
            createCrisis: function() {

                var payload = {
                    type: 'crisisCreate',
                    title: this.get('title'),
                    descr: this.get('descr'),
                    lat: this.get('lat'),
                    lng: this.get('lng'),
                    cityID: this.get('cityID'),
                    countryID: this.get('countryID'),
                    markers: this.get('markers')
                };

                this.pushPayloadToServer(payload);
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
        cityDidChange: function() {
            if (!this.get('city')) {
                this.set('country', '');
            }
        }.observes('this.city'),
        geoData: function() {
            return this.get('controllers.application.geoData');
        }.property('controllers.application.geoData')
    });

    return CrisesCreateController;
});