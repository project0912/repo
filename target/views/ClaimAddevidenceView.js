define(["ember"],function(e){var t=e.View.extend({layoutName:"claim/evidence_modal_layout",didInsertElement:function(){this.$(".modal, .modal-backdrop").addClass("in"),this.$().attr({tabindex:1}),this.$().focus()},willDestroyElement:function(){this.$(".modal, .modal-backdrop").removeClass("in")},keyDown:function(e){e.keyCode===27?this.get("controller").send("close"):e.keyCode===13&&this.get("controller").send("addEvidence")}});return t});