define(['ember', 'utils/store', 'controllers/UserActionMixin'], function(Ember, Store, UserActionMixin) {
    "use strict";

    var ClaimIndexController = Ember.ObjectController.extend(UserActionMixin, {
        needs: ['crisis', 'claim'],
        parentCrisisName: function() {
            return this.get('controllers.crisis.title');
        }.property('controllers.crisis.title'),
        parentCrisisID: function() {
            return this.get('controllers.crisis.id');
        }.property('controllers.crisis.id'),
        claimID: function() {
            return this.get('controllers.claim.id');
        }.property('controllers.claim.id'),
        actions: {
            backToCrisis: function() {
                this.transitionToRoute('crisis');
            },
            createSupportingEvidence: function() {
                this.set('controllers.claim.evidSupport', 1);
                this.transitionToRoute('claim.addevidence');
            },
            createOpposingEvidence: function() {
                this.set('controllers.claim.evidSupport', 0);
                this.transitionToRoute('claim.addevidence');
            },
            editClaim: function() {

                this.set('controllers.claim.isEdit', true);
                this.transitionToRoute('claim.edit');
            }
        }
    });
    return ClaimIndexController;
});