define(['jquery', 'utils/utils', 'ember'],
        function($, Utils, Ember) {
            'use strict';

            var localStorage,
                    supportLocalStorage = false,
                    requestTagsFromServer,
                    requestAuthorsFromServer,
                    getFromStorage,
                    Store = {};

            /**
             * [localStorage] Hiding from the interface of Store. This way the localStorage can be accessed only through 
             * Store.
             * The flag supportLocalStorage needs to be consulted at every Storage call in order to avoid errors.
             * 
             */
            supportLocalStorage = function() {
                try {
                    if ('localStorage' in window && window.localStorage !== null) {
                        localStorage = window.localStorage;
                        return true;
                    } else {
                        return false;
                    }
                } catch (e) {
                    return false;
                }
            }();

            getFromStorage = function(IDlist, callback, type) {

                Utils.assert(!Ember.isNone(callback), 'no callback defined');
                Utils.assert(!Ember.isNone(IDlist), 'empty IDlist array');
                Utils.assert(!Ember.isNone(type), 'type of items not defined');

                var prefix, aux,
                        mergeWithStored,
                        stored = [],
                        toBeResolved = [],
                        requestItemFromServer;

                mergeWithStored = function(items) {

                    items.forEach(function(item) {
                        //store the list in local storage
                        localStorage[prefix + item.ID] = JSON.stringify(item);
                        //merge the lists in store
                        stored.push(item);
                    });
                    //return the list to the caller
                    callback(stored);
                };

                switch (type) {
                    case 'tag':
                        requestItemFromServer = requestTagsFromServer;
                        prefix = 'tag.';
                        break;
                    case 'author':
                        requestItemFromServer = requestAuthorsFromServer;
                        prefix = 'autor.';
                        break;
                    default:
                        Utils.assert(false, 'Unsupported type');
                }

                if (supportLocalStorage) {
                    // Check how many items are stored in localStorage. Resolve the rest.
                    IDlist.forEach(function(itemID) {
                        aux = localStorage[prefix + itemID];

                        if (aux) {
                            stored.push(JSON.parse(aux));
                        } else {
                            toBeResolved.push(itemID);
                        }
                    });

                    if (toBeResolved.length) {
                        //call only if there are unresolved itemIDs
                        requestItemFromServer(mergeWithStored, toBeResolved);
                    } else {
                        //all the authorIDs are stored in localStorage
                        callback(stored);
                    }

                } else {
                    requestItemFromServer(mergeWithStored, IDlist);
                }

            };

            Store.getAuthors = function(authorIDs, callback) {
                getFromStorage(authorIDs, callback, 'author');
            };

            Store.getTags = function(tagIDs, callback) {
                getFromStorage(tagIDs, callback, 'tag');
            };

            //in order to make the inreface uniform from getItems the taglist is
            requestTagsFromServer = function(getTagsCallback) {
                var prepareData,
                        payloadTags = {
                    type: 'tagsGetListBrief'
                };

                prepareData = function(data) {
                    getTagsCallback(data.list);
                };

                Utils.ajax.makeAJAXPostCall('router.php', payloadTags, prepareData);
            };

            Store.getAllTags = function(callback) {
                return requestTagsFromServer(callback);
            };

            requestAuthorsFromServer = function(getAutorsCallback, authorIDs) {
                var prepareData, payload = {
                    type: 'usersBrief',
                    userIDs: authorIDs
                };
                prepareData = function(data) {
                    getAutorsCallback(data.list);
                };
                Utils.ajax.makeAJAXPostCall('router.php', payload, prepareData);
            };

            Store.resolveGeoData = function(callback) {

                $.get('js/utils/resources/cities.txt', function(data) {
                    callback($.parseJSON(data));
                });
            };

            return Store;

        });