define(['ember', 'jquery', 'tagit'], function(Ember, $) {
    'use strict';

    var TagsAutoCompleteFieldView = Ember.View.extend({
        classNames: ['percentWidth90'],
        tagName: 'ul',
        stringsHaveSameBeginning: function(string1, string2) {
            if (string1.toLowerCase().indexOf(string2.toLowerCase())) {
                return false;
            }

            return true;
        },
        filterList: function(dataList, term) {
            var that = this,
                    newList;

            newList = $.map(dataList, function(item) {
                if (that.stringsHaveSameBeginning(item.name, term)) {
                    return {
                        value: item.name
                    };
                }
            });

            return newList;
        },
        didInsertElement: function() {
            var that = this;
            this.$().tagit({
                placeholderText: that.get('placeHolder'),
                autocomplete: {
                    source: function(request, response) {

                        var list = that.get('tagsData'),
                                filteredList;

                        if (list.length) {
                            filteredList = that.filterList(list, request.term);
                            response(filteredList);
                        }
                    },
                    minLength: 1
                },
                afterTagAdded: function(event, ui) {
                    if (!ui.duringInitialization) {
                        // some validation

                            var list = that.get('tagsData').map(function(item) {
                                    return item.name;
                                }),
                                listIsEmpty = (list.length === 0),
                                termNotInList = ($.inArray(ui.tagLabel, list) === -1),
                                additionNotAllowed = listIsEmpty || termNotInList,
                                list;

                        if (additionNotAllowed) {
                            ui.tag.parent().tagit("removeTagByLabel", ui.tagLabel);
                        } else {
                            that.get('value').push(ui.tagLabel);
                        }
                    }
                },
                afterTagRemoved: function(event, ui) {
                    var i, len,
                            tags = that.get('value');

                    for (i = 0, len = tags.length; i < len; i++) {
                        if (tags[i] === ui.tagLabel) {
                            that.get('value').splice(i, 1);
                            break;
                        }
                    }
                }
            });
        }
    });

    return TagsAutoCompleteFieldView;
});