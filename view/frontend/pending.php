<?php
function evolution_html_pending(){
	echo '
		<div class="evolution_overlay">
			<div class="evolution_loader"></div>
		</div>
	';
}
add_action('wp_footer', 'evolution_html_pending');
function evolution_html_copy(){
	echo '
	<div class="evolution_tag_copy">
		<p>
		<i class="fa fa-check" aria-hidden="true" style="color: #FFF; margin-right: 5px;"></i>
		'.__('Copied successfully', 'evolution-api').'
		</p>
	</div>
	';
}
add_action('wp_footer', 'evolution_html_copy');