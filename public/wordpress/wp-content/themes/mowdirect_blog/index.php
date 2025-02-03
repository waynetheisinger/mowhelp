<?php

get_header();
?>

		<?php
		if ( have_posts() ) {

			// Load posts loop.
			while ( have_posts() ) {
				the_post();
			}


		} else {


		}
		?>

<?php
get_footer();
