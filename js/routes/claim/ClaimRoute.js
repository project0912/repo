define(['ember','models/Claim'],
        function(Ember,Claim) {
            "use strict";

            var ClaimRoute = Ember.Route.extend({
                /**
                 * IMPORTANT [StackOverflow] (http://stackoverflow.com/questions/14367517/in-latest-ember-how-do-you-link-to-a-route-with-just-the-id-name-of-a-model-ra)
                 * In short, when using transitionTo, the find() method does not gets called
                 * by default on the model, so you need to make it manually in order to populate
                 * your model properly.
                 */
                loadedClaim:'',
                setupController: function(controller, claim) { 
                    if (this.loadedClaim !== claim.id){
                        this.loadedClaim = claim.id;
                        claim = Claim.find(claim.id);
                        this.controllerFor('claim').set('model', claim);
                    }
                }
            });

            return ClaimRoute;
});