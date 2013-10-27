define(['ember', 'models/Evidence'],
        function(Ember, Evidence) {
            "use strict";

            var EvidenceRoute = Ember.Route.extend({
                loadedEvidence: '',
                setupController: function(controller, evidence) {
                    
                    if (this.loadedEvidence !== evidence.id) {
                        this.loadedEvidence = evidence.id;
                        evidence = Evidence.find(evidence.id);
                        this.controllerFor('evidence').set('model', evidence);
                    }
                }
            });

            return EvidenceRoute;
        });