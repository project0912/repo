define(['routes/AuthenticatedRoute', "models/Claim"],
        function(AuthenticatedRoute, Claim) {
            "use strict";

            var CrisisMakeclaimRoute = AuthenticatedRoute.extend({
                setupController: function(controller, model) {
                    
                    controller.reset();
                    controller.set('model', Claim.create());
                }
            });

            return CrisisMakeclaimRoute;
        });