/**
 * JavaScript commonly uses closures to define CONSTANTS. We use this module to 
 * define a unique implementation of such constructs. 
 *
 * Example 
 * CONSTANTS.get('key') returns the value of key
 * 
 * All constants must be defined in the values object as there are no setter 
 * methods available.
 *
 * @name CONSTANT
 * @class Constant
 * @returns returns a getter that will look up the value of the constant after 
 * passing the key of that constant as a parameter
 */

define(function() {
    'use strict';

    var CONSTANTS = (function() {
        var values = {
            minute: 60000,

            //validation related
            minPasswordLen: 5,
            minTitleLen: 10,
            maxTitleLen: 140,
            minDescrLen: 20,
            maxDescrLen: 512,
            minZoomLevel: 1,
            maxZoomLevel: 20,
            mongoIDLen: 24,
            minCountryID: 1,
            maxCountryID: 64000,
            minCityID: 1,
            maxCityID: 64000,
            minLat: -90,
            maxLat: 90,
            minLng: -180,
            maxLng: 180,
            minMarkerNumber: 3,
            minStreetLen: 2,
            maxStreetLen: 100,
            minSeverityLevel: 1,
            maxSeverityLevel: 5,
            minEmailLen: 5,
            geoDataURL: "target/utils/resources/geoData.json",
            tagsDataURL: "target/utils/resources/tags.json",
            bigImageIdentifier: 'b_',
            hugeImageIdentifier: 'h_',
            thumbnailImageIdentifier: 's_',
            coverImageIdentifier: 'm_',
            baseImageDir: 'img/upload',
            jpgFileType: 'jpg'
        };

        return {
            get: function(name) {
                return values[name];
            }
        };
    }());

    return CONSTANTS;
});