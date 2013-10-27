define(['ember'], function(Ember) {
    'use strict';

    var EvidenceView = Ember.View.extend({
        layoutName: 'evidence/modal_layout',
        didInsertElement: function() {
            this.$('.modal, .modal-backdrop').addClass('in');
            //give the view focus
            this.$().attr({tabindex: 1});
            this.$().focus();
        },
        transition: function(container, loc, direction) {
            var unit; //+/-

            if (direction && loc !== 0) {
                unit = (direction === 'next') ? '-=' : '+=';
            }

            container.animate({
                'margin-left': unit ? (unit + loc) : loc
            }, 1000);
        },
        willDestroyElement: function() {
            this.$('.modal, .modal-backdrop').removeClass('in').hide();
        },
        keyDown: function(event) {
            if (event.keyCode === 27) {//escape character code
                this.get('controller').send('backToClaim');
            } else if ( (event.keyCode === 37) || (event.keyCode === 39)) {
                //Right and Left keys
                var direction = (event.keyCode === 37) ? 'left' : 'right';
                
                this.get('controller').send('showNextImage', direction);                
            }
        }
    });

    return EvidenceView;
});