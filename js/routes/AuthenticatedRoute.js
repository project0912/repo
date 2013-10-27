define(['ember'],
        function(Ember) {
            "use strict";

            var AuthenticatedRoute = Ember.Route.extend({
                beforeModel: function(transition) {
                    //this.controllerFor('application').get('user.isLoggedIn')
                    if ( localStorage.getItem('isLoggedIn') === "false" ) {
                        this.redirectToLogin(transition);
                    }
                },
                redirectToLogin: function(transition) {
                    var loginController = this.controllerFor('UserLogin');
                    loginController.set('attemptedTransition', transition);
                    this.transitionTo('user.login');
                }
            });

            return AuthenticatedRoute;
        });