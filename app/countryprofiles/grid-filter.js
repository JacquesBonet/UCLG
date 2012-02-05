/*

This file is part of Ext JS 4

Copyright (c) 2011 Sencha Inc

Contact:  http://www.sencha.com/contact

GNU General Public License Usage
This file may be used under the terms of the GNU General Public License version 3.0 as published by the Free Software Foundation and appearing in the file LICENSE included in the packaging of this file.  Please review the following information to ensure the GNU General Public License version 3.0 requirements will be met: http://www.gnu.org/copyleft/gpl.html.

If you are unsure which license is appropriate for your use, please contact the sales department at http://www.sencha.com/contact.

*/

var countryGrid;
var regionStore;
var filterRegion = Array();


Ext.Loader.setConfig({enabled: true});
Ext.Loader.setPath('Ext.ux', '/app/ux');
Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.ux.grid.FiltersFeature',
    'Ext.ux.grid.HeaderToolTip',
    'Ext.toolbar.Paging'
]);

Ext.define('modelCountry', {
    extend: 'Ext.data.Model',
    fields: [{
            name: 'id',
            type: 'int'
    },{
        name: 'region'
    },{
        name: 'name'
    }, {
        name: 'download'
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


Ext.onReady(function(){

    Ext.QuickTips.init();

  
    ////////////////////////////////////////////////////////////////////
    // Country GRID
    ////////////////////////////////////////////////////////////////////

    var countryStore = new Ext.data.Store( {
        model: 'modelCountry',
        proxy: {
            type: 'ajax',
            url: '/app/countryprofiles/grid-filter.php',
            reader: {
                type: 'json',
                root: 'data',
                idProperty: 'id',
                totalProperty: 'total'
            }
        },
        remoteSort: true,
        sorters: [{
            property: 'name',
            direction: 'ASC'
        }],
        pageSize: 15
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

    var labels = { name: 'Name', region: 'Region', countryProfile: 'Country Profile', readOnline : 'Read Only', download : 'Download'};

    // localisation
    if (Ext.util.Cookies.get( "uclg_lang") == "fr")
    {
       labels = { name: 'Nom', region: 'Region', countryProfile: 'Fiche Pays', readOnline : 'Lecture seul', download : 'Téléchargement'};
    }
    else
    if (Ext.util.Cookies.get( "uclg_lang") == "es")
    {
        labels = { name: 'Nombre', region: 'Region', countryProfile: 'Fichas Países', readOnline : 'Leer en Línea', download : 'Descargar'};
    }

    var createCountryColumns = function (finish, start) {

        var columns = [{
	            dataIndex: 'name',
	            text: labels.name,
	            sortable: true,
	            width:202,
	            tooltip: 'Name of the Country',
				filterable: false,
                //filter: { type: 'string'}
	        },{
                dataIndex: 'region',
                text: labels.region,
                sortable: true,
                width:180,
                filter: { type: 'list', options: filterRegion, phpMode: true},
                renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                      for ( var i=0; i < regionStore.data.length; i++)
                      {
                          if (regionStore.data.items[i].data.id == value)
                              return regionStore.data.items[i].data.nombre;
                      }
                      return '';
                },
                tooltip: 'Region of the country'
	        },{
                text: labels.countryProfile,
                filterable: false,
                columns: [{
                    dataIndex: 'name',
                    text: labels.readOnline,
                    width:223,
                    sortable: false,
                    tooltip: 'Link to a country description',
                    renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                            return '<a href=\"http://issuu.com/uclggold/docs/' + value.toLowerCase() + '?mode=embed\" target=_blank  style=\"padding-left:12px\"><img src=\"/images/stories/red-online-icon.jpg\" alt=\"\" style=\"margin-right:12px\" />Read Online</a>';
                     }},
                    {
                    dataIndex: 'download',
                    text: labels.download,
                    width:223,
                    sortable: false,
                    tooltip: 'Download Link',
                    renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                            return '<a href=\"' + value + '\" target=_blank><img src=\"/images/stories/donload-icon.jpg\" alt=\"\" style=\"margin-right:12px\" />Download</a>';
                    }}
			    ]
	        }
        ];
        return columns;
    };
    
    var filters = {
            ftype: 'filters',
            // encode and local configuration options defined previously for easier reuse
            encode: false, // json encode the filter query
            local: false,   // defaults to false (remote filtering)
            filters: []
        };

    countryGrid = Ext.create('Ext.grid.Panel', {
        border: false,
        renderTo: 'idCountryprofiles',
        store: countryStore,
        columns: createCountryColumns(),
        loadMask: true,
        height:578,
        features: [filters],
        plugins: ['headertooltip'],
        dockedItems: [Ext.create('Ext.toolbar.Paging', {
            dock: 'bottom',
            store: countryStore
        })]
    });

    // clear filter button on bottom toolbar
    countryGrid.child('pagingtoolbar').add([
        '->',
		{
            text: 'Clear Filter Data',
            handler: function () {
                countryGrid.filters.clearFilters();
            }
        }
    ]);

	countryGrid.show();
    countryStore.load();
});


