<?php
/**
 * @package EFA
 */
defined('ABSPATH') or die();
/**
 * Settings class
 *
 * settings page --> Persian Elementor
 *
 * @since 1.2.0
 */

require_once plugin_dir_path(__FILE__) . '/codestar-framework/codestar-framework.php';

// Control core classes for avoid errors
if( class_exists( 'EFS' ) ) {

    //
    // Set a unique slug-like ID
    $prefix = 'persian_elementor';
  
    //
    // Create options
    EFS::createOptions( $prefix, array(
  
      // framework title
      'framework_title'         => 'المنتور فارسی',
      'framework_class'         => '',
  
      // menu settings
      'menu_title'              => 'المنتور فارسی',
      'menu_slug'               => 'persian-elementor',
      'menu_type'               => 'submenu',
      'menu_capability'         => 'manage_options',
      'menu_icon'               => '',
      'menu_position'           => null,
      'menu_hidden'             => false,
      'menu_parent'             => '',
  
      // menu extras
      'show_bar_menu'           => false,
      'show_sub_menu'           => false,
      'show_in_network'         => true,
      'show_in_customizer'      => false,
  
      'show_search'             => false,
      'show_reset_all'          => false,
      'show_reset_section'      => true,
      'show_footer'             => true,
      'show_all_options'        => true,
      'show_form_warning'       => true,
      'sticky_header'           => true,
      'save_defaults'           => true,
      'ajax_save'               => true,
  
      // admin bar menu settings
      'admin_bar_menu_icon'     => '',
      'admin_bar_menu_priority' => 80,
  
      // footer
      'footer_text'             => 'پشتیبانی المنتور فارسی',
      'footer_after'            => '',
      'footer_credit'           => '',
  
      // database model
      'database'                => '', // options, transient, theme_mod, network
      'transient_time'          => 0,
  
      // contextual help
      'contextual_help'         => array(),
      'contextual_help_sidebar' => '',
  
      // typography options
      'enqueue_webfont'         => true,
      'async_webfont'           => false,
  
      // others
      'output_css'              => true,
  
      // theme and wrapper classname
      'nav'                     => 'normal',
      'theme'                   => 'dark',
      'class'                   => '',
  
      // external default values
      'defaults'                => array(),
  
    ) );
  
    //
    // Create a section
    EFS::createSection( $prefix, array(
      'title'  => 'المنتور فارسی',
      'icon'   => 'far fa-smile-wink',
      'fields' => array(
  
        array(
            'type'    => 'content',
            'content' => '<p style="color:#93033c;font-size:14px"> نسخه افزونه: '.  PERSIAN_ELEMENTOR_VERSION ,
          ),

          array(
            'type'    => 'submessage',
            'style'   => 'info',
            'content' => 'به صورت پیش فرض افزونه المنتور فارسی فونت پنل المنتور را به فونت <b>ایران یکان</b> تغییر می دهد. در صورت نیاز می توانید این گزینه را غیر فعال کنید.',
          ),
          array(
            'id'    => 'efa-panel-font',
            'type'  => 'switcher',
            'title' => 'تغییر فونت پنل المنتور',
            'text_on'    => 'فعال',
            'text_off'   => 'غیرفعال',
            'subtitle'   => 'با روشن کردن این گزینه، فونت پنل المنتور را به فونت ایران یکان تغییر دهید.',
            'default'    => true ,
            'text_width' => 70
          ),
          array(
            'type'    => 'submessage',
            'style'   => 'info',
            'content' => 'با فعال کردن این گزینه، تاریخ شمسی برای فیلد فرم ساز المنتور فعال خواهد شد.',
          ),
          array(
            'id'    => 'efa-flatpickr',
            'type'  => 'switcher',
            'title' => 'تاریخ شمسی فرم المنتور',
            'text_on'    => 'فعال',
            'text_off'   => 'غیرفعال',
            'subtitle'   => 'فعال کردن تاریخ شمسی برای فیلد تاریخ فرم ساز المنتور',
            'default'    => true ,
            'text_width' => 70
          ),
      )
    ) );
  
    //
    // Create a section
    EFS::createSection( $prefix, array(
      'title'  => 'ترجمه افزونه ها',
      'icon'   => 'fa fa-language',
      'fields' => array(

        array(
          'type'    => 'content',
          'content' => 'به صورت پیش فرض ترجمه افزونه المنتور و المنتور پرو فعال می باشد. می توانید باقی افزونه ها را فعال یا غیر فعال کنید.' ,
        ),
   array(
          'id'    => 'efa-elementor',
          'type'  => 'switcher',
          'title' => 'فارسی ساز افزونه المنتور',
          'text_on'    => 'فعال',
          'text_off'   => 'غیرفعال',
          'subtitle'   => 'فعال/غیر فعال کردن ترجمه فارسی افزونه المنتور',
          'default'    => true ,
          'text_width' => 70
        ),
   array(
          'id'    => 'efa-elementor-pro',
          'type'  => 'switcher',
          'title' => 'فارسی ساز افزونه المنتور پرو',
          'text_on'    => 'فعال',
          'text_off'   => 'غیرفعال',
          'subtitle'   => 'فعال/غیر فعال کردن ترجمه فارسی افزونه المنتور پرو',
          'default'    => true ,
          'text_width' => 70
        ),  
   array(
          'id'    => 'efa-ele-custom-skin',
          'type'  => 'switcher',
          'title' => 'Ele Custom Skin',
          'text_on'    => 'فعال',
          'text_off'   => 'غیرفعال',
          'subtitle'   => 'فعال/غیر فعال کردن ترجمه فارسی افزونه Ele Custom Skin',
          'default'    => false ,
          'text_width' => 70
        ),		
	array(
          'id'    => 'efa-essential-addons-for-elementor-lite',
          'type'  => 'switcher',
          'title' => 'Essential Addons',
          'text_on'    => 'فعال',
          'text_off'   => 'غیرفعال',
          'subtitle'   => 'فعال/غیر فعال کردن ترجمه فارسی افزونه Essential Addons',
          'default'    => false ,
          'text_width' => 70
        ),
	array(
          'id'    => 'efa-dynamicconditions',
          'type'  => 'switcher',
          'title' => 'Dynamic Conditions',
          'text_on'    => 'فعال',
          'text_off'   => 'غیرفعال',
          'subtitle'   => 'فعال/غیر فعال کردن ترجمه فارسی افزونه Dynamic Conditions',
          'default'    => false ,
          'text_width' => 70
        ),
	array(
          'id'    => 'efa-woolentor',
          'type'  => 'switcher',
          'title' => 'Woolentor',
          'text_on'    => 'فعال',
          'text_off'   => 'غیرفعال',
          'subtitle'   => 'فعال/غیر فعال کردن ترجمه فارسی افزونه Woolentor',
          'default'    => false ,
          'text_width' => 70
        ),	
	array(
          'id'    => 'efa-metform',
          'type'  => 'switcher',
          'title' => 'Metform',
          'text_on'    => 'فعال',
          'text_off'   => 'غیرفعال',
          'subtitle'   => 'فعال/غیر فعال کردن ترجمه فارسی افزونه Metform',
          'default'    => false ,
          'text_width' => 70
        ),			
      )
    ) );

    EFS::createSection( $prefix, array(
      'title'  => 'فونت های فارسی',
      'icon'   => 'fas fa-font',
      'fields' => array(
  
        array(
          'type'    => 'content',
          'content' => '<p>با غیر فعال کردن این گزینه، تمامی فونت های فارسی از ویرایشگر المنتور حذف خواهد شد.</p>' ,
        ),
          array(
            'type'    => 'submessage',
            'style'   => 'info',
            'content' => 'با استفاده از این گزینه، می توانید تمامی فونت ها را به صورت یک جا غیر فعال کنید.',
          ),
        array(
          'id'    => 'efa-all-font',
          'type'  => 'switcher',
          'title' => 'بارگذاری فونت ها',
          'text_on'    => 'فعال',
          'text_off'   => 'غیرفعال',
          'subtitle'   => 'فعال/غیر فعال کردن فونت های فارسی',
          'default'    => true ,
          'text_width' => 70
        ), 
      )
    ) );

    EFS::createSection( $prefix, array(
      'title'  => 'قالب های آماده ایرانی',
      'icon'   => 'far fa-object-group',
      'fields' => array(
        array(
          'type'    => 'content',
          'content' => '<p>با غیر فعال کردن این گزینه، قالب های آماده ایرانی از کتابخانه المنتور حذف خواهد شد.</p>' ,
        ),  
        array(
          'id'    => 'efa-templates-kits',
          'type'  => 'switcher',
          'title' => 'قالب های آماده',
          'text_on'    => 'فعال',
          'text_off'   => 'غیرفعال',
          'subtitle'   => 'فعال/غیر فعال کردن قالب های آماده ایرانی',
          'default'    => true ,
          'text_width' => 70
        ),
  
      )
    ) );

    EFS::createSection( $prefix, array(
      'title'  => 'آیکون های ایرانی',
      'icon'   => 'far fa-star',
      'fields' => array(
  
        array(
          'type'    => 'content',
          'content' => '<p>با غیر فعال کردن این گزینه، آیکون های ایرانی از کتابخانه آیکون ها حذف خواهد شد.</p>' ,
        ),  
        array(
            'id'    => 'efa-iranian-icon',
            'type'  => 'switcher',
            'title' => 'مجموعه آیکون های ایرانی',
            'text_on'    => 'فعال',
            'text_off'   => 'غیرفعال',
            'subtitle'   => 'فعال/غیر فعال کردن مجموعه آیکون های ایرانی',
            'default'    => true ,
            'text_width' => 70
        ),
      )
    ) );

    EFS::createSection( $prefix, array(
      'title'  => 'درباره المنتور فارسی',
      'icon'   => 'fas fa-award',
      'fields' => array(
  
        array(
          'type'    => 'content',
          'content' => '
                  <h2>المنتور فارسی</h2>
                  <p>افزونه المنتور فارسی مجموعه ای از امکانات برای کاربران فارسی زبان طراحی و توسعه داده شده تا بتوانید در کنار طراحی لذت بخش توسط المنتور، این صفحه ساز را فارسی تجربه کنید.<br /><br />
                  </p>',
           ),
           array(
            'type'    => 'content',
            'content' => 'برای دریافت پشتیبانی می توانید به سایت <a href="#" target="_blank">المنتور فارسی</a> مراجعه کنید' ,
          ), 
  
      )
    ) );
  
  }
  