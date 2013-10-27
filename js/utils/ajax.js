define(["jquery",'utils/asserts'],
        function($,assert) {
            'use strict';

            var Ajax;

            Ajax = {};
            Ajax.makeAJAXPostCall = function(destination, payload, callback) {

                assert(typeof(destination) === 'string', 'makeAJAXPostCall missing destination');
                assert(payload !== undefined || payload !== null, 'makeAJAXPostCall undefined or null payload');
                assert(typeof(callback) === 'function', 'makeAJAXPostCall callback is not a function');

                $.ajax({
                    url: destination,
                    type: "POST",
                    data: payload,
                    dataType: 'json', 
                    success: function(data) {
                        if (callback) {
                            callback(data);
                        }
                    },
                    error: function(jqxhr,status,err) {
                        console.log("ajaxPostFailed "+jqxhr+' status: '+status+' err'+err);
                        console.log(arguments);
                    }
                });
            };

            return Ajax;
        });
