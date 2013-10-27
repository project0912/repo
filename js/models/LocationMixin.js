define(['ember'], function(Ember) {
    "use strict";

    var LocationMixin = Ember.Mixin.create({
        lat: '',
        lng: '',
        street: '',
        city: '',
        cityID: '',
        country: '',
        countryID: '',
        markers: ''
    });
    return LocationMixin;
});