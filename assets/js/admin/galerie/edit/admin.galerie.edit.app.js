/*const $ = require('jquery');
global.$ = global.jQuery = $;
require('bootstrap');*/
var jQueryBridget = require('jquery-bridget');
var Masonry = require('masonry-layout');
jQueryBridget( 'masonry', Masonry, $ );
var InfiniteScroll = require('infinite-scroll');
jQueryBridget( 'infiniteScroll', InfiniteScroll, $ );
var imagesLoaded = require('imagesloaded');
InfiniteScroll.imagesLoaded = imagesLoaded;
require("@fancyapps/fancybox");
global.bootbox = require("bootbox");
require("./admin.galerie.edit.js");