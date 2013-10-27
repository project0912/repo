define(['ember', 'controllers/UserActionMixin', 'models/Tag', 'utils/utils'], function(Ember, UserActionMixin, Tag, Utils) {
    "use strict";
    var CrisisIndexController = Ember.ObjectController.extend(UserActionMixin, {
        isLoading: false, //For displaying a spinner
        isUpdate: false, //Check if this is being updated
        showSeverityView: null,
        init: function() {
            this.set('showSeverityView', false);
            this._super.apply(this, arguments);
        },
        needs: ['crisis', 'application'],
        crisisID: function() {
            return this.get('controllers.crisis.id');
        }.property('crisis.id'),
        actions: {
            editCrisis: function() {
                this.set('controllers.crisis.isEdit', true);
                this.transitionToRoute('crisis.edit');
            },
            toggleSeverityView: function() {
                var payload,
                        that = this,
                        shSev = this.get('showSeverityView');
                if (!shSev) {
                    if (this.get('controllers.application.user.isLoggedIn')) {

                        that.set('showSeverityView', !that.get('showSeverityView'));
                        payload = {
                            type: 'statisticsRevokeSeverity',
                            crisisID: this.get('controllers.crisis.id'),
                        };
                        if (that.get('controllers.crisis.castedSeverity')) {
                            Utils.ajax.makeAJAXPostCall('router.php', payload, function(response) {
                                if (!response.error) {
                                    var sum = that.get('controllers.crisis.sumSeverity'),
                                            num = that.get('controllers.crisis.numSeverity');
                                    sum = sum - response.vote;
                                    num = num - 1;
                                    that.set('controllers.crisis.sumSeverity', sum);
                                    that.set('controllers.crisis.numSeverity', num);
                                    that.set('controllers.crisis.castedSeverity', 0);
                                } else {
                                    console.log('some error occured in toggleSeverityView');
                                }
                            });
                        }
                        that.set('controllers.crisis.castedSeverityVote', 0);
                    }
                } else {
                    if (that.get('controllers.crisis.castedSeverityVote') && this.get('controllers.application.user.isLoggedIn')) {
                        payload = {
                            type: 'statisticsCastSeverity',
                            crisisID: this.get('controllers.crisis.id'),
                            severity: this.get('controllers.crisis.castedSeverityVote')
                        };
                        Utils.ajax.makeAJAXPostCall('router.php', payload, function(response) {
                            if (!response.error) {
                                var sum = that.get('controllers.crisis.sumSeverity'),
                                        num = that.get('controllers.crisis.numSeverity'),
                                        newLevel = that.get('controllers.crisis.castedSeverityVote');
                                sum = sum + newLevel;
                                if (newLevel) {
                                    num = num + 1;
                                }
                                that.set('controllers.crisis.sumSeverity', sum);
                                that.set('controllers.crisis.numSeverity', num);
                            } else {
                                console.log('some error occured in toggleSeverityView');
                            }
                        });
                    }
                    that.set('showSeverityView', !that.get('showSeverityView'));
                }


            }
        }
    });
    return CrisisIndexController;
});