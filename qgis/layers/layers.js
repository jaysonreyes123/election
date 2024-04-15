var wms_layers = [];


        var lyr_OpenStreetMap_0 = new ol.layer.Tile({
            'title': 'OpenStreetMap',
            'type': 'base',
            'opacity': 1.000000,
            
            
            source: new ol.source.XYZ({
    attributions: ' ',
                url: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png'
            })
        });
var format_CSJDM_1 = new ol.format.GeoJSON();
var features_CSJDM_1 = format_CSJDM_1.readFeatures(json_CSJDM_1, 
            {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
var jsonSource_CSJDM_1 = new ol.source.Vector({
    attributions: ' ',
});
jsonSource_CSJDM_1.addFeatures(features_CSJDM_1);
var lyr_CSJDM_1 = new ol.layer.Vector({
                declutter: true,
                source:jsonSource_CSJDM_1, 
                style: style_CSJDM_1,
                popuplayertitle: "CSJDM",
                interactive: true,
                title: '<img src="styles/legend/CSJDM_1.png" /> CSJDM'
            });
var format_CSJDM_Barangay_2 = new ol.format.GeoJSON();
var features_CSJDM_Barangay_2 = format_CSJDM_Barangay_2.readFeatures(json_CSJDM_Barangay_2, 
            {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
var jsonSource_CSJDM_Barangay_2 = new ol.source.Vector({
    attributions: ' ',
});
jsonSource_CSJDM_Barangay_2.addFeatures(features_CSJDM_Barangay_2);
var lyr_CSJDM_Barangay_2 = new ol.layer.Vector({
                declutter: true,
                source:jsonSource_CSJDM_Barangay_2, 
                style: style_CSJDM_Barangay_2,
                popuplayertitle: "CSJDM_Barangay",
                interactive: true,
                title: '<img src="styles/legend/CSJDM_Barangay_2.png" /> CSJDM_Barangay'
            });

lyr_OpenStreetMap_0.setVisible(true);lyr_CSJDM_1.setVisible(true);lyr_CSJDM_Barangay_2.setVisible(true);
var layersList = [lyr_OpenStreetMap_0,lyr_CSJDM_1,lyr_CSJDM_Barangay_2];
lyr_CSJDM_1.set('fieldAliases', {'gid': 'gid', 'shape_leng': 'shape_leng', 'shape_area': 'shape_area', 'adm3_en': 'adm3_en', 'adm3_pcode': 'adm3_pcode', 'adm3_ref': 'adm3_ref', 'adm3alt1en': 'adm3alt1en', 'adm3alt2en': 'adm3alt2en', 'adm2_en': 'adm2_en', 'adm2_pcode': 'adm2_pcode', 'adm1_en': 'adm1_en', 'adm1_pcode': 'adm1_pcode', 'adm0_en': 'adm0_en', 'adm0_pcode': 'adm0_pcode', 'date': 'date', 'validon': 'validon', 'validto': 'validto', });
lyr_CSJDM_Barangay_2.set('fieldAliases', {});
lyr_CSJDM_1.set('fieldImages', {'gid': 'TextEdit', 'shape_leng': '', 'shape_area': '', 'adm3_en': '', 'adm3_pcode': '', 'adm3_ref': '', 'adm3alt1en': '', 'adm3alt2en': '', 'adm2_en': '', 'adm2_pcode': '', 'adm1_en': '', 'adm1_pcode': '', 'adm0_en': '', 'adm0_pcode': '', 'date': '', 'validon': '', 'validto': '', });
lyr_CSJDM_Barangay_2.set('fieldImages', {});
lyr_CSJDM_1.set('fieldLabels', {'gid': 'hidden field', 'shape_leng': 'no label', 'shape_area': 'no label', 'adm3_en': 'no label', 'adm3_pcode': 'no label', 'adm3_ref': 'no label', 'adm3alt1en': 'no label', 'adm3alt2en': 'no label', 'adm2_en': 'no label', 'adm2_pcode': 'no label', 'adm1_en': 'no label', 'adm1_pcode': 'no label', 'adm0_en': 'no label', 'adm0_pcode': 'no label', 'date': 'no label', 'validon': 'no label', 'validto': 'no label', });
lyr_CSJDM_Barangay_2.set('fieldLabels', {});
lyr_CSJDM_Barangay_2.on('precompose', function(evt) {
    evt.context.globalCompositeOperation = 'normal';
});