<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Persian_Elementor_Templates_Source extends Elementor\TemplateLibrary\Source_Base {
	
	/**
	 * Template prefix
	 */
	protected $template_prefix = 'efa_';

	/**
	 * Return templates prefix
	 */
	public function get_prefix() {
		return $this->template_prefix;
	}

	public function get_id() {
		return 'persiantemplate';
	}

	public function get_title() {
		return __( 'Persian', 'persian-elementor-templates' );
	}

	public function register_data() {}

	public function get_items( $args = array() ) {

		$url            ='https://c110736.parspack.net/c110736/library/persian-elementor/info.json';
		$response       = wp_remote_get( $url, array( 'timeout' => 60 ) );
		$body           = wp_remote_retrieve_body( $response );
		$body           = json_decode( $body, true );
		$templates_data = ! empty( $body['data'] ) ? $body['data'] : false;
		$templates      = array();

		if ( ! empty( $templates_data ) ) {
			foreach ( $templates_data as $template_data ) {
				$templates[] = $this->get_item( $template_data );
			}
		}

		if ( ! empty( $args ) ) {
			$templates = wp_list_filter( $templates, $args );
		}

		return $templates;
	}

	public function get_item( $template_data ) {

		return [
			'template_id' => $this->get_prefix() . $template_data['template_id'],
			'source' => 'remote',
			'type' => $template_data['type'],
			'subtype' => $template_data['subtype'],
			'title' => $template_data['title'], // Prepend name for searchable string
			'thumbnail' => $template_data['thumbnail'],
			'date' => $template_data['tmpl_created'],
			'author' => $template_data['author'],
			'tags' => $template_data['tags'],
			'isPro' => 0,
			'templatePro' => false,
			'popularityIndex' => (int) $template_data['popularity_index'],
			'trendIndex' => (int) $template_data['trend_index'],
			'hasPageSettings' => ( '1' === $template_data['has_page_settings'] ),
			'url' => $template_data['url'],
			'accessLevel' => 0,
			'favorite' => ( 1 == $template_data['favorite'] ),
		];
	}

	public function save_item( $template_data ) {
		return false;
	}

	public function update_item( $new_data ) {
		return false;
	}

	public function delete_template( $template_id ) {
		return false;
	}

	public function export_template( $template_id ) {
		return false;
	}

	public function get_data( array $args, $context = 'display' ) {
		
		$filename   = str_replace( $this->template_prefix, '', $args['template_id'] );
		$url        = 'https://c110736.parspack.net/c110736/library/persian-elementor/templates/' . $filename . '.json';
		$response   = wp_remote_get( $url, array( 'timeout' => 60 ) );
		$body       = wp_remote_retrieve_body( $response );
		$body       = json_decode( $body, true );
		$data       = ! empty( $body['content'] ) ? $body['content'] : false;

		$result = array();

		$result['content']       = $this->replace_elements_ids( $data );
		$result['content']       = $this->process_export_import_content( $result['content'], 'on_import' );
		$result['page_settings'] = array();

		return $result;
	}

}
