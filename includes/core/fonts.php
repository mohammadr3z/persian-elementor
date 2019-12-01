<?php
/**
 * Elementor Custom Fonts
 */
function modify_controls( $controls_registry ) {
	// First we get the fonts setting of the font control
	$fonts = $controls_registry->get_control( 'font' )->get_settings( 'options' );
	// Then we append the custom font family in the list of the fonts we retrieved in the previous step
	$new_fonts = array_merge( 
		[ 'IRANYekan' => 'custom' ], 
		// [ 'IRANSans' => 'custom' ], 
		// [ 'IRANSansDN' => 'custom' ], 
		// [ 'IRANSansFN' => 'custom' ], 
		// [ 'Daal' => 'custom' ], 
		// [ 'Aviny' => 'custom' ], 
		// [ 'Anjoman' => 'custom' ], 		
		// [ 'Yekanbakh' => 'custom' ], 
		// [ 'Dana' => 'custom' ], 
		// [ 'Maneli' => 'custom' ],
		// [ 'Irancell' => 'custom' ], 
		[ 'Vazir' => 'custom' ], 
		[ 'Shabnam' => 'custom' ], 

		$fonts );
	// Then we set a new list of fonts as the fonts setting of the font control
	$controls_registry->get_control( 'font' )->set_settings( 'options', $new_fonts );
}
add_action( 'elementor/controls/controls_registered', 'modify_controls', 10, 1 );