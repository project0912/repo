define(['routes/AuthenticatedRoute', "models/Evidence"],
        function(AuthenticatedRoute, Evidence) {
            "use strict";

            var ClaimAddevidenceRoute = AuthenticatedRoute.extend({
                setupController: function(controller, model) {
                    controller.reset();
                    controller.set('model', Evidence.create());
                }
            });

            return ClaimAddevidenceRoute;
        });