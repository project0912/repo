define(['ember'],
        function(Ember) {
            "use strict";
            
            //A single Error object contains the error message and its code
            var Error = Ember.Object.extend({
                id: '',
                code: Ember.computed.alias('id'),
                message: '',
                resolved: false     //If the error has been resolved
            });
            
            return Error;
        }
);

        