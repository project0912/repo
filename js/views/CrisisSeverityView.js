define(['ember'],
        function(Ember) {
            "use strict";

            var CrisisSeverityView = Ember.View.extend({
                templateName: 'crisis/severity',
                prevVote: function() {
                    return this.get('controller.controllers.crisis.castedSeverityVote');
                }.property('controller.controllers.crisis.castedSeverityVote')
            });

            return CrisisSeverityView;
        });