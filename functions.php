<?php

if (!function_exists('reading_time')):
    /**
     * Display post reading time in minutes
     *
     * @param $post_content
     *
     * @return string
     */
    function reading_time($post_content)
    {
        $word_count  = str_word_count(strip_tags($post_content));
        $readingtime = ceil($word_count / 200);

        $est = sprintf( // WPCS: XSS OK.
            esc_html(_nx(
                '1 min',
                ' %1$s min',
                $readingtime,
                'min',
                'hello-elementor'
            )),
            number_format_i18n($readingtime)

        );
		$est = '<svg width="11" height="11" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M8.33366 5.36334H6.58366V3.61334C6.58366 3.29076 6.32233 3.03001 6.00033 3.03001C5.67833 3.03001 5.41699 3.29076 5.41699 3.61334V5.94668C5.41699 6.26926 5.67833 6.53001 6.00033 6.53001H8.33366C8.65624 6.53001 8.91699 6.26926 8.91699 5.94668C8.91699 5.62409 8.65624 5.36334 8.33366 5.36334ZM6.00033 10.6133C3.42724 10.6133 1.33366 8.51976 1.33366 5.94668C1.33366 3.37359 3.42724 1.28001 6.00033 1.28001C8.57341 1.28001 10.667 3.37359 10.667 5.94668C10.667 8.51976 8.57341 10.6133 6.00033 10.6133ZM6.00033 0.113342C2.78383 0.113342 0.166992 2.73018 0.166992 5.94668C0.166992 9.16318 2.78383 11.78 6.00033 11.78C9.21683 11.78 11.8337 9.16318 11.8337 5.94668C11.8337 2.73018 9.21683 0.113342 6.00033 0.113342Z" fill="#E29A36"/>
</svg> '.$est;
        return $est;
    }
endif;

/* Simply remove anything that looks like an archive title prefix */
add_action( 'elementor/utils/get_the_archive_title', function ($title) {
    return preg_replace('/^\w+: /', '', $title);
});

/* Change title of search result page */
add_filter('elementor/utils/get_the_archive_title','archive_callback'); 
function archive_callback($title) { 
	if ( is_search() ) { 
		return '' . get_search_query() ; 
	} return $title; 
}

/* Exemple of custom taxonomy query for Post widget */
add_action( 'elementor/query/tax_exemple_filter', function($query) {
	$slug = get_queried_object()->post_name;
	$tax_query = $query->get('tax_query');

	// If there is no meta query when this filter runs, it should be initialized as an empty array.
	if (!$tax_query){
		$tax_query = [];
	}
	
	$tax_query[] = [
		'relation'	=> 'OR',
		array(	
			'taxonomy' 		    => 'category',
			'field'    			=> 'slug',
			'terms'    			=> $slug.'-somenthing',
		),
		array(
			'taxonomy' 		    => 'category',
			'field'    			=> 'slug',
			'terms'    			=> $slug.'-exemple',
		)
	];
	$query->set( 'tax_query', $tax_query );
});

/* Exemple of custom meta query for Post widget */
add_action( 'elementor/query/meta_exemple_filter', function($query) {
	$page = get_post_field( 'post_name', $post_id );
	$meta_query = $query->get('meta_query');

	// If there is no meta query when this filter runs, it should be initialized as an empty array.
	if (!$meta_query){
		$meta_query = [];
	}

	$meta_query[] = [
		array(
			'key' 	  => 'custom-meta', 
			'value'   => $page, 
			'compare' => 'LIKE', 
		)
	];
	$query->set( 'meta_query', $meta_query );
});

?>