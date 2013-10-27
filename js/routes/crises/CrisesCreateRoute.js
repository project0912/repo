define(['routes/AuthenticatedRoute', "models/Crisis"],
        function(AuthenticatedRoute, Crisis) {
            "use strict";

            var CrisesCreateRoute = AuthenticatedRoute.extend({
                setupController: function(controller, model) {
                    
                    controller.reset();
                    controller.set('model', Crisis.create());
                }
            });

            return CrisesCreateRoute;
        });