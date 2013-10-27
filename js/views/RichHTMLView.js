define(['ember', 'jquery', 'libs/nicEdit'], function(Ember, $) {
    'use strict';
    /**
     * Need to be bound to 'descr' in the template! It sets the controller's descr.
     */
    var RichHTMLView = Ember.TextArea.extend({
        id: null,
        editor: null,
        didInsertElement: function() {
            var view = this;

            view.id = this.get("elementId");

			view.editor = new nicEditor({
					buttonList : ['bold','italic','underline','right','center','justify', 'link', 'ul', 'ol']
			}).panelInstance(view.id);
			
			view.setEditorContent();//set the initial content

			//When the editor looses focus the content of the editor is passed to descr
			view.editor.addEvent('blur',$.proxy(view.onBlur,this));

			//So the editor looks nice
			$('.nicEdit-panelContain').parent().width('100%');
			$('.nicEdit-panelContain').parent().next().width('100%');
    	},
        onBlur: function(){
                var view = this,
                    content = view.getViewContent();

                view.set('descr',view.getViewContent());
        },
    	getViewContent: function(){
    		var view = this,
    			inlineEditor = view.editor.instanceById(view.id);
    		return inlineEditor.getContent();
    	},
    	setEditorContent: function(){
    		var view = this,
    			inlineEditor = view.editor.instanceById(view.id),
                content = view.get('descr');
                
    		if (content)
        		inlineEditor.setContent(content);
    	},
    	willClearRender: function(){
    		var view = this;
            view.editor.removeEvent('blur',view.onBlur);
    		view.editor.removeInstance(view.id);
    	}

    });

    return RichHTMLView;
});
