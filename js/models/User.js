define(["ember", "utils/utils"],
        function(Ember, Utils) {
            "use strict";

            var User = Ember.Object.extend({
                id: "",
                name: "",
                surname: "",
                nickname: "",
                email: "dkrasnoshtan@masdar.ac.ae",
                ID: Ember.computed.alias('id'),
                isLoggedIn: false,
                isLoading: false,
                password: "longpassword",
                getCurrentUserInfo: function() {
                    
                    var that = this,
                            payload = {
                        type: 'currentUserInfo'
                    },
                    callback = function(data) {
                        if (!data.error) {
                            that.set('isLoggedIn', true);
                            that.setProperties(data.element);
                            localStorage.setItem('isLoggedIn',true);
                        } else {
                            that.set('isLoggedIn', false);
                            localStorage.setItem('isLoggedIn',false);
                        }
                    };

                    Utils.ajax.makeAJAXPostCall('router.php', payload, callback);
                }
            });

            return User;
        });
