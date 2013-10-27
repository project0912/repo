define(['ember', "models/Error"],
        function(Ember, Error) {
            "use strict";

            //Contains a list of errors and allows for operations on them
            var Errors = Ember.ArrayProxy.extend({
                content: [],
                hasErrors: function() {

                    var unresolvedErrors = this.filterProperty('resolved', false);

                    return unresolvedErrors.get('length') > 0;
                }.property('@each.resolved'),
                addError: function(code, message) {

                    var err,
                            errObj = this.findProperty('code', code);

                    if (errObj) {
                        errObj.set('message', message);
                    } else {
                        err = Error.create({
                            code: code,
                            message: message,
                            resolved: false
                        });

                        this.pushObject(err);
                    }

                },
                resolveError: function(code) {

                    var errObj = this.findProperty('code', code);
                    if (errObj) {
                        errObj.set('resolved', true);
                        this.removeObject(errObj);
                    }
                },
                errorMessage: function() {
                    return this.get('firstObject.message');
                }.property('@each.message'),
                clearAllErrors: function() {
                    this.clear();
                }

            });

            return Errors;
        }
);

        