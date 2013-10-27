define(['ember'],
        function(Ember) {
            "use strict";
            
            var UserLoginRoute = Ember.Route.extend({
                setupController: function(controller, model) {
                    //Wipe controller content to ensure clean state, then set error message
                    controller.reset();
                    controller.setErrorState();
                }
            });
            
            return UserLoginRoute;
        });