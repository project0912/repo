/**
 * CrisisRoute
 * The CrisisRoute module.
 */

define(['ember','models/Crisis'],
        function(Ember,Crisis) {
            "use strict";

            var CrisisRoute = Ember.Route.extend({
                loadedCrisis: '',
                setupController: function(controller, crisis) {
                    if (this.loadedCrisis !== crisis.id){
                        this.loadedCrisis = crisis.id;
                        crisis = Crisis.find(crisis.id);
                        this.controllerFor('crisis').set('model', crisis);
                    }
                }
            });

            return CrisisRoute;
        });