<?php
defined('ABSPATH') or die();

class PersianElementorOptions extends PersianElementorCore
{
    private $plugin_name;
    
    function __construct($plugin_name)
    {
        add_action('init', array(
            $this,
            'efa_options'
        ));
        $this->plugin_name = $plugin_name;

    }
    public function efa_options() {
        
    /**
     * 
     * Create a submenu page under Plugins.
    * 
    * Framework also add "Settings" to your plugin in plugins list.
    * 
    * @link https://github.com/JoeSz/Exopite-Simple-Options-Framework
    */
    
        $config_submenu = array(
        	'type' => 'menu',
            'id' => $this->plugin_name,
            'menu_title' => __('المنتور فارسی', 'persian-elementor') ,
            'parent' => '',
            'submenu' => true,
            'title' => __('افزونه المنتور فارسی', 'persian-elementor') ,
            'capability' => 'manage_options',
            'multilang' => false,
            'icon' => plugins_url( 'persian-elementor/includes/assets/images/icon.png' )
        );
        
        /*
        *
        * To add a metabox.
        *
        * This normally go to your functions.php or another hook
        *
        */

        $fields[] = array(
            'name' => 'persian-elementor',
            'title' => __('المنتور فارسی', 'persian-elementor') ,
            'icon' => 'dashicons-admin-generic',
            'fields' => array(
                array(
                    'type' => 'notice',
                    'class' => 'info',
                    'content' => __('به صورت پیش فرض افزونه المنتور فارسی فونت پنل المنتور را به فونت <b>ایران یکان</b> تغییر می دهد. در صورت نیاز می توانید این گزینه را غیر فعال کنید.', 'persian-elementor') ,
                ) ,
	            array(
		            'id' => 'efa-panel-font',
		            'type' => 'switcher',
		            'title' => __('تغییر فونت پنل المنتور', 'persian-elementor') ,
		            'description' => __('با روشن کردن این گزینه، فونت پنل المنتور را به فونت ایران یکان تغییر دهید.', 'persian-elementor') ,
		            'default' => 'yes',
	            ) ,
                array(
                    'type' => 'notice',
                    'class' => 'info',
                    'content' => __('با فعال کردن این گزینه، تاریخ شمسی برای فیلد فرم ساز المنتور فعال خواهد شد.', 'persian-elementor') ,
                ) ,
	           	array(
		            'id' => 'efa-flatpickr',
		            'type' => 'switcher',
		            'title' => __('تاریخ شمسی فرم المنتور', 'persian-elementor') ,
		            'description' => __('فعال کردن تاریخ شمسی برای فیلد تاریخ فرم ساز المنتور', 'persian-elementor') ,
		            'default' => 'yes',
	            ) ,
            ),
        );
        
        $fields[] = array(
            'name' => 'efa-translate',
            'title' => __('ترجمه افزونه ها', 'persian-elementor') ,
            'icon' => 'dashicons-translation',
            'fields' => array(
	            array(
		            'type' => 'content',
		            'class' => 'class-name',
		            'content' => __('<p>به صورت پیش فرض ترجمه افزونه المنتور و المنتور پرو فعال می باشد. می توانید باقی افزونه ها را فعال یا غیر فعال کنید.</p>', 'persian-elementor') ,
	            ) ,
                array(
                    'id' => 'efa-elementor',
                    'type' => 'switcher',
                    'title' => __('فارسی ساز افزونه المنتور', 'persian-elementor') ,
                    'description' => __('فعال/غیر فعال کردن ترجمه فارسی افزونه المنتور', 'persian-elementor') ,
                    'default' => 'yes',
                ) ,
                array(
                    'id' => 'efa-elementor-pro',
                    'type' => 'switcher',
                    'title' => __('فارسی ساز افزونه المنتور پرو', 'persian-elementor') ,
                    'description' => __('فعال/غیر فعال کردن ترجمه فارسی افزونه المنتور پرو', 'persian-elementor') ,
                    'default' => 'yes',
                ) ,
                array(
                    'id' => 'efa-ele-custom-skin',
                    'type' => 'switcher',
                    'title' => __('Ele Custom Skin', 'persian-elementor') ,
                    'description' => __('فعال/غیر فعال کردن ترجمه فارسی افزونه Ele Custom Skin', 'persian-elementor') ,
                    'default' => 'no',
                ) ,
                array(
                    'id' => 'efa-essential-addons-for-elementor-lite',
                    'type' => 'switcher',
                    'title' => __('Essential Addons', 'persian-elementor') ,
                    'description' => __('فعال/غیر فعال کردن ترجمه فارسی افزونه Essential Addons', 'persian-elementor') ,
                    'default' => 'no',
                ) ,
                array(
                    'id' => 'efa-dynamicconditions',
                    'type' => 'switcher',
                    'title' => __('Dynamic Conditions', 'persian-elementor') ,
                    'description' => __('فعال/غیر فعال کردن ترجمه فارسی افزونه Dynamic Conditions', 'persian-elementor') ,
                    'default' => 'no',
                ) ,
                array(
                    'id' => 'efa-woolentor',
                    'type' => 'switcher',
                    'title' => __('Woolentor', 'persian-elementor') ,
                    'description' => __('فعال/غیر فعال کردن ترجمه فارسی افزونه Woolentor', 'persian-elementor') ,
                    'default' => 'no',
                ) ,
                array(
                    'id' => 'efa-metform',
                    'type' => 'switcher',
                    'title' => __('Metform', 'persian-elementor') ,
                    'description' => __('فعال/غیر فعال کردن ترجمه فارسی افزونه Metform', 'persian-elementor') ,
                    'default' => 'no',
                ) ,
            ) ,
        );
        
        $fields[] = array(
            'title' => __('فونت های فارسی', 'persian-elementor') ,
            'icon' => 'dashicons-editor-paste-text',
            'name' => 'persian-font',
            'fields' => array(
                array(
                    'type' => 'content',
                    'class' => 'class-name',
                    'content' => __('<p>با غیر فعال کردن این گزینه، تمامی فونت های فارسی از ویرایشگر المنتور حذف خواهد شد.</p>', 'persian-elementor') ,
                ) ,
                array(
                    'type' => 'notice',
                    'class' => 'info',
                    'content' => __('با استفاده از این گزینه، می توانید تمامی فونت ها را به صورت یک جا غیر فعال کنید.', 'persian-elementor') ,
                ) ,
                array(
		            'id' => 'efa-all-font',
		            'type' => 'switcher',
		            'title' => __('بارگذاری فونت ها', 'persian-elementor') ,
		            'description' => __('فعال/غیر فعال کردن فونت های فارسی', 'persian-elementor') ,
		            'default' => 'yes',
	            ) ,
            ) ,

        );
        
                $fields[] = array(
            'name' => 'templates-kits',
            'title' => __('قالب های آماده ایرانی', 'persian-elementor') ,
            'icon' => 'dashicons-tagcloud',
            'fields' => array(
                array(
                    'type' => 'content',
                    'class' => 'class-name',
                    'content' => __('<p>با غیر فعال کردن این گزینه، قالب های آماده ایرانی از کتابخانه المنتور حذف خواهد شد.</p>', 'persian-elementor') ,
                ) ,
	            array(
		            'id' => 'efa-templates-kits',
		            'type' => 'switcher',
		            'title' => __('قالب های آماده', 'persian-elementor') ,
		            'description' => __('فعال/غیر فعال کردن قالب های آماده ایرانی', 'persian-elementor') ,
		            'default' => 'yes',
	            ) ,
            ) ,
        );
        
        $fields[] = array(
            'title' => __('آیکون های ایرانی', 'persian-elementor') ,
            'icon' => 'dashicons-insert',
            'name' => 'iranian-icons',
            'fields' => array(
                array(
                    'type' => 'content',
                    'class' => 'class-name',
                    'content' => __('<p>با غیر فعال کردن این گزینه، آیکون های ایرانی از کتابخانه آیکون ها حذف خواهد شد.</p>', 'persian-elementor') ,
                ) ,
	            array(
		            'id' => 'efa-iranian-icon',
		            'type' => 'switcher',
		            'title' => __('مجموعه آیکون های ایرانی', 'persian-elementor') ,
		            'description' => __('فعال/غیر فعال کردن مجموعه آیکون های ایرانی', 'persian-elementor') ,
		            'default' => 'yes',
	            ) ,


            ) ,

        );
        
        $fields[] = array(
            'name' => 'about-persian-elementor',
            'title' => __('درباره المنتور فارسی', 'persian-elementor') ,
            'icon' => 'dashicons-admin-comments',
            'fields' => array(

                array(
                    'type' => 'content',
                    'class' => 'class-name',
                    'content' => __('
                  <h2>المنتور فارسی</h2>
                  <p>افزونه المنتور فارسی مجموعه ای از امکانات برای کاربران فارسی زبان طراحی و توسعه داده شده تا بتوانید در کنار طراحی لذت بخش توسط المنتور، این صفحه ساز را فارسی تجربه کنید.<br /><br />
                  </p>', 'persian-elementor') ,
                ) ,
	            array(
		            'type' => 'content',
		            'wrap_class' => 'no-border-bottom',
		            'title' => __('پشتیبانی', 'persian-elementor') ,
		            'content' => __('برای دریافت پشتیبانی می توانید به سایت <a href="https://elementorfa.ir/" target="_blank">elementorfa.ir</a> مراجعه کنید', 'persian-elementor') 
	            ) ,
            )
        );
        $options_panel = new Exopite_Simple_Options_Framework($config_submenu, $fields);
    }
}

new PersianElementorOptions('persian_elementor');