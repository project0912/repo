define(['ember', 'jquery'], function(Ember, $) {
    'use strict';
    var CrisesMapView = Ember.View.extend({
        map: null,
        markers: '',
        init: function() {
            var that = this;
            this._super.apply(this, arguments);
            this.set('markers', []);
            // Subscribe to markers information
            $.subscribe('updateMapMarkers', $.proxy(this.updateMap, this));
        },
        clearOldMarkers: function() {
            this.get('markers').forEach(function(marker) {
                marker.setMap(null);
            });
            
            //Wipe the array
            this.get('markers').clear();
        },
        createMarkers: function(newMarkersArray) {
            var that = this,
                    point,
                    marker;
    
            newMarkersArray.forEach(function(latLng) {
                point = new google.maps.LatLng(latLng[0], latLng[1]);
                marker = new google.maps.Marker({
                    position: point,
                    map: that.get('map')
                });
                that.get('markers').pushObject(marker);
            });
        },
        updateMap: function(ev, newMarkersArray) {
            this.clearOldMarkers();
            this.createMarkers(newMarkersArray);
        },
        didInsertElement: function() {
            var map,
                    mapOptions = {
                zoom: 2, //Change this to use a constant!
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControl: false, //no switching between satelite and map
                center: new google.maps.LatLng(-34.397, 150.644),
                streetViewControl: false//Turn off street view
            };
            map = new google.maps.Map(this.$().get(0), mapOptions);
            google.maps.event.addListener(map, "bounds_changed",
                    $.proxy(this.mapBoundariesChanged, this, map));

            this.set('map', map);
        },
        mapBoundariesChanged: function(mapObject) {
            var mapStateInfo = {
                lat1: mapObject.getBounds().getNorthEast().lat(), //Top
                lat2: mapObject.getBounds().getSouthWest().lat(), //bottom
                lng1: mapObject.getBounds().getNorthEast().lng(), //right 
                lng2: mapObject.getBounds().getSouthWest().lng(), //left, 
                zoom: mapObject.getZoom()
            };
            this.get('controller').send('updateDisplay', mapStateInfo);
        }

    });
    return CrisesMapView;
});