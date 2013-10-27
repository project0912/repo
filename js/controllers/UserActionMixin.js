define (['ember', 'utils/utils', 'jquery'],
        function(Ember, Utils, $) {
            'use strict';

            var UserActionMixin = Ember.Mixin.create({
                needs: ['application'],
                showShareView: null,
                init: function(){
                        this.set('showShareView', false);
                        this._super.apply(this, arguments);
                },
                actions: {
                    bookmarkItem: function() {
                        var instanceType, payload, element, aux,
                        user = this.get('controllers.application.user');

                        if (!user.get('isLoggedIn'))
                            return;

                        aux = this.itemType();

                        element = aux.element;
                        instanceType = aux.instanceType;

                        payload = {
                            type: 'statisticsBookmark',
                            instanceType: instanceType,
                            elementID: element.get('id'),
                            undo: element.get('castedBookmark')
                        };

                        Utils.ajax.makeAJAXPostCall('router.php', payload, function(response) {
                            if (!response.error) {
                                //update the castedBookmark
                                var total = element.get('bookmarksCount'),
                                        casted = element.get('castedBookmark');
                                casted = (casted + 1) % 2;

                                if (casted) {
                                    total = total + 1;
                                } else {
                                    total = total - 1;
                                }
                                element.set('castedBookmark', casted);
                                element.set('bookmarksCount', total);
                            }
                        });
                    },
                    upVoteItem: function() {
                        var instanceType, element, aux, payload,
                            user = this.get('controllers.application.user');

                        if (!user.get('isLoggedIn'))
                            return;

                        aux = this.itemType();

                        instanceType = aux.instanceType;
                        element = aux.element;

                        //you can't both agree and disagree, so you need to revoke your disagree
                        if (element.get('castedDisagree') === 1) {
                            alert("You can't agree and disagree at the same time");
                            return;
                        }

                        payload = {
                            type: 'statisticsAgree',
                            instanceType: instanceType,
                            elementID: element.get('id'),
                            undo: element.get('castedAgree')
                        };

                        Utils.ajax.makeAJAXPostCall('router.php', payload, function(response) {
                            if (!response.error) {
                                //update the castedBookmark
                                var total = element.get('agreesCount'),
                                        casted = element.get('castedAgree');
                                casted = (casted + 1) % 2;

                                if (casted) {
                                    total = total + 1;
                                } else {
                                    total = total - 1;
                                }
                                element.set('castedAgree', casted);
                                element.set('agreesCount', total);
                            }
                        });
                    },
                    downVoteItem: function() {
                        var instanceType, element, aux, payload,
                            user = this.get('controllers.application.user');

                        if (!user.get('isLoggedIn'))
                            return;

                        aux = this.itemType();

                        instanceType = aux.instanceType;
                        element = aux.element;

                        //you can't both agree and disagree, so you need to revoke your agree
                        if (element.get('castedAgree') === 1) {
                            alert("You can't agree and disagree at the same time");
                            return;
                        }

                        payload = {
                            type: 'statisticsDisagree',
                            instanceType: instanceType,
                            elementID: element.get('id'),
                            undo: element.get('castedDisagree')
                        };

                        Utils.ajax.makeAJAXPostCall('router.php', payload, function(response) {
                            if (!response.error) {
                                //update the castedBookmark
                                var total = element.get('disagreesCount'),
                                        casted = element.get('castedDisagree');
                                casted = (casted + 1) % 2;

                                if (casted) {
                                    total = total + 1;
                                } else {
                                    total = total - 1;
                                }
                                element.set('castedDisagree', casted);
                                element.set('disagreesCount', total);
                            }
                        });
                    },
                    setSeverity: function(level) {
                        var element, aux, 
                            user = this.get('controllers.application.user');

                        if (!user.get('isLoggedIn'))
                            return;
                        aux = this.itemType();

                        element = aux.element;
                        element.set('castedSeverity', 1);
                        element.set('castedSeverityVote', level);
                    },
                    toggleShareLink: function () {
                        var shareVewState = this.get('showShareView');
                        this.set('showShareView', !shareVewState);
                    },
                    shareOnFacebook: function(){
                        var that = this;
                        window.open(
                          'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(that.generatedLink()), 
                          'facebook-share-dialog', 
                          'width=626,height=436');
                        console.log(this.generatedLink());
                        return false;
                    }
                },
                /*
                    Generates the payload to be sent to the server. This payload is used when you:
                        1. Generate the link in a human readable form for the user.
                        2. When the payload is sent to the server so that can update the user profile (that the user has shared the link)
                 */
                getLinkProperties: function(){
                        var payload, element, instanceType, elementType, ids,
                            user = this.get('controllers.application.user'),
                            aux = this.itemType();

                        element = aux.element;
                        instanceType = aux.instanceType;
                        //Determine what are you sharing
                        switch(instanceType){//TODO make the id retreival simple
                                case 'evidence':
                                        elementType = 3;
                                        break;
                                case 'claim':
                                        elementType = 2;
                                        break;
                                case 'crisis':
                                        elementType = 1;
                                        break;
                                default:
                                    Utils.assert(false, 'Failed to determine artifact type for sharing the link');
                        };

                        payload = {
                            type: 'sharesView',
                            elementID: element.get('id'),
                            elementType: elementType
                        }

                        if (user.get('isLoggedIn')){
                            payload.userID = user.id;
                        }

                        return payload;
                },
                generatedLink: function(){
                    var link, payload = this.getLinkProperties();
                    //temporary really ugly solution
                    link = window.location.host;
                    if (payload.userID){
                        link += '/#/s/' + payload.userID + '/' + payload.elementID + '/' + payload.elementType; 
                    }else{
                        link += '/#/s/' + payload.elementID + '/' + payload.elementType;    
                    }
                    return link;
                },
                itemType: function() {
                    var instanceType, element,
                        currentPath = this.get('controllers.application.currentPath');
                    if (currentPath === 'crisis.claim.evidence.index') {
                        instanceType = 'evidence';
                        element = this.get('controllers.evidence');
                    } else if (currentPath === 'crisis.claim.index') {
                        element = this.get('controllers.claim');
                        instanceType = 'claim';
                    } else if (currentPath === 'crisis.index') {
                        instanceType = 'crisis';
                        element = this.get('controllers.crisis');
                    }
                    return {
                        instanceType: instanceType,
                        element: element
                    };
                }
            });

            return UserActionMixin;

        });