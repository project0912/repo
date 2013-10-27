define(['ember'], function(Ember) {
    "use strict";

    var Tag = Ember.Object.extend({
        id: '',
        name: 'Testing',
        descr: 'Test description'
    });
    
    return Tag;
});
