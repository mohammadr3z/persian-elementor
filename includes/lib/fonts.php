<?php

/**
 * Add Font Group
 */
add_filter( 'elementor/fonts/groups', function( $font_groups ) {
	$font_groups['FARSI'] = __( 'فونت فارسی' );
	return $font_groups;
} );
/**
 * Add Group Fonts
 */
add_filter( 'elementor/fonts/additional_fonts', function( $additional_fonts ) {
	// Key/value
	//Font name/font group
	$additional_fonts['IRANYekan'] = 'FARSI';
	// $additional_fonts['IRANSans'] = 'FARSI';
	// $additional_fonts['IRANSansFN'] = 'FARSI';
	// $additional_fonts['IRANSansDN'] = 'FARSI';
	// $additional_fonts['Daal'] = 'FARSI';
	// $additional_fonts['Aviny'] = 'FARSI';
	// $additional_fonts['Anjoman'] = 'FARSI';
	// $additional_fonts['Yekanbakh'] = 'FARSI';
	// $additional_fonts['Dana'] = 'FARSI';
	// $additional_fonts['Maneli'] = 'FARSI';
	// $additional_fonts['Irancell'] = 'FARSI';
	$additional_fonts['Vazir'] = 'FARSI';
	$additional_fonts['Shabnam'] = 'FARSI';
	return $additional_fonts;
} );