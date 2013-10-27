define(['ember', 'jquery'], function(Ember, $) {
    'use strict';
    
    var ImageUploaderView = Ember.View.extend({
        classNames: ['dropzone', 'height100'],
        didInsertElement: function() {
            var elemID = this.get('elementId'),
                    myDropZone = new Dropzone('#' + elemID, {
                url: "router.php",
                paramName: "uploadedFile",
                addRemoveLinks: true,
                acceptedFiles: "image/*"
            });
            
            this.setupEventHandlers(myDropZone);
        },
        setupEventHandlers: function(dropZoneTarget) {
            //Use $.proxy to pass on the appropriate this context
            dropZoneTarget.on("sending", $.proxy(this.beforeUploadToServer, this));
            dropZoneTarget.on("success", $.proxy(this.afterSuccessfulUploadToServer, this));
            dropZoneTarget.on("removedfile", $.proxy(this.removeFileOnServer, this));
        },
        beforeUploadToServer: function(file, xhr, formData) {
            var timestamp = new Date().getTime();
            formData.append("imageNum", timestamp); // Will send the filesize along with the file as POST data.
            formData.append("type", "imageUpload");
            file.imageNum = timestamp;
        },
        afterSuccessfulUploadToServer: function(file, response) {
            var responseJSON = JSON.parse(response);
            file.serverID = responseJSON.photoID; // Get the ID of the recently uploaded photo

            //check that the imageNum on file and response match
            // push to imageIDs array
            this.get('imageIDs').push(responseJSON.photoID);

        },
        removeFileOnServer: function(file) {
            if (!file.serverID) {
                return;
            } // The file hasn't been uploaded, pop the file ID

            //Do AJAX call for removal on server
            var i, len,
                    ids = this.get('imageIDs');
            
            for (i = 0, len = ids.length; i < len; i++) {
                if (ids[i] === file.serverID) {
                    this.get('imageIDs').splice(i, 1);
                    break;
                }
            }
        }
    });
    
    return ImageUploaderView;
});