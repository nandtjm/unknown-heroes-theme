<?php
/**
 * The template for displaying header.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<ul class="elementor-icon-list-items elementor-inline-items">
	<li class="elementor-icon-list-item elementor-inline-item">
		<div class="search">
	        <?php get_search_form(); ?> 
	        <a href="javascript:void(0)">
	            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor"
	                 class="bi bi-search" viewBox="0 0 16 16">
	                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
	            </svg>
	        </a>
	    </div> 
	</li>
	<li class="elementor-icon-list-item elementor-inline-item">
		 <?php 

	    $account_url=(is_user_logged_in()) ? site_url().'/my-account/' : site_url().'/login/' ;
	    ?>
	    <a href="<?php echo $account_url; ?>">
	        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor"
	             class="bi bi-person" viewBox="0 0 16 16">
	            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
	        </svg>
	    </a>
	</li>
</ul>
	