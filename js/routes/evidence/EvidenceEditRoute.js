define(['routes/AuthenticatedRoute'],
        function(AuthenticatedRoute) {
            "use strict";

            var EvidenceEditRoute = AuthenticatedRoute.extend({
                setupController : function (controller, model){
                    
                    var evidence = this.controllerFor('evidence').get('model');
                    
                    controller.reset();
                    controller.set('model', evidence);
                }
            });

            return EvidenceEditRoute;
});