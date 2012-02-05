CREATE TABLE IF NOT EXISTS `#__iyosismaps_maps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `maptype` varchar(25) NOT NULL,
  `latitude` varchar(25) NOT NULL,
  `longitude` varchar(25) NOT NULL,
  `zoomlevel` tinyint(3) unsigned NOT NULL,
  `width` varchar(25) NOT NULL,
  `height` varchar(25) NOT NULL,
  `contentbefore` TEXT NOT NULL,
  `contentafter` TEXT NOT NULL,
  `pancontrol` tinyint(1) NOT NULL,
  `zoomcontrol` tinyint(1) NOT NULL,
  `maxzoom` tinyint(3) unsigned NOT NULL,
  `minzoom` tinyint(3) unsigned NOT NULL,
  `maptypecontrol` tinyint(1) NOT NULL,
  `scalecontrol` tinyint(1) NOT NULL,
  `streetviewcontrol` tinyint(1) NOT NULL,
  `overviewmapcontrol` tinyint(1) NOT NULL,
  `zoomcontrolstyle` varchar(25) NOT NULL,
  `maptypecontrolstyle` varchar(25) NOT NULL,
  `scrollwheel` tinyint(1) NOT NULL,
  `disabledoubleclickzoom` tinyint(1) NOT NULL,
  `draggable` tinyint(1) NOT NULL,
  `centermarker` tinyint(1) NOT NULL,
  `infowindow` tinyint(1) NOT NULL,
  `description` TEXT NOT NULL,
  `iconid` int(10) unsigned NOT NULL,
  `kml` tinyint(1) NOT NULL,
  `kmlurl` TEXT NOT NULL default '',
  `params` TEXT NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO `#__iyosismaps_maps` (`id`, `title`, `published`, `maptype`, `latitude`, `longitude`, `zoomlevel`, `width`, `height`, `contentbefore`, `contentafter`, `pancontrol`, `zoomcontrol`, `maxzoom`, `minzoom`, `maptypecontrol`, `scalecontrol`, `streetviewcontrol`, `overviewmapcontrol`, `zoomcontrolstyle`, `maptypecontrolstyle`, `scrollwheel`, `disabledoubleclickzoom`, `draggable`, `centermarker`, `infowindow`, `description`, `iconid`) VALUES
(1, 'Map', 1, 'ROADMAP', '20', '-40', 2, '600', '500', '', '', 0, 1, 0, 0, 1, 1, 1, 0, 'DEFAULT', 'DEFAULT', 1, 0, 1, 1, 1, '<p>Center</p>', '1');


CREATE TABLE IF NOT EXISTS `#__iyosismaps_markers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `mapid` int(10) unsigned NOT NULL,
  `latitude` varchar(25) NOT NULL,
  `longitude` varchar(25) NOT NULL,
  `iconid` int(10) unsigned NOT NULL,
  `infowindow` tinyint(1) NOT NULL,
  `description` TEXT NOT NULL,
  `catid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__iyosismaps_icons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `icon` TEXT NOT NULL,
  `iconsize` varchar(25) NOT NULL default '',
  `iconorigin` varchar(25) NOT NULL default '',
  `iconanchor` varchar(25) NOT NULL default '',
  `shadow` TEXT NOT NULL default '',
  `shadowsize` varchar(25) NOT NULL default '',
  `shadoworigin` varchar(25) NOT NULL default '',
  `shadowanchor` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=48;

INSERT INTO `#__iyosismaps_icons` (`id`, `title`, `published`, `icon`, `iconsize`, `iconorigin`, `iconanchor`, `shadow`, `shadowsize`, `shadoworigin`, `shadowanchor`)VALUES
(1, 'Icon', 1, 'http://www.google.com/mapfiles/marker.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(2, 'Icon Black', 1, 'http://www.google.com/mapfiles/marker_black.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(3, 'Icon Grey', 1, 'http://www.google.com/mapfiles/marker_grey.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(4, 'Icon Orange', 1, 'http://www.google.com/mapfiles/marker_orange.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(5, 'Icon White', 1, 'http://www.google.com/mapfiles/marker_white.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(6, 'Icon Yellow', 1, 'http://www.google.com/mapfiles/marker_yellow.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(7, 'Icon Purple', 1, 'http://www.google.com/mapfiles/marker_purple.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(8, 'Icon Green', 1, 'http://www.google.com/mapfiles/marker_green.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(9, 'Icon Start', 1, 'http://www.google.com/mapfiles/dd-start.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(10, 'Icon End', 1, 'http://www.google.com/mapfiles/dd-end.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(11, 'Icon Arrow', 1, 'http://maps.google.com/mapfiles/arrow.png', '20,34', '0,0', '10,34', 'http://maps.google.com/mapfiles/arrowshadow.png', '37,34', '0,0', '10,34'),
(12, 'Icon A', 1, 'http://www.google.com/mapfiles/markerA.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(13, 'Icon B', 1, 'http://www.google.com/mapfiles/markerB.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(14, 'Icon C', 1, 'http://www.google.com/mapfiles/markerC.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(15, 'Icon D', 1, 'http://www.google.com/mapfiles/markerD.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(16, 'Icon E', 1, 'http://www.google.com/mapfiles/markerE.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(17, 'Icon F', 1, 'http://www.google.com/mapfiles/markerF.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(18, 'Icon G', 1, 'http://www.google.com/mapfiles/markerG.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(19, 'Icon H', 1, 'http://www.google.com/mapfiles/markerH.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(20, 'Icon I', 1, 'http://www.google.com/mapfiles/markerI.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(21, 'Icon J', 1, 'http://www.google.com/mapfiles/markerJ.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(22, 'Icon K', 1, 'http://www.google.com/mapfiles/markerK.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(23, 'Icon L', 1, 'http://www.google.com/mapfiles/markerL.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(24, 'Icon M', 1, 'http://www.google.com/mapfiles/markerM.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(25, 'Icon N', 1, 'http://www.google.com/mapfiles/markerN.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(26, 'Icon O', 1, 'http://www.google.com/mapfiles/markerO.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(27, 'Icon P', 1, 'http://www.google.com/mapfiles/markerP.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(28, 'Icon Q', 1, 'http://www.google.com/mapfiles/markerQ.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(29, 'Icon R', 1, 'http://www.google.com/mapfiles/markerR.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(30, 'Icon S', 1, 'http://www.google.com/mapfiles/markerS.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(31, 'Icon T', 1, 'http://www.google.com/mapfiles/markerT.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(32, 'Icon U', 1, 'http://www.google.com/mapfiles/markerU.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(33, 'Icon V', 1, 'http://www.google.com/mapfiles/markerV.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(34, 'Icon W', 1, 'http://www.google.com/mapfiles/markerW.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(35, 'Icon X', 1, 'http://www.google.com/mapfiles/markerX.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(36, 'Icon Y', 1, 'http://www.google.com/mapfiles/markerY.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(37, 'Icon Z', 1, 'http://www.google.com/mapfiles/markerZ.png', '20,34', '0,0', '10,34', 'http://www.google.com/mapfiles/shadow50.png', '37,34', '0,0', '10,34'),
(38, 'Icon Mini Purple', 1, 'http://labs.google.com/ridefinder/images/mm_20_purple.png', '12,20', '0,0', '6,20', 'http://labs.google.com/ridefinder/images/mm_20_shadow.png', '22,20', '0,0', '6,20'),
(39, 'Icon Mini Yellow', 1, 'http://labs.google.com/ridefinder/images/mm_20_yellow.png', '12,20', '0,0', '6,20', 'http://labs.google.com/ridefinder/images/mm_20_shadow.png', '22,20', '0,0', '6,20'),
(40, 'Icon Mini Blue', 1, 'http://labs.google.com/ridefinder/images/mm_20_blue.png', '12,20', '0,0', '6,20', 'http://labs.google.com/ridefinder/images/mm_20_shadow.png', '22,20', '0,0', '6,20'),
(41, 'Icon Mini White', 1, 'http://labs.google.com/ridefinder/images/mm_20_white.png', '12,20', '0,0', '6,20', 'http://labs.google.com/ridefinder/images/mm_20_shadow.png', '22,20', '0,0', '6,20'),
(42, 'Icon Mini Green', 1, 'http://labs.google.com/ridefinder/images/mm_20_green.png', '12,20', '0,0', '6,20', 'http://labs.google.com/ridefinder/images/mm_20_shadow.png', '22,20', '0,0', '6,20'),
(43, 'Icon Mini Red', 1, 'http://labs.google.com/ridefinder/images/mm_20_red.png', '12,20', '0,0', '6,20', 'http://labs.google.com/ridefinder/images/mm_20_shadow.png', '22,20', '0,0', '6,20'),
(44, 'Icon Mini Black', 1, 'http://labs.google.com/ridefinder/images/mm_20_black.png', '12,20', '0,0', '6,20', 'http://labs.google.com/ridefinder/images/mm_20_shadow.png', '22,20', '0,0', '6,20'),
(45, 'Icon Mini Orange', 1, 'http://labs.google.com/ridefinder/images/mm_20_orange.png', '12,20', '0,0', '6,20', 'http://labs.google.com/ridefinder/images/mm_20_shadow.png', '22,20', '0,0', '6,20'),
(46, 'Icon Mini Gray', 1, 'http://labs.google.com/ridefinder/images/mm_20_gray.png', '12,20', '0,0', '6,20', 'http://labs.google.com/ridefinder/images/mm_20_shadow.png', '22,20', '0,0', '6,20'),
(47, 'Icon Mini Brown', 1, 'http://labs.google.com/ridefinder/images/mm_20_brown.png', '12,20', '0,0', '6,20', 'http://labs.google.com/ridefinder/images/mm_20_shadow.png', '', '', '');


CREATE TABLE IF NOT EXISTS `#__iyosismaps_polylines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `mapid` int(10) unsigned NOT NULL,
  `coordinates` TEXT NOT NULL,
  `strokecolor` varchar(25) NOT NULL,
  `strokeopacity` varchar(25) NOT NULL,
  `strokeweight` varchar(25) NOT NULL,
  `infowindow` tinyint(1) NOT NULL,
  `description` TEXT NOT NULL,
  `catid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__iyosismaps_polygons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `mapid` int(10) unsigned NOT NULL,
  `coordinates` TEXT NOT NULL,
  `strokecolor` varchar(25) NOT NULL,
  `strokeopacity` varchar(25) NOT NULL,
  `strokeweight` varchar(25) NOT NULL,
  `fillcolor` varchar(25) NOT NULL,
  `fillopacity` varchar(25) NOT NULL,
  `infowindow` tinyint(1) NOT NULL,
  `description` TEXT NOT NULL,
  `catid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- ALTER TABLE `#__iyosismaps_maps` ADD `kmlurl` TEXT NOT NULL default '' AFTER `iconid` ;  
-- ALTER TABLE `#__iyosismaps_maps` ADD `kml` int(3) NOT NULL AFTER `iconid` ; 
