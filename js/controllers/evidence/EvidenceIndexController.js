define(['ember', 'models/Comment', 'utils/utils', 'utils/store', 'controllers/UserActionMixin', 'models/Errors', 'jquery'],
        function(Ember, Comment, Utils, Store, UserActionMixin, Errors, $) {
            "use strict";
            var EvidenceIndexController = Ember.ObjectController.extend(UserActionMixin, {
                needs: ['application', 'evidence', 'claim', 'crisis'],
                init: function() {
                    this.set('error', Errors.create());
                    this._super.apply(this, arguments);
                    this.set('selected', null);
                },
                selectedImagePath: function() {
                    var firstImageID,
                            hugeImageType = Utils.CONSTANTS.get('hugeImageIdentifier');

                    if (!this.get('selected')) {
                        firstImageID = this.get('controllers.evidence.images')[0];
                        this.set('selected', firstImageID);
                    }
                    
                    if (this.get('selected')) {
                        return this.createImagePath(hugeImageType, this.get('selected'));
                    }
                }.property('controllers.evidence.images', 'selected'),
                imgPaths: function() {
                    var hugeImageType = Utils.CONSTANTS.get('hugeImageIdentifier');

                    return this.getImagePaths(hugeImageType);
                }.property('controllers.evidence.images'),
                thumbnailPaths: function() {
                    var thumbnailImageType = Utils.CONSTANTS.get('thumbnailImageIdentifier');

                    return this.getImagePaths(thumbnailImageType);
                }.property('controllers.evidence.images'),
                getImageIDFromImagePath: function(imagePath) {
                    var imageIDStartIndex = imagePath.indexOf("_") + 1,
                            imageIDEndIndex = imagePath.indexOf(".jpg");
                    return imagePath.substring(imageIDStartIndex, imageIDEndIndex);
                },
                getImagePaths: function(imageTypeIdentifier) {
                    var imageIDs = this.get('controllers.evidence.images'),
                            imagePaths;

                    //imageID is passed in as the second argument to the createImagePath function
                    imagePaths = imageIDs.map($.proxy(this.createImagePath, this, imageTypeIdentifier));

                    return imagePaths;
                },
                createImagePath: function(imageTypeIdentifier, imageID) {
                    var crisisID = this.get('controllers.crisis.id'),
                            claimID = this.get('controllers.claim.id'),
                            evidenceID = this.get('controllers.evidence.id'),
                            imagePath;

                    imagePath = Utils.CONSTANTS.get('baseImageDir') +
                            '/' + crisisID +
                            '/' + claimID +
                            '/' + evidenceID +
                            '/' + imageTypeIdentifier +
                            imageID + "." + Utils.CONSTANTS.get('jpgFileType');

                    return imagePath;
                },
                tags: function() {
                    var callback,
                            tagIDs = this.get('controllers.evidence.tags'),
                            tagList = Ember.ArrayProxy.create({
                        content: []
                    });
                    callback = function(tagInfo) {
                        if (tagInfo) {
                            tagList.pushObjects(tagInfo);
                        }
                    };
                    if (tagIDs) {
                        Store.getTags(tagIDs, callback);
                    }
                    return tagList;
                }.property('controllers.evidence.tags'),
                parentCrisisName: function() {
                    return this.get('controllers.crisis.title');
                }.property('controllers.crisis.title'),
                parentClaimName: function() {
                    return this.get('controllers.claim.title');
                }.property('controllers.claim.title'),
                userCanCreate: function() {
                    return this.get('controllers.application.user.isLoggedIn');
                }.property('controllers.application.user.isLoggedIn'),
                setupNewComment: function() {
                    var newComment = Comment.create();

                    newComment.set('author', this.get('controllers.application.user'));
                    newComment.set('authorID', this.get('controllers.application.user.id'));
                    newComment.set('evidenceID', this.get('controllers.evidence.id'));
                    newComment.set('descr', this.get('controllers.evidence.tempComment'));
                    newComment.set('flagged', 0);

                    return newComment;
                },
                reset: function() {
                    this.get('error').clearAllErrors();
                },
                pushPayloadToServer: function(payload, newComment) {

                    var that = this,
                            validation = Utils.validatePayload(payload),
                            valErrCode = Utils.ERRORS.get('VALIDATION_ERR_CODE'),
                            commentsList = this.get('controllers.evidence.comments'),
                            callback;

                    callback = function(data) {

                        var commentErrCode = Utils.ERRORS.get('COMMENT_ERR_CODE'),
                                commentErrMsg = Utils.ERRORS.get('COMMENT_ERR_MSG');

                        if (data.error) {
                            this.get('error').addError(commentErrCode, commentErrMsg);
                        } else {
                            //TODO Validate mongoID coming in from server here?
                            that.get('error').resolveError(commentErrCode);
                            newComment.set('commentID', data.commentID);
                            //Add created comment to list and wipe the textField
                            commentsList.insertAt(0, newComment);
                            that.set('controllers.evidence.tempComment', '');
                        }
                    };

                    if (validation.valid) {
                        this.get('error').resolveError(valErrCode);
                        Utils.ajax.makeAJAXPostCall('router.php', payload, callback);
                    } else {
                        this.get('error').addError(valErrCode, validation.message);
                    }

                },
                actions: {
                    editEvidence: function() {
                        this.transitionToRoute('evidence.edit');
                    },
                    backToCrisis: function() {

                        var parentCrisisContext = this.get('controllers.crisis.content');
                        this.transitionToRoute('crisis', parentCrisisContext);
                    },
                    backToClaim: function() {

                        var parentClaimContext = this.get('controllers.claim.content');
                        this.transitionToRoute('claim', parentClaimContext);
                    },
                    addComment: function() {
                        var newComment = this.setupNewComment(),
                                payload = {
                            type: 'commentAdd',
                            descr: newComment.get('descr'),
                            evidenceID: newComment.get('evidenceID')
                        };

                        this.pushPayloadToServer(payload, newComment);
                    },
                    thumbnailClicked: function(imagePath) {
                        var imageID = this.getImageIDFromImagePath(imagePath);

                        this.set('selected', imageID);
                    },
                    showNextImage: function(direction) {
                        var currentImageID = this.get('selected'),
                                imageIDs = this.get('controllers.evidence.images'),
                                currentImageIndex = imageIDs.indexOf(currentImageID),
                                offset = (direction === 'left') ? -1 : 1,
                                newImageIndex,
                                newImageID;

                        // Get the offset taking into consideration wrap-arounds due to first and last elements of arrays
                        newImageIndex = (currentImageIndex + offset + imageIDs.length) % imageIDs.length;
                        newImageID = imageIDs[newImageIndex];

                        this.set('selected', newImageID);
                    }
                }
            });
            return EvidenceIndexController;
        });