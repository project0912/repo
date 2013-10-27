define(['ember',
    'models/AuthorInfoMixin',
    'models/StatsMixin',
    'models/LocationMixin',
    'models/Comment',
    'utils/store',
    'utils/utils',
    'jquery'],
        function(Ember, AuthorInfoMixin, StatsMixin, LocationMixin, Comment, Store, Utils, $) {
            "use strict";

            var Evidence = Ember.Object.extend(AuthorInfoMixin, LocationMixin, StatsMixin, {
                isEdit: '',
                id: '',
                author: '',
                authorID: '',
                evidenceID: Ember.computed.alias('id'),
                comments: null,
                tagList: null,
                tags: '',
                coverPhotoID: '',
                coverMediaType: '',
                init: function() {
                    this.comments = Ember.ArrayProxy.create({
                        content: []
                    });
                    this.tagList = Ember.ArrayProxy.create({
                        content: []
                    });
                    this.images = Ember.ArrayProxy.create({
                        content: []
                    });
                    this.videos = Ember.ArrayProxy.create({
                        content: []
                    });
                },
                loadComments: function() {
                    var that = this;

                    this.comments.forEach(function(comment) {
                        var cmt = Comment.create();
                        Utils.setModelProperties(cmt, comment);
                        that.comments.pushObject(cmt);
                    });
                },
                loadAuthors: function() {

                    var that = this,
                            authorIDs = [],
                            callback;

                    //add the evidence authorID
                    authorIDs.push(this.authorID);
                    //Add Comments authorIDs
                    this.comments.forEach(function(comment) {
                        authorIDs.push(comment.authorID);
                    });

                    callback = function(authorInfo) {

                        var i;

                        if (authorInfo) {
                            for (i = 0; i < authorInfo.length; i = i + 1) {

                                if (authorInfo[i].ID === that.authorID) {
                                    that.setProperties({'author': authorInfo[i]});
                                }

                                that.comments.forEach(function(comment) {

                                    if (comment.authorID === authorInfo[i].ID) {
                                        //create nice authorinfo object
                                        comment.setProperties({'author': authorInfo[i]});
                                    }
                                });
                            }
                        }

                    };
                    Store.getAuthors(authorIDs, callback);
                },
                hasCover: function() {
                    var mediaTypeNum = parseInt(this.get('coverMediaType'), 10);

                    return ($.isNumeric(mediaTypeNum) && (mediaTypeNum !== 0));
                }.property('coverMediaType')
            });

            return Evidence;
        });