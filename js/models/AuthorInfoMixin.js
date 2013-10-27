define(['ember'], function(Ember) {
    "use strict";

    var AuthorInfoMixin = Ember.Mixin.create({
        author: Ember.Object.create({
            medal1: '',
            medal2: '',
            medal3: '',
            name: '',
            nickname: '',
            rating: '',
            surname: ''
        })
    });

    return AuthorInfoMixin;
});