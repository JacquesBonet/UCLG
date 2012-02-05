/*

This file is part of Ext JS 4

Copyright (c) 2011 Sencha Inc

Contact:  http://www.sencha.com/contact

GNU General Public License Usage
This file may be used under the terms of the GNU General Public License version 3.0 as published by the Free Software Foundation and appearing in the file LICENSE included in the packaging of this file.  Please review the following information to ensure the GNU General Public License version 3.0 requirements will be met: http://www.gnu.org/copyleft/gpl.html.

If you are unsure which license is appropriate for your use, please contact the sales department at http://www.sencha.com/contact.

*/
var titleColumn;
var descriptionColumn;
var themeStore;
var regionStore;
var countryStore;
var organisationStore;
var filterTheme = Array();
var filterRegion = Array();
var filterOrganisation = Array();

Ext.Loader.setConfig({enabled: true});
Ext.Loader.setPath('Ext.ux', '/app/ux');
Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.ux.grid.FiltersFeature',
    'Ext.ux.grid.HeaderToolTip',
    'Ext.toolbar.Paging'
]);

//
// check the lang
//
if (Ext.util.Cookies.get( "uclg_lang") == "fr")
{
    titleColumn = 'title_fr';
    descriptionColumn = 'description_fr';
}
else
if (Ext.util.Cookies.get( "uclg_lang") == "es")
{
    titleColumn = 'title_es';
    descriptionColumn = 'description_es';
}
else
{
    titleColumn = 'title_en';
    descriptionColumn = 'description_en';
}

//
// define the model
//
Ext.define('modelLinkDatabase', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'countryId'
    }, {
        name: 'regionId'
    }, {
        name: 'organizationId'
    }, {
        name: 'themeId'
    }, {
        name: 'title_es'
    }, {
        name: 'title_en'
    }, {
        name: 'title_fr'
    }, {
        name: 'description_es'
    }, {
       name: 'description_en'
    }, {
       name: 'description_fr'
    }, {
        name: 'url'
    }]
});

//
// models for the filters
//
Ext.define('modelTheme', {
    extend: 'Ext.data.Model',
    fields: [{
          name: 'id',
          type: 'int'
        },{
        name: 'themeName'
    }]
});

Ext.define('modelRegion', {
    extend: 'Ext.data.Model',
    fields: [{
          name: 'id',
          type: 'int'
        },{
        name: 'nombre'
    }]
});

Ext.define('modelCountry', {
    extend: 'Ext.data.Model',
    fields: [{
          name: 'id',
          type: 'int'
        },{
        name: 'name'
    }]
});

Ext.define('modelOrganisation', {
    extend: 'Ext.data.Model',
    fields: [{
          name: 'id',
          type: 'int'
        },{
        name: 'organisationName'
    }]
});


Ext.onReady(function(){

    Ext.QuickTips.init();


    //
    // get the Link Database store
    //
    var linkdatabaseStore = new Ext.data.Store( {
        // store configs
        model: 'modelLinkDatabase',
        proxy: {
            type: 'ajax',
            url: '/app/linkdatabase/grid-filter.php',
            reader: {
                type: 'json',
                root: 'data',
                idProperty: 'id'
            }
        },
        remoteSort: true,
        sorters: [{
            property: titleColumn,
            direction: 'ASC'
        }],
        pageSize: 20
    });

    //
    // get the theme database store
    //
    themeStore = new Ext.data.Store( {
        // store configs
        model: 'modelTheme',
        proxy: {
            type: 'ajax',
            url: '/app/linkdatabase/theme.php',
            reader: {
                type: 'json',
                root: 'data',
                idProperty: 'id'
            }
        }
    });
    themeStore.load( {
        callback: function(records, operation, success) {
            for( var i = 0; i < records.length; i++)
                filterTheme.push( records[i].data.themeName);
        }
    });

    //
    // get the region database store
    //
    regionStore = new Ext.data.Store( {
        // store configs
        model: 'modelRegion',
        proxy: {
            type: 'ajax',
            url: '/app/linkdatabase/region.php',
            reader: {
                type: 'json',
                root: 'data',
                idProperty: 'id'
            }
        }
    });

    regionStore.load( {
        callback: function(records, operation, success) {
            for( var i = 0; i < records.length; i++)
               filterRegion.push( records[i].data.nombre);
        }
    });

    //
    // for the organisation database store
    //
    organisationStore = new Ext.data.Store( {
        model: 'modelOrganisation',
        proxy: {
            type: 'ajax',
            url: '/app/linkdatabase/organisation.php',
            reader: {
                type: 'json',
                root: 'data',
                idProperty: 'id'
            }
        }
    });

    organisationStore.load(  {
            callback: function(records, operation, success) {
                for( var i = 0; i < records.length; i++)
                   filterOrganisation.push( records[i].data.organisationName);
            }
    });

    labels = { name: 'Name', year: 'Year', country: 'Country', title: 'Title', organization: 'Organization'};

    // localisation
    if (Ext.util.Cookies.get( "uclg_lang") == "fr")
    {
        labels = { name: 'Nom', year: 'Année', country: 'Pay', title: 'Titre', organization: 'Organisation'};
    }
    else
    if (Ext.util.Cookies.get( "uclg_lang") == "es")
    {
        labels = { name: 'Nombre', year: 'Año', country: 'Pais', title: 'Título', organization: 'Organización'};
    }
    //
    // create the columns
    //
    var createLinkDatabaseColumns = function (finish, start) {

        var columns = [{
	            dataIndex: titleColumn,
	            text: labels.title,
	            sortable: true,
	            width:200,
                filter: { type: 'string'},
                renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    if (value == '')
                        value = record.data.title_en;
                    return '<div data-qtip="' + value + '"><a href=\"http://' + record.data.url + '\" target=_blank  style=\"padding-left:12px\">' + value + '</a></div>';
                }
	        },{
	            dataIndex: descriptionColumn,
	            text: 'Description',
	            sortable: true,
	            width:240,
                filter: { type: 'string'},
                renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                     // create tooltip
                    if (value == '')
                        value = record.data.description_en;
                    return '<div data-qtip="' + value + '">' + value + '</div>';
                }
	        },{
                dataIndex: 'themeId',
                text: 'Theme',
                sortable: true,
                width:220,
                filter: { type: 'list', options: filterTheme, phpMode: true},
                renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    for ( var i=0; i < themeStore.data.items.length; i++)
                    {
                        if (themeStore.data.items[i].data.id == value)
                            return themeStore.data.items[i].data.themeName;
                    }
                    return '';
                }
	        },{
                dataIndex: 'organizationId',
                text: labels.organization,
                sortable: true,
                width:180,
                filter: { type: 'list', options: filterOrganisation, phpMode: true},
                renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    for ( var i=0; i < organisationStore.data.items.length; i++)
                    {
                        if (organisationStore.data.items[i].data.id == value)
                            return '<div data-qtip="' + organisationStore.data.items[i].data.organisationName + '">' + organisationStore.data.items[i].data.organisationName + '</div>';
                    }
                    return '';
                }
            },{
                dataIndex: 'countryId',
                text: labels.country,
                sortable: true,
                width:90,
                filter: { type: 'string'}
            },{
                dataIndex: 'regionId',
                text: 'Region',
                sortable: true,
                width:85,
                filter: { type: 'list', options: filterRegion, phpMode: true},
                renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    for ( var i=0; i < regionStore.data.length; i++)
                    {
                        if (regionStore.data.items[i].data.id == value)
                            return regionStore.data.items[i].data.nombre;
                    }
                    return '';
                }
            }];

        return columns;
    };

    // create the filter
    var filters = {
            ftype: 'filters',
            // encode and local configuration options defined previously for easier reuse
            encode: false, // json encode the filter query
            local: false,   // defaults to false (remote filtering)
            filters: []
        };

    // create the grid
    linkdatabaseGrid = Ext.create('Ext.grid.Panel', {
        border: false,
        renderTo: 'idLinkDatabase',
        store: linkdatabaseStore,
        columns: createLinkDatabaseColumns(),
        loadMask: true,
        height:500,
        features: [filters],
        plugins: ['headertooltip'],
        dockedItems: [Ext.create('Ext.toolbar.Paging', {
            dock: 'bottom',
            store: linkdatabaseStore
        })]
    });

    // clear filter button on bottom toolbar
    linkdatabaseGrid.child('pagingtoolbar').add([
        '->',
        {
            text: 'Clear Filter Data',
            handler: function () {
                linkdatabaseGrid.filters.clearFilters();
            }
        }
    ]);

    // show the grid and load data
	linkdatabaseGrid.show();
    linkdatabaseStore.load();
});
