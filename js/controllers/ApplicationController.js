define(["ember", "jquery", "utils/constants"],
        function(Ember, $, CONSTANTS) {
            "use strict";

            var ApplicationController = Ember.ObjectController.extend({
                user: "",
                geoData: '',
                tags: '',
                init: function() {
                    this._super.apply(this, arguments);
                    this.loadGeoData();
                    this.loadTags();
                },
                loadGeoData: function() {
                    var that = this;
                    
                    $.ajax({
                        url: CONSTANTS.get('geoDataURL'),
                        dataType: "json",
                        type: 'get',
                        success: function(data) {
                            that.set('geoData', data);
                        },
                        error: function(xhr, status, error) {
                            //TODO log this silently and raise issue?
                            that.set('geoData', []);
                        }
                    });
                },
                loadTags: function (){
                    var that = this;
                    
                    $.ajax({
                        url: CONSTANTS.get('tagsDataURL'),
                        dataType: "json",
                        type: 'get',
                        success: function(data) {
                            that.set('tags', data);
                        },
                        error: function(xhr, status, error) {
                            //TODO log this silently and raise issue?
                            that.set('tags', []);
                        }
                    });
                },
                updateCurrentPath: function() {
                    App.set('currentPath', this.get('currentPath'));
                }.observes('currentPath')
            });

            return ApplicationController;
        });
