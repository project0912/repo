define(["ember",
    "models/Crisis",
    "models/Claim",
    "models/Evidence",
    "utils/utils",
    "jquery"],
        function(Ember, Crisis, Claim, Evidence, Utils, $) {
            "use strict";

            var IndexController = Ember.ObjectController.extend({
                recent: '',
                top: '',
                markers: '',
                init: function() {
                    var that = this,
                            recent,
                            top,
                            markers;

                    recent = Ember.ArrayProxy.create({
                        content: []
                    });

                    top = Ember.ArrayProxy.create({
                        content: []
                    });

                    markers = Ember.ArrayProxy.create({
                        content: []
                    });

                    this.set('recent', recent);
                    this.set('top', top);
                    this.set('markers', markers);

                    var payload = {
                        type: 'getPoints'
                    },
                    callback = function(data) {
                        var claim, i;
                        for (i = 0; i < 3; i = i + 1) {
                            claim = Claim.create(data.list[i]);
                            claim.set('id', claim.get('elementID'));
                            that.get('top').pushObject(claim);
                        }
                        for (i = 3; i < 6; i = i + 1) {
                            claim = Claim.create(data.list[i]);
                            claim.set('id', claim.get('elementID'));
                            that.get('recent').pushObject(claim);
                        }
                    };
                    Utils.ajax.makeAJAXPostCall('router.php', payload, callback);
                },
                createInfoType: function(dataPack) {
                    var type = dataPack.type,
                            id = dataPack.elementID,
                            created;

                    //remove fields that have no mapping to JS object fields
                    delete dataPack.type;
                    delete dataPack.elementID;

                    if (type === 'crisis') {
                        created = Crisis.create(dataPack);
                    } else if (type === 'claim') {
                        created = Claim.create(dataPack);
                    } else if (type === 'evidence') {
                        created = Evidence.create(dataPack);
                    }

                    if (created) {
                        created.set('id', id);
                    }

                    return created;
                },
                actions: {
                    updateDisplay: function(mapStateInfo) {

                        var that = this,
                                payload = $.extend(mapStateInfo, {type: 'getPoints'}),
                        callback = function(data) {
                            var infoType, i;

                            that.get('top').clear();
                            that.get('recent').clear();
                            that.get('markers').clear();

                            var rand = Math.floor(Math.random() * 5);

                            for (i = 0; i < rand; i = i + 1) {
                                infoType = that.createInfoType(data.list[i]);
                                that.get('top').pushObject(infoType);
                                that.get('markers').pushObject([infoType.get('lat'), infoType.get('lng')]);
                            }
                            for (i = rand; i < (rand * 2); i = i + 1) {
                                infoType = that.createInfoType(data.list[i]);
                                that.get('recent').pushObject(infoType);
                                that.get('markers').pushObject([infoType.get('lat'), infoType.get('lng')]);
                            }

                            //Publish the update markers function so view can update accordingly
                            $.publish('updateMapMarkers', that.get('markers'));
                        };

                        Utils.ajax.makeAJAXPostCall('router.php', payload, callback);
                    }
                }
            });

            return IndexController;
        });
