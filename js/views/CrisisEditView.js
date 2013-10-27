define(['ember'], function(Ember) {
    'use strict';

    var CrisisEditView = Ember.View.extend({
        layoutName: 'crises/modal_layout',
        didInsertElement: function() {
            this.$('.modal, .modal-backdrop').addClass('in');
            //give the view focus
            this.$().attr({ tabindex: 1 });
            this.$().focus();
        },
        willDestroyElement: function() {
            this.$('.modal, .modal-backdrop').removeClass('in').hide();
        },
        keyDown: function(event) {
            if (event.keyCode === 27){//escape character code
                this.get('controller').send('close');
            }
        }
    });

    return CrisisEditView;
});