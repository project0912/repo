define(['ember', 'models/Claim', 'models/StatsMixin', 'models/LocationMixin', 'models/Tag', "utils/utils", 'utils/store'],
        function(Ember, Claim, StatsMixin, LocationMixin, Tag, Utils, Store) {
            "use strict";

            var Crisis = Ember.Object.extend(StatsMixin, LocationMixin, {
                isEdit: '',
                claims: "",
                id: '',
                author: '',
                crisisID: Ember.computed.alias('id'),
                descr: "",
                title: "",
                tagIDs: "",
                init: function() {
                    this.claims = Ember.ArrayProxy.create({
                        content: [] //holds claims for this crisis
                    });

                    this.tagIDs = Ember.ArrayProxy.create({
                        content: [] //holds claims for this crisis
                    });

                    this._super.apply(this, arguments);
                },
                tags: function() {
                    var tags = this.get('tagIDs').map(function(tagID) {
                        var tagObject = Tag.create();
                        tagObject.set('id', tagID);
                        return tagObject;
                    });

                    return tags;
                }.property('@each.tagIDs'),
                loadClaims: function() {
                    var that = this,
                            payload = {
                        type: 'crisisGetClaims',
                        crisisID: this.get('crisisID')
                    },
                    callback = function(response) {
                        if (response.error) {
//                                var errMsg = response.code ? errorHandler(response) : "Claims loading failed";
//                                that.error.showError(errMsg);
                        } else {
                            response.list.forEach(function(claim) {
                                var clm = Claim.create();
                                Utils.setModelProperties(clm, claim);

                                var tags = clm.get('tagIDs').map(function(tagID) {
                                    var tagObject = Tag.create();
                                    tagObject.set('id', tagID);

                                    return tagObject;
                                });

                                clm.set('tags', tags);

                                that.claims.addObject(clm);
                            }, this);

                            //Retrieve all authorIDs
                            that.loadAuthors();
                            that.loadClaimTags();
                        }
                    };

                    Utils.ajax.makeAJAXPostCall('router.php', payload, callback);
                },
                loadAuthors: function() {

                    var that = this,
                            authorIDs = [],
                            callback;

                    this.claims.forEach(function(claim) {
                        authorIDs.push(claim.authorID);
                    });

                    callback = function(authorInfo) {

                        var i,
                                authoredElements;

                        if (authorInfo) {
                            for (i = 0; i < authorInfo.length; i = i + 1) {

                                //Retrieve all elements (i.e. claims) authored by this authorID
                                authoredElements = that.claims.filterProperty('authorID', authorInfo[i].ID);
                                //For each, update the author fields
                                authoredElements.forEach(function(element) {
                                    element.setProperties({'author': authorInfo[i]});
                                });
                            }
                        }
                    };
                    Store.getAuthors(authorIDs, callback);
                },
                loadClaimTags: function() {
                    var tagIDs,
                            callback,
                            tagDict = {};

                    this.get('tags').forEach(function(tag) {
                        var id = tag.get('id');

                        if (tagDict[id]) {
                            tagDict[id].pushObject(tag);
                        } else {
                            tagDict[id] = [tag];
                        }
                    });

                    this.claims.forEach(function(claim) {
                        claim.get('tags').forEach(function(tag) {
                            var id = tag.get('id');

                            if (tagDict[id]) {
                                tagDict[id].pushObject(tag);
                            } else {
                                tagDict[id] = [tag];
                            }
                        });
                    });
                    
                    tagIDs = Object.keys(tagDict);
                    
                    callback = function(tagInfoList) {

                        if (tagInfoList) {
                            tagInfoList.forEach(function(tag) {
                                var id = tag.ID,
                                    tagIDs = Object.keys(tagDict);
                                    
                                if(tagIDs.contains(id)){
                                    tagDict[id].forEach(function(tagObject) {
                                        tagObject.set('name', tag.name);
                                        tagObject.set('descr', tag.short);
                                    });
                                }
                            });
                        }
                    };

                    Store.getTags(tagIDs, callback);
                }
            });

            return Crisis;
        });