define(['ember', 'models/Errors'], function(Ember, Errors) {
    "use strict";
    var EvidenceController = Ember.ObjectController.extend({
        tempComment: '',
        init: function() {
            this._super.apply(this, arguments);        
        }
    });

    return EvidenceController;
});