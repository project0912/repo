define(['ember', 'utils/utils'], function(Ember, Utils) {
    'use strict';

    var CoverImageView = Ember.View.extend({
        classNames: ['span12', 'spacingBottom'],
        tagName: 'img',
        didInsertElement: function() {
            var imgSrc = this.createImagePath();
            this.$().attr('src', imgSrc);
        },
        createImagePath: function() {
            var crisisID = this.get('crisisID'),
                    claimID = this.get('claimID'),
                    evidenceID = this.get('evidID'),
                    photoID = this.get('photoID'),
                    imageTypeIdentifier = Utils.CONSTANTS.get('coverImageIdentifier'),
                    imagePath;

            imagePath = Utils.CONSTANTS.get('baseImageDir') +
                    '/' + crisisID +
                    '/' + claimID +
                    '/' + evidenceID +
                    '/' + imageTypeIdentifier +
                    photoID + "." + Utils.CONSTANTS.get('jpgFileType');

            return imagePath;
        }
    });

    return CoverImageView;
});