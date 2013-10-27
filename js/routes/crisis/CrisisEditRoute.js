define(['routes/AuthenticatedRoute'],
        function(AuthenticatedRoute) {
            "use strict";

            var CrisisEditRoute = AuthenticatedRoute.extend({
                setupController : function (controller, model){
                    
                    var crisis = this.controllerFor('crisis').get('model');
                    
                    controller.reset();
                    controller.set('model', crisis);
                }
            });

            return CrisisEditRoute;
});