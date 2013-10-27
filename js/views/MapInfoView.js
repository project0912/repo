define(['ember'], function(Ember) {
    'use strict';

    var MapInfoView = Ember.View.extend({
        map: null,
        mapCentreMarker: null,
        mapPolygon: null,
        didInsertElement: function() {
            // Some of these can be moved to the init call?
            var mapOptions = {
                zoom: 2, //Change this to use a constant!
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControl: false,//no switching between satelite and map
                streetViewControl: false//Turn off street view
            };
            this.map = new google.maps.Map(this.$().get(0), mapOptions);
            this.mapCentreMarker = new google.maps.Marker({
                map: this.map
            });
            this.mapPolygon = new google.maps.Polygon({
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#FF0000",
                fillOpacity: 0.35
            });
            
            //Force a call to display map if information is already available
            this.mapPointerDidChange();
            this.mapPolygonDidChange();
        },
        mapPointerDidChange: function() {
            if (this.lat && this.lng) {
                var center = new google.maps.LatLng(this.lat, this.lng);

                this.map.setCenter(center);
                this.mapCentreMarker.setPosition(center);
            }
        }.observes('this.lat', 'this.lng'),
        mapPolygonDidChange: function() {
            if (this.markers) {
                this.drawPolygon();
            }
        }.observes('this.markers'),
        drawPolygon: function() {
            
            var polygonCoords = [],
                    point;

            this.markers.forEach(function(latLng) {
                point = new google.maps.LatLng(latLng[0], latLng[1]);
                polygonCoords.push(point);
            });

            this.mapPolygon.setPath(polygonCoords);
            this.mapPolygon.setMap(this.map);
        }
    });

    return MapInfoView;
});