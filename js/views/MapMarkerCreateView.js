define(['ember'], function(Ember) {
    'use strict';
    var MapMarkerCreateView = Ember.View.extend({
        mapOptions: {
            center: new google.maps.LatLng(37.222, 23.1223),
            zoom: 4,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        },
        init: function() {

            this._super.apply(this, arguments);
            this.set('marker', '');
        },
        didInsertElement: function() {
            this.drawMap();
        },
        drawMap: function() {
            var that = this,
                    lat = this.get('lat'),
                    lng = this.get('lng'),
                    centreLat = this.get('centreLat'),
                    centreLng = this.get('centreLng'),
                    marker,
                    latLng,
                    map = new google.maps.Map(this.$().get(0), this.get('mapOptions')),
                    mapHasMarker = (!!lat && !!lng);


            if (mapHasMarker) {
                latLng = new google.maps.LatLng(lat, lng);
                marker = new google.maps.Marker({
                    position: latLng,
                    map: map
                });
                //If latLng coords exist; set map centre to this location and show a marker
                map.setCenter(latLng);
                that.set('marker', marker);
            } else {
                if (centreLat && centreLng) {
                    latLng = new google.maps.LatLng(centreLat, centreLng);
                    map.setCenter(latLng);
                }
            }

            this.setupMarkerCreation(map);
        },
        setupMarkerCreation: function(map) {
            var that = this;
            
            google.maps.event.addListener(map, 'click', function(event) {

                var marker = that.get('marker'),
                        newMarker;
                if (marker) {
                    marker.setMap(null);
                }

                newMarker = new google.maps.Marker({
                    position: event.latLng,
                    map: map
                });
                that.set('marker', newMarker);
            });
        },
        markerDidChange: function() {

            if (this.get('marker')) {
                var newLat = this.marker.position.lat(),
                        newLng = this.marker.position.lng();
                this.set('lat', newLat);
                this.set('lng', newLng);
            }

        }.observes("this.marker"),
        latLngDidChange: function() {
            this.drawMap();
        }.observes('this.centreLat', 'this.centreLng')
    });
    return MapMarkerCreateView;
});