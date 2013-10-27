define(['ember', 'models/Errors', 'utils/utils'], function(Ember, Errors, Utils) {
    "use strict";
    var EvidenceEditController = Ember.ObjectController.extend({
        needs: ['claim', 'application'],
        init: function() {
            this.set('error', Errors.create());
            this._super.apply(this, arguments);
        },
        reset: function() {
            this.get('error').clearAllErrors();
        },
        actions: {
            close: function() {

                this.transitionToRoute('claim');
            },
            updateEvidence: function() {

                var that = this,
                        support = (this.get('support')) ? 1 : 0,
                        payload = {
                    type: 'evidenceCreate',
                    crisisID: this.get('parentCrisisID'),
                    claimID: this.get('parentClaimID'),
                    title: this.get('title'),
                    descr: this.get('descr'),
                    support: support,
                    lat: this.get('lat'),
                    lng: this.get('lng'),
                    street: this.get('street'),
                    cityID: this.get('cityID'),
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
                        that.transitionToRoute('evidence', that.get('model'));
                    }
                };

                if (validation.valid) {
                    this.error.resolveError(valErrCode);
                    Utils.ajax.makeAJAXPostCall('router.php', payload, callback);
                } else {
                    this.error.addError(valErrCode, validation.message);
                }
            }
        },
        parentClaimName: function() {
            return this.get('controllers.claim.title');
        }.property('controllers.claim.title'),
        parentClaimLat: function() {
            return this.get('controllers.claim.lat');
        }.property('controllers.claim.title'),
        parentClaimLng: function() {
            return this.get('controllers.claim.lng');
        }.property('controllers.claim.title'),
        parentCrisisID: function() {
            return this.get('controllers.claim.crisisID');
        }.property('controllers.claim.crisisID'),
        parentClaimID: function() {
            return this.get('controllers.claim.claimID');
        }.property('controllers.claim.claimID'),
    });

    return EvidenceEditController;
});