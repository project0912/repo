/**
 * ApplicationRoute
 * The ApplicationRoute module.
 */

define(['ember', 'models/User', 'utils/utils'],
        function(Ember, User, Utils) {
            "use strict";

            var ApplicationRoute = Ember.Route.extend({
                setupController: function(controller, model) {

                    var user = User.create();
                    user.getCurrentUserInfo();
                    controller.set('user', user);
                },
                actions: {
                    logout: function() {

                        var that = this,
                            payload = {
                            type: 'logout'
                            },
                            callback = function(logoutStatus) {
                                //Wipe the user object with an empty one
                                if (logoutStatus) {
                                    that.controller.set("user", User.create());
                                    localStorage.setItem('isLoggedIn',false);
                                }
                            };

                        Utils.ajax.makeAJAXPostCall('router.php', payload, callback);
                    }
                }
            });

            return ApplicationRoute;
        });