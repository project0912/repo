define(["ember","jquery","libs/nicEdit"],function(e,t){var n=e.TextArea.extend({id:null,editor:null,didInsertElement:function(){var e=this;e.id=this.get("elementId"),e.editor=(new nicEditor({buttonList:["bold","italic","underline","right","center","justify","link","ul","ol"]})).panelInstance(e.id),e.setEditorContent(),e.editor.addEvent("blur",t.proxy(e.onBlur,this)),t(".nicEdit-panelContain").parent().width("100%"),t(".nicEdit-panelContain").parent().next().width("100%")},onBlur:function(){var e=this,t=e.getViewContent();e.set("descr",e.getViewContent())},getViewContent:function(){var e=this,t=e.editor.instanceById(e.id);return t.getContent()},setEditorContent:function(){var e=this,t=e.editor.instanceById(e.id),n=e.get("descr");n&&t.setContent(n)},willClearRender:function(){var e=this;e.editor.removeEvent("blur",e.onBlur),e.editor.removeInstance(e.id)}});return n});