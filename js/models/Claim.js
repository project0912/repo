define(['ember',
    'models/AuthorInfoMixin',
    'models/StatsMixin',
    'models/LocationMixin',
    'models/Evidence',
    'utils/utils',
    'utils/store',
    'jquery'],
        function(Ember, AuthorInfoMixin, StatsMixin, LocationMixin, Evidence, Utils, Store, $) {
            "use strict";

            var Claim = Ember.Object.extend(AuthorInfoMixin, StatsMixin, LocationMixin, {
                isEdit: '',
                evidSupport: '',
                author: '',
                authorID: '',
                id: '',
                crisisID: '',
                claimID: Ember.computed.alias('id'),
                descr: "",
                evidences: null,
                title: "",
                coverPhotoID: '',
                coverMediaType: '',
                coverEvidenceID: '',
                tagIDs: '',
                init: function() {
                    this.evidences = Ember.ArrayProxy.create({
                        content: []
                    });

                    this.tagIDs = Ember.ArrayProxy.create({
                        content: []
                    });
                    
                    this.tags = Ember.ArrayProxy.create({
                        content: []
                    });

                    this._super.apply(this, arguments);
                },
                loadEvidences: function() {
                    var that = this,
                            payload = {
                        type: 'claimGetEvidences',
                        claimID: this.get('claimID')
                    },
                    callback = function(response) {
                        if (response.error) {
//                                var errMsg = response.code ? errorHandler(response) : "Claims loading failed";
//                                that.error.showError(errMsg);
                        } else {
                            response.list.forEach(function(evidence) {
                                var evid = Evidence.create();
                                Utils.setModelProperties(evid, evidence);

                                that.evidences.addObject(evid);
                            }, this);

                            //Retrieve all authorIDs
                            that.loadAuthors();
                        }
                    };

                    Utils.ajax.makeAJAXPostCall('router.php', payload, callback);
                },
                loadAuthors: function() {

                    var that = this,
                            authorIDs = [],
                            callback;

                    //Add Claim authorID
                    authorIDs.push(this.authorID);

                    callback = function(authorInfo) {

                        var i,
                                len = authorInfo.length;

                        if (authorInfo) {
                            for (i = 0; i < len; i = i + 1) {
                                if (authorInfo[i].ID === that.authorID) {
                                    that.set('author', authorInfo[i]);
                                    break;
                                }

                            }
                        }
                    };
                    Store.getAuthors(authorIDs, callback);
                },
                saveClaimToServer: function(callback) {

                    Utils.assert(!Ember.isNone(callback), "Callback is not defined");

                    var payload = {
                        type: 'claimCreate',
                        crisisID: this.get('crisisID'),
                        title: this.get('title'),
                        descr: this.get('descr'),
                        lat: this.get('lat'),
                        lng: this.get('lng'),
                        street: this.get('street'),
                        cityID: this.get('cityID'),
                        countryID: this.get('countryID'),
                        tags: []
                    },
                    onSaveCallback = function(data) {
                        if (data.error) {
//                            var errMsg = data.code ? errorHandler(data) : "Claim creation failed";
//                            that.error.showError(errMsg);
                        } else {
                            if (callback) {
                                callback(data.ID); //Send back the claim ID
                            }
                        }
                    };

                    Utils.ajax.makeAJAXPostCall('router.php', payload, onSaveCallback);
                },
                hasCover: function() {
                    var mediaTypeNum = parseInt(this.get('coverMediaType'), 10);

                    return ($.isNumeric(mediaTypeNum) && (mediaTypeNum !== 0));
                }.property('coverMediaType')
            });

            return Claim;
        });
