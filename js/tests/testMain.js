(function(root) {
    'use strict';

    require.config({
        baseUrl: '../', //set it correctly if you move it from here
        paths: {
            QUnit: 'libs/qunit',
            ember: 'libs/ember',
            jquery: 'libs/jquery.min',
            handlebars: 'libs/handlebars'
        },
        shim: {
            'QUnit': {
                exports: 'QUnit',

                init: function() {
                    QUnit.config.autoload = false;
                    QUnit.config.autostart = false;
                }

            },
	        'ember': {
	            deps: [
	                'handlebars',
	                'jquery'
	            ],
	            exports: 'Ember'
	        	}
	    }
    });

    require(['QUnit', 'tests/validatorTests','utils/utils'],
        function(QUnit, validatorTests,Utils) {
/**
 * REMOVE THIS MESS WHEN DISCUSSED HOW THE TESTING WILL GO ON
 */
            var AppName = "Testing";
            root[AppName] = Ember.Application.create({
            	Utils:Utils
            });

            // run the tests.
            
            // start QUnit.
            QUnit.load();
            QUnit.start();

            //randomly generated tests should run here, otherwise the whole data will be the same.
            validatorTests.run();
        });
}(this));
