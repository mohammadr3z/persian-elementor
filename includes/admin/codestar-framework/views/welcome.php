<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Setup Framework Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'EFS_Welcome' ) ) {
  class EFS_Welcome{

    private static $instance = null;

    public function __construct() {

      if ( EFS::$premium && ( ! EFS::is_active_plugin( 'codestar-framework/codestar-framework.php' ) || apply_filters( 'csf_welcome_page', true ) === false ) ) { return; }

      add_action( 'admin_menu', array( &$this, 'add_about_menu' ), 0 );
      add_filter( 'plugin_action_links', array( &$this, 'add_plugin_action_links' ), 10, 5 );
      add_filter( 'plugin_row_meta', array( &$this, 'add_plugin_row_meta' ), 10, 2 );

      $this->set_demo_mode();

    }

    // instance
    public static function instance() {
      if ( is_null( self::$instance ) ) {
        self::$instance = new self();
      }
      return self::$instance;
    }

    public function add_about_menu() {
      add_management_page( 'Codestar Framework', 'Codestar Framework', 'manage_options', 'csf-welcome', array( &$this, 'add_page_welcome' ) );
    }

    public function add_page_welcome() {

      $section = ( ! empty( $_GET['section'] ) ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : '';

      EFS::include_plugin_file( 'views/header.php' );

      // safely include pages
      switch ( $section ) {

        case 'quickstart':
          EFS::include_plugin_file( 'views/quickstart.php' );
        break;

        case 'documentation':
          EFS::include_plugin_file( 'views/documentation.php' );
        break;

        case 'relnotes':
          EFS::include_plugin_file( 'views/relnotes.php' );
        break;

        case 'support':
          EFS::include_plugin_file( 'views/support.php' );
        break;

        case 'free-vs-premium':
          EFS::include_plugin_file( 'views/free-vs-premium.php' );
        break;

        default:
          EFS::include_plugin_file( 'views/about.php' );
        break;

      }

      EFS::include_plugin_file( 'views/footer.php' );

    }

    public static function add_plugin_action_links( $links, $plugin_file ) {

      if ( $plugin_file === 'codestar-framework/codestar-framework.php' && ! empty( $links ) ) {
        $links['csf--welcome'] = '<a href="'. esc_url( admin_url( 'tools.php?page=csf-welcome' ) ) .'">Settings</a>';
        if ( ! EFS::$premium ) {
          $links['csf--upgrade'] = '<a href="http://codestarframework.com/">Upgrade</a>';
        }
      }

      return $links;

    }

    public static function add_plugin_row_meta( $links, $plugin_file ) {

      if ( $plugin_file === 'codestar-framework/codestar-framework.php' && ! empty( $links ) ) {
        $links['csf--docs'] = '<a href="http://codestarframework.com/documentation/" target="_blank">Documentation</a>';
      }

      return $links;

    }

    public function set_demo_mode() {

      $demo_mode = get_option( 'csf_demo_mode', false );

      $demo_activate = ( ! empty( $_GET[ 'csf-demo' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'csf-demo' ] ) ) : '';

      if ( ! empty( $demo_activate ) ) {

        $demo_mode = ( $demo_activate === 'activate' ) ? true : false;

        update_option( 'csf_demo_mode', $demo_mode );

      }

      if ( ! empty( $demo_mode ) ) {

        EFS::include_plugin_file( 'samples/admin-options.php' );

        if ( EFS::$premium ) {

          EFS::include_plugin_file( 'samples/customize-options.php' );
          EFS::include_plugin_file( 'samples/metabox-options.php'   );
          EFS::include_plugin_file( 'samples/nav-menu-options.php'  );
          EFS::include_plugin_file( 'samples/profile-options.php'   );
          EFS::include_plugin_file( 'samples/shortcode-options.php' );
          EFS::include_plugin_file( 'samples/taxonomy-options.php'  );
          EFS::include_plugin_file( 'samples/widget-options.php'    );
          EFS::include_plugin_file( 'samples/comment-options.php'   );

        }

      }

    }

  }

  EFS_Welcome::instance();
}
