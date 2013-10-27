define(['ember'],
        function(Ember) {
            "use strict";

            var EvidenceIndexRoute = Ember.Route.extend({
                setupController: function(controller, model) {
                    //Wipe controller content to ensure clean state
                    controller.reset();
                }
            });

            return EvidenceIndexRoute;
});