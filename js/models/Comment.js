define(['ember', 'models/AuthorInfoMixin'], 
	function(Ember, AuthorInfoMixin) {
    "use strict";

    var Comment = Ember.Object.extend(AuthorInfoMixin, {
        id: '',
        descr: '',
        evidenceID: '',
        commentID: Ember.computed.alias('id')
    });

    return Comment;
});
