/**
 * Defines an assertion that will throw an Exception if the conditions are not met.
 * All asserts will be removed from production code.
 * 
 * ```javascript
 * // Test for validity
 * App.assert(value, "value must be set");
 * // Failed Assert
 * App.assert(false, "This must fail all the time);
 * 
 * @method assert
 * @param {Boolean} condition This must be truthy for the assertion to pass;
 * otherwise an Exception is thrown.
 * @param {String} desc A description of the assertion; this is passed into
 * the Error that is thrown when the assertion fails.
 *
 */
define(["ember"],
        function(Ember) {
            'use strict';

            var Assert = function(condition, description) {
                Ember.assert(description, condition);

                //Ensure that an exception is thrown when asserts fail
                if (!condition) {
                    throw new Error("Assertion failed: " + description);
                }
            };

            return Assert;
        });