Verily

Documentation conform [JSDoc 3 Standard](https://github.com/jsdoc3/jsdoc). Documentation wiki can be found at [JSDoc Wiki](https://code.google.com/p/jsdoc-toolkit/w/list).
Use require instead of requirejs to preserve uniformity (the two are interchangable, for more info see [documentation](https://github.com/jrburke/requirejs/commit/be45948433b053921dc6a6a57bf06d04e13b3b39)).

>Usage dunkey.py:
	1. Create the directories in templates, controllers and routes corresponding to the route of which the newly generated structure will be part of. See example at the end of the block.
	2. Specify the Core of the name of the structures respecting BigCamelCase.

	Assuming the following directory structure:
	/controllers/crises/
	/routes/crises/
	/templates/crises/
	```$ python dunkey.py
		 --- instructions will be printed here ---
		 crises CrisesCreate
	```

	This will create the following:
	/controllers/crises/CrisesCreateController.js
	/routes/crises/CrisesCreateRoute.js
	/templates/crises/crises.create.hbs

Please note:

In order that the naming conventions be preserved [Emberjs Routes](http://emberjs.com/guides/routing/defining-your-routes/), whenever you create the files for a resource which has no URL (it acts as the parent of a it's children and usually does not have any other role than being a wrapper). You should ommit the respective directory name (in e.g. crises), in order to generate the syntactically correct template name. Alternativelly you can set the directory structure, in which case you need to set the template's naming conventions right.
