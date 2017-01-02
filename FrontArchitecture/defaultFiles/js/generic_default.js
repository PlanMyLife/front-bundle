import $ from 'jquery';
import jQuery from 'jquery';
import 'pepjs';
import Mq from './libs/mediaQueries';
import Generic from './inc/generic';

$(window).on('load', function() {
    $('html').removeClass('no-js');

    window.$ = $;
    window.jQuery = jQuery;

    //Init Mq Events
    window.mq = new Mq( [
        {name: 'mobile', size: 380},
        {name: 'landscape', size: 560},
        {name: 'tablet', size: 740},
        {name: 'desktop', size: 980},
        {name: 'large', size: 1300},
        {name: 'wide', size: 1640}
    ] );

    window.generic = new Generic();

    if(typeof svg4everybody !== 'undefined') {
        svg4everybody();
    }
});