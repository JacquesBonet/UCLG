/*
Copyright (c) 2003 - 2011, CKSource - Frederico Knabben. All rights reserved.
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.protectedSource.push(/<\?[\s\S]*?\?>/g);  // to keep PHP code
	config.protectedSource.push(/<script[\s\S]*?\/script>/g);  // to keep script code
	config.protectedSource.push(/<link [\s\S]*?text\/css\" \/>/g);  // to keep link code
	
	config.removeFormatTags = 'b,big,code,del,dfn,em,font,i,ins,kbd,q,samp,small,span,strike,strong,sub,sup,tt,u,var,div,script,link';
	
	config.extraPlugins = 'syntaxhighlight';
	
};
