define(['ember', 'utils/utils'], function(Ember, Utils) {
    'use strict';

    var MapPolygonCreateView = Ember.View.extend({
        polygonIsClosed: '',
        mapOptions: {
            center: new google.maps.LatLng(37.222, 23.1223),
            zoom: 4,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        },
        polygonOptions: {
            strokeColor: "#FF0000",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#FF0000",
            fillOpacity: 0.5
        },
        init: function() {

            this.set('polygonIsClosed', false);
            this._super.apply(this, arguments);
        },
        didInsertElement: function() {

            this.drawPolygon();
        },
        markersDidChange: function() {

            this.drawPolygon();
        }.observes('this.controller.markers'),
        updateMarkers: function(polygon) {

            var polygonMarkersArray = polygon.getPath().getArray(),
                    processedMarkersArray = polygonMarkersArray.map(function(latLng) {
                return [latLng.lat(), latLng.lng()];
            });

            this.set('controller.markers', processedMarkersArray);
        },
        drawPolygon: function() {

            var polygonExists = (this.get('controller.markers.length') > 0),
                    map = new google.maps.Map(this.$().get(0), this.get('mapOptions'));
            
            if(this.lat && this.lng){
                var latLng = new google.maps.LatLng(this.lat, this.lng);
                map.setCenter(latLng);
                map.setZoom(9);//TODO change to zoom.
            }

            if (polygonExists) {
                this.showPolygon(map);
            } else {
                this.set('polygonIsClosed', false);
                this.setupPolygon(map);
            }
        },
        closePolygon: function(map, polygon) {

            Utils.assert(!Ember.isNone(polygon), "Polygon is not defined");

            var path = polygon.getPath(),
                    newPolygon;

            //Wipe old polygon and create a new one
            polygon.setMap(null);
            newPolygon = new google.maps.Polygon({map: map, path: path});
            newPolygon.setOptions(this.polygonOptions);
            this.updateMarkers(newPolygon);
        },
        showPolygon: function(map) {
            
            Utils.assert(!Ember.isNone(map), "Map is not defined");

            var polygonCoords = [],
                    point,
                    polygon = new google.maps.Polygon(this.get('polygonOptions'));

            this.get('controller.markers').forEach(function(latLng) {
                point = new google.maps.LatLng(latLng[0], latLng[1]);
                polygonCoords.push(point);
            });

            polygon.setPath(polygonCoords);
            polygon.setMap(map);
        },
        setupPolygon: function(map) {

            var that = this,
                    polygon = new google.maps.Polyline(this.get('polygonOptions'));

            polygon.setMap(map);

            google.maps.event.addListener(map, 'click', function(clickEvent) {

                if (that.get('polygonIsClosed')) {
                    //return immediately when polygon is closed.
                    return;
                }

                var markerIndex = polygon.getPath().length,
                        isFirstMarker = (markerIndex === 0),
                        marker = new google.maps.Marker({map: map, position: clickEvent.latLng, draggable: false});
                //Draggable css clashes with twitter bootstrap making it appear stretched

                google.maps.event.addListener(marker, 'click', function() {
                    // A polygon must have at least 3 points and be closed
                    // Check if this marker is the first marker (this is a closure from above declaration)
                    // If it is and the polgon is not closed and there are at least three markers
                    // on the map then close the polygon
                    if (isFirstMarker && !that.get('polygonIsClosed') && polygon.getPath().length >= 3) {
                        that.closePolygon(map, polygon);
                        that.set('polygonIsClosed', true);
                    }
                });

                polygon.getPath().push(clickEvent.latLng);
            });
        },
        latLngDidChange: function() {
            this.drawPolygon();
        }.observes('this.lat', 'this.lng')
    });

    return MapPolygonCreateView;
});