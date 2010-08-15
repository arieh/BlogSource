<?php
/**
 * this variable holds the paths used by the site.
 * each array is a list of paths for a specific enviorment of the site. The first should be the offline version,
 * follwed by a list of online versions.
 * 
 * each list should hold these 3 locations:
 *  1. the site's full base url
 *  2. If the site doesn't sit on the base dir of the site, the path to substract from the request URI
 *  3. full path to the site's CDN
 */
$paths = array(
    0=>array( //offline
        'http://arieh-laptop/blog/public/'
        ,'blog/public/'
        ,'http://arieh-laptop/blog/public/min/'
    )
    ,1=> array( //online
        'http://blog.arieh.co.il/'
        ,''
        ,'http://blog-css.arieh.co.il/'
    )
);

/**
 * which path to use 
 */
$paths = $paths[0];