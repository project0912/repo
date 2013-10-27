define(["ember", "models/Errors", "utils/utils"],
        function(Ember, Errors, Utils) {
            "use strict";
            var UserLoginController = Ember.ObjectController.extend({
                needs: ['application'],
                contentBinding: 'controllers.application.user',
                init: function() {
                    this.set('error', Errors.create());
                    this._super.apply(this, arguments);
                },
                actions: {
                    close: function() {

                        window.history.back(); //TODO find better way
                        //Clear the attempted transition
                        this.set('attemptedTransition', null);
                    },
                    goToAppropriateRoute: function() {
                        var attemptedTransition = this.get('attemptedTransition');
                        if (attemptedTransition) {
                            this.set('attemptedTransition', null);
                            attemptedTransition.retry();
                        } else {
                            // Redirect to 'index' by default.
                            this.send('close');
                        }
                    },
                    login: function() {
                        var that = this,
                                payload = {
                            type: 'login',
                            login: this.get('email'),
                            password: this.get('password')
                        },
                        validation = Utils.validatePayload(payload),
                                valErrCode = Utils.ERRORS.get('VALIDATION_ERR_CODE'),
                                callback = function(data) {

                            var loginErrCode = Utils.ERRORS.get('LOGIN_ERR_CODE'),
                                    loginErrMsg = Utils.ERRORS.get('LOGIN_ERR_MSG');
                            if (data.error) {
                                that.error.addError(loginErrCode, loginErrMsg);
                            } else {
                                that.error.resolveError(loginErrCode);
                                that.set('isLoggedIn', true);
                                localStorage.setItem('isLoggedIn', true);
                                that.setProperties(data.element);
                                that.send('goToAppropriateRoute');
                            }
                        };
                        if (validation.valid) {
                            this.error.resolveError(valErrCode);
                            Ember.$.post('router.php', payload, callback, "json");//.then(callback);
                        } else {
                            this.error.addError(valErrCode, validation.message);
                        }
                    }
                },
                reset: function() {
                    //TODO Remember to clear
                    this.setProperties({
                        email: "dkrasnoshtan@masdar.ac.ae",
                        password: "longpassword"
                    });
                    this.get('error').clearAllErrors();
                },
                setErrorState: function() {
                    var attemptedTransition = this.get('attemptedTransition');
                    var signInErrCode = Utils.ERRORS.get('SIGN_IN_ERR_CODE'),
                            signInErrMsg = Utils.ERRORS.get('SIGN_IN_ERR_MSG');

                    if (attemptedTransition) {
                        this.get('error').addError(signInErrCode, signInErrMsg);
                    }

                }

            });

            return UserLoginController;
        });
