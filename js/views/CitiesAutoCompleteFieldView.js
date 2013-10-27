define(['ember', 'utils/utils', 'tagit'], function(Ember, Utils) {
    'use strict';

    var CitiesAutoCompleteFieldView = Ember.View.extend({
        classNames: ['percentWidth90'],
        tagName: 'ul',
        stringsHaveSameBeginning: function(string1, string2) {
            if (string1.toLowerCase().indexOf(string2.toLowerCase())) {
                return false;
            }

            return true;
        },
        filterData: function(dataList, term) {
            var that = this,
                    newList;

            newList = $.map(dataList, function(item) {
                if (that.stringsHaveSameBeginning(item.city, term)) {

                    return {
                        value: item.city,
                        label: item.city + ' - ' + item.country
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
                        var list = that.get('geoData'),
                                filteredList;

                        if (list.length) {
                            filteredList = that.filterData(list, request.term);
                            response(filteredList);
                        }
                    },
                    minLength: 1
                },
                afterTagAdded: function(event, ui) {
                    if (!ui.duringInitialization) {
                        // some validation
                        var list = that.get('geoData'),
                                cities = list.map(function(item) {
                            return item.city;
                        }),
                                listIsEmpty = (cities.length === 0),
                                valueSetAlready = (!!that.get('value') && that.get('value') !== ui.tagLabel) ? true : false,
                                termNotInList = ($.inArray(ui.tagLabel, cities) === -1),
                                additionAllowed = !(listIsEmpty || termNotInList || valueSetAlready),
                                i, len;

                        if (additionAllowed) {
                            for (i = 0, len = that.get('geoData').length; i < len; i++) {
                                if (list[i].city === ui.tagLabel) {
                                    that.get('controller').send('updateLocationInfo', list[i]);
                                    break;
                                }
                            }
                            that.set('value', ui.tagLabel);
                            //TODO Dirty hack for resetting placeholder text
                            that.$('.tagit-new').find('input').attr('placeholder', '');
                        } else {
                            ui.tag.parent().tagit("removeTagByLabel", ui.tagLabel);
                        }
                    }
                },
                afterTagRemoved: function(event, ui) {
                    if (ui.tagLabel === that.get('value')) {
                        that.set('value', '');
                    }
                    //TODO Dirty hack for resetting placeholder text
                    that.$('.tagit-new').find('input').attr('placeholder', 'City');
                }
            });

            if (this.get('value')) {
                this.$().tagit('createTag', this.get('value'));
            }
        }
    });

    return CitiesAutoCompleteFieldView;
});