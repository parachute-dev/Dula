/*
    AJAX lazy-loader of posts

    This should be enqueued via functions.php as it takes advantage of WP localisation to hook into the API, like so:

    global $wp_query;
    wp_enqueue_script( 'parachute-child-rcs-scripts', get_stylesheet_directory_uri() . '/js/scripts.min.js' ); // 'parachute-rcs-XXXX' refers to the child theme
    wp_register_script( 'ajax_load_more_posts', get_stylesheet_directory_uri() . '/js/ajax.lazyload.js' );

    Allows us to pass parameters to the ajax.lazyload.js script
    
    Docs: https://codex.wordpress.org/Function_Reference/wp_localize_script

    wp_localize_script( 'ajax_load_more_posts', 'parachute_ajax_loadmore_params', array(
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
        'block_args' => json_encode( $wp_query->query_vars ),
        'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
        'max_page' => $wp_query->max_num_pages
    ) );

    wp_enqueue_script( 'ajax_load_more_posts' );

    function parachute_ajax_loadmore_posts_handler(){
        // Your code tries to nab some posts here
        die(); // Bail, like a lazy-loadin' snail!
    }

    add_action('wp_ajax_loadmoreposts', 'parachute_ajax_loadmore_posts_handler'); // wp_ajax_{action}
    add_action('wp_ajax_nopriv_loadmoreposts', 'parachute_ajax_loadmore_posts_handler'); // wp_ajax_nopriv_{action}

    --------------------------------------------

    Sloths lazy-load their posts and you can too!
                        ^
      `""==,,__  
        `"==..__"=..__ _    _..-==""_
             .-,`"=/ /\ \""/_)==""``
            ( (    | | | \/ |
             \ '.  |  \;  \ /
              |  \ |   |   ||
         ,-._.'  |_|   |   ||
        .\_/\     -'   ;   Y
       |  `  |        /    |-.
       '. __/_    _.-'     /'
              `'-.._____.-'
*/

Parachute.ns("Parachute.Utils.Ajax");
Parachute.ns("Parachute.Utils.Ajax.Helper");
Parachute.ns("Parachute.Utils.Ajax.Utils");
Parachute.ns("Parachute.Utils.Ajax.WP");

Parachute.Utils.Ajax = function() {};
Parachute.Utils.Ajax.Helper = function() {};
Parachute.Utils.Ajax.Utils = function() {};
Parachute.Utils.Ajax.WP = function() {};

/*
    * parachuteAjaxLoadMorePosts(id)
    *
    * <string> id: The key of a query object in the parachute_ajax_loadmore_queries array
    * <jQuery Object> btn: The load more button inside your container, with the ID formatted as #ajax-load-more<KEY>, where <KEY> is the
    * key of your query object in the parachute_ajax_loadmore_queries array
    * <jQuery Object> container: The container containing your .content-listings that you wish to add listing blocks with your new posts to
*/
Parachute.Utils.Ajax.WP.parachuteAjaxLoadMorePosts = function(id, btn, container){
    if( typeof id == 'undefined' ) { console.error('Invalid ID'); return false; }
    if( typeof window.parachute_ajax_loadmore_queries[id] == 'undefined' ) { console.error('ID provided is not key in query array'); return false; }
    if( typeof container == 'undefined' ) { console.error('Container element required for output.'); return false; }
    if( typeof btn == 'undefined' ) btn = jQuery('#ajax-load-more-' + id);
    if( btn.hasClass('loading') ) return true; // Bug out if we're already making a request for this query
    
    /* If we've got that query in our array then let's go! */
    var query_arr = window.parachute_ajax_loadmore_queries[id];

    var data = {
        'action': 'loadmoreposts',
        'current_page' : query_arr.current_page,
        'block_args': query_arr.block_args // wp_localize_script() warp trickery
    };
    
    // A little helper class to add to the button so we know if it's making a request already or not
    btn.addClass('loading');

    jQuery.ajax({
        url : parachute_ajax_loadmore_params.ajaxurl, // AJAX handler
        data : data,
        dataType : 'html',
        type : 'POST',
        beforeSend : function ( xhr ) {
            btn.text('Loading...');
        },
        success : function( data ){
            btn.removeClass('loading');
            if( data ) { 
                btn.text( 'Load more' );
                container.before(data);

                window.parachute_ajax_loadmore_queries[id].current_page++;

                if ( window.parachute_ajax_loadmore_queries[id].current_page == window.parachute_ajax_loadmore_queries[id].max_page ) 
                    btn.remove(); // Remove when there's nothing left
            } else {
                console.error(data);
                btn.remove();
            }
        }
    });
};

jQuery(document).ready( function(){
    /* 
     * By using global PHP vars like $wp_query this works but we're limited to one AJAX lazyloader per page.
     * Obviously, we'd rather be able to do this as many times as we want so we will have an array of these
     * queries and their associated parameters which are identifiable via a unqiue ID.
     * 
     * This unique ID will be used as the ID of the relevant "Load more" button for each listings block.
     * Ergo, we can reliably query the next list of posts for each query easily and reliably!
     * 
     * The data object required by the ajax call below should be in the format:
     * {
     *  'action': 'loadmoreposts', <--- defined in functions.php
     *  'query': $args you passed to WP_Query(),
     *  'paged': The integer of the current page you'd be on, initially found via get_query_var( 'pager )
     * }
     */

    /* Initialise our queries array */
    parachute_ajax_loadmore_queries = [];

    /* If our AJAX button comes into view for the user then we want to load more posts automatically */
    jQuery(window).scroll( function(){
        var scrollY = window.scrollY;
        jQuery('.ajax-load-more.button').each( function(){
            var btn = jQuery(this);
            var btnScrollY = btn.offset().top;
            var windowScrollY2 = scrollY + jQuery(window).height();
            var offsetY = 200; // A little offset to allow the user to at least see the button before trying to load more posts automatically

            // If we've not scrolled this button into view or we've scrolled past it then don't attempt to load more posts
            // if(scrollY <= btnScrollY) return true; // Would skip over this iteration if we hadn't scrolled by this button yet
            if(windowScrollY2 <= btnScrollY + offsetY) return true;

            // If we're going to try and load posts stop firing this method until it has finished loading the last request

            var idStr = btn.attr('id');
            var id = idStr.replace('ajax-load-more-', '');

            /* 
            * The container you want to append the returned posts to, by default we will append things just before the container of our button 
            * which should be ".content-listings-footer"
            * 
            */
            var container = jQuery(btn).parents().eq(0);

            /* If this query ID isn't in our window.parachute_ajax_loadmore_queries array then bug out */
            if( typeof window.parachute_ajax_loadmore_queries[id] == 'undefined' ) return false;
            Parachute.Utils.Ajax.WP.parachuteAjaxLoadMorePosts(id, btn, container);
        });
    });

    /* Default behaviour, load more posts on click */
    jQuery('.ajax-load-more').click( function(event){
        event.preventDefault();

        var btn = jQuery(this);
        var idStr = btn.attr('id');
        var id = idStr.replace('ajax-load-more-', '');

        /* 
         * The container you want to append the returned posts to, by default we will append things just before the container of our button 
         * which should be ".content-listings-footer"
         * 
        */
        var container = jQuery(btn).parents().eq(0);

        /* If this query ID isn't in our window.parachute_ajax_loadmore_queries array then bug out */
        if( typeof window.parachute_ajax_loadmore_queries[id] == 'undefined' ) return false;
        parachuteAjaxLoadMorePosts(id, btn, container);
    });
});

console.info("Parachute initialised: ajax.lazyload.js");