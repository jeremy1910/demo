/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.height = '50rem';
    config.extraPlugins = 'codesnippet';
    config.skin = 'bootstrapck';
    config.extraPlugins = 'easyimage';
    config.extraPlugins = 'imagebase';
    config.extraPlugins = 'filebrowser';
    config.extraPlugins = 'popup';
    config.extraPlugins = 'filetools';
    //config.extraPlugins = 'uploadfile';
    //config.extraPlugins = 'uploadwidget';
    //config.extraPlugins = 'uploadimage';
    config.filebrowserUploadUrl = '/test';

    config.filebrowserUploadMethod = 'form';
    config.filebrowserBrowseUrl = '/test2'


};
