define(['routes/AuthenticatedRoute'],
        function(AuthenticatedRoute) {
            "use strict";

            var ClaimEditRoute = AuthenticatedRoute.extend({
                setupController : function (controller, model){
                    
                    var claim = this.controllerFor('claim').get('model');
                    
                    controller.reset();
                    controller.set('model', claim);
                }
            });

            return ClaimEditRoute;
});