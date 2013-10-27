'''
Created on Jun 22, 2013

@author: atoth
'''
import sys, os

def save_to_file(dirname,filename,payload):
    try:
        assert not(os.path.isfile(dirname+'/'+filename))
        with open(dirname+'/'+filename, 'w') as myFile:
                myFile.write(payload)
    except AssertionError:
        print 'Warning: '+filename + ' already exists. You should remove it first'
    return

def read_input():
    #dir Controller=Route template template_content
#     lines = open('file.in','r').read().splitlines()
    lines = sys.stdin.read().splitlines()
    return lines


def extract_info(lines):
    for i in lines:
        aux = i.split()
        assert len(aux) == 2, "Please follow the syntax: directory BigCamelCaseName (of the structure)"

        dir_original = aux[0]#crises
        capName = aux[1]#Crises
        template = ''
        for i in capName:
                if i.isupper():
                    if len(template)>0:
                        template +='.'
                    template +=i.lower()
                else:
                    template +=i
                    
        #the templates names should look exactly like the route name which renders them(just . instead of /)

        templateContent = template + "Template content <br>\n{{outlet}}"

        
        dir_ = "controllers/"+dir_original            
        save_to_file(dir_,capName+"Controller.js",create_controller(capName))#controller
        dir_ = "views/"+dir_original
        # save_to_file(dir_,capName+"View.js",create_view(capName,template,dir_original))#view
        dir_ = "templates/"+dir_original
        # save_to_file(dir_,template+".hbs","".join(templateContent))#template
        dir_ = "routes/"+dir_original
        save_to_file(dir_,capName+"Route.js",create_route(capName))#route


def create_route(routeName):
    route = """define(['ember'],
        function(Ember) {
            "use strict";

            var """+routeName+"""Route = Ember.Route.extend({
                model: function() {
                },
                setupController: function() {
                }
            });

            return """+routeName+"""Route;
});"""
    return route
        
def create_view(viewName,templateName,dir_original):
    view = """define(['ember','text!templates/"""+dir_original+"""/"""+templateName+"""Template.html'],function(Ember,"""+templateName+"""Template){
    "use strict";
    var """+viewName+"""View= Ember.View.extend({
        defaultTemplate: Ember.Handlebars.compile("""+templateName+"""Template)
    });
    return """+viewName+"""View;
});"""
    return view

def create_controller(controllerName):
    controller = """define(['ember'],function(Ember){
    "use strict";
    var """+controllerName+"""Controller = Ember.ObjectController.extend();

    return """+controllerName+"""Controller;
});"""
    return controller


if __name__ == '__main__':
    print 'input: directory BigCamelCaseName (of the structure)'
    r = read_input()
    extract_info(r)
    pass