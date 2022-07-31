<?php

$options = get_option('persian_elementor');
if ( $options['efa-all-font']) {
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
	$additional_fonts['Estedad'] = 'FARSI';
	$additional_fonts['EstedadFN'] = 'FARSI';
	$additional_fonts['Gandom'] = 'FARSI';
	$additional_fonts['IRANYekan'] = 'FARSI';
	$additional_fonts['IRANYekanFN'] = 'FARSI';
	$additional_fonts['Kara'] = 'FARSI';
	$additional_fonts['Mikhak'] = 'FARSI';
	$additional_fonts['Nahid'] = 'FARSI';
	$additional_fonts['Parastoo'] = 'FARSI';
	$additional_fonts['Sahel'] = 'FARSI';
	$additional_fonts['Samim'] = 'FARSI';
	$additional_fonts['Shabnam'] = 'FARSI';
	$additional_fonts['ShabnamFN'] = 'FARSI';
	$additional_fonts['Tanha'] = 'FARSI';
	$additional_fonts['TanhaFN'] = 'FARSI';
	$additional_fonts['Vazir'] = 'FARSI';
	$additional_fonts['VazirFN'] = 'FARSI';
	$additional_fonts['VazirMatn'] = 'FARSI';
	return $additional_fonts;
} );

}