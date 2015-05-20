<?php 
if ( is_home() && get_query_var('paged') == 0 ) { 
	get_template_part( 'front' ); 
} else { 
	get_template_part( 'index' ); 
} 
?>