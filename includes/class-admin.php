<?php
// set priority to lower i.e. greater than 10
add_action( 'admin_menu', 'persian_elementor_admin_menu', 900 );

function persian_elementor_admin_menu() {
    add_submenu_page( 
        'elementor',
        'المنتور فارسی',
        'المنتور فارسی',
        'manage_options',
        'persian_elementor',
        'persian_elementor_page' );
}

function persian_elementor_page()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
<div class="wrap about-wrap">
    <h1>به المنتور فارسی خوش آمدید</h1>
    <div class="about-text">لذت طراحی با زبان فارسی و ظاهر زیبا
    </div>

    <a class="wp-badge" href="https://elementorfa.ir/" target="_blank"
         style="background-color:#ee305c !important;background-image:url(<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/about.png'; ?>) !important;background-position: center center;background-size: 167px auto !important;"></a>

    <h2 class="nav-tab-wrapper">
        <a class="nav-tab nav-tab-active" href="https://elementorfa.ir/" target="_blank">پشتیبانی المنتور فارسی</a>
        <a href="https://elementorfa.ir/shop/" class="nav-tab" target="_blank">فروشگاه</a>
        <a href="https://elementorfa.ir/faq" class="nav-tab" target="_blank">انجمن پرسش و پاسخ</a>
    </h2>


    <div>

        <div class="feature-section col three-col">
            <div>
                <h4>المنتور به زبان فارسی</h4>
                <p>بسته فارسی ساز المنتور، قابلیت فارسی سازی تمامی بخش های المنتور و المنتور پرو را دارد.</p>
            </div>

            <div>
                <h4>بهبود رابط کاربری</h4>
                <p>بسته فارسی ساز المنتور، رابط ویرایشگر را با فونت ایران یکان زیباتر می کند.</p>
            </div>

            <div>

                <h4>فونت های فارسی</h4>
                <p>با فعال سازی افزونه فارسی ساز المنتور، فونت ایران یکان و 9 فونت رایگان دیگر را به المنتور اضافه کنید.</p>
				<p>لیست فونت های نسخه رایگان:</p>
				<li>IRANYekan</li>
				<li>Vazir</li>
				<li>Shabnam</li>
				<li>Parastoo</li>
				<li>Tanha</li>
				<li>Samim</li>
				<li>Nahid</li>
				<li>Sahel</li>
            </div>
			
			<div>

                <h4>فونت های فارسی نسخه پرو</h4>
                <p>با تهیه نسخه پرو بسته <a href="https://elementorfa.ir/%d8%a7%d9%81%d8%b2%d9%88%d9%86%d9%87-%d9%81%d8%a7%d8%b1%d8%b3%db%8c-%d8%b3%d8%a7%d8%b2-%d8%a7%d9%84%d9%85%d9%86%d8%aa%d9%88%d8%b1/"> فارسی ساز المنتور </a>، 19 فونت فارسی را به این افزونه اضافه کنید.</p>
				<p>لیست فونت های نسخه پولی:</p>
				<li>Anjoman</li>
				<li>Aviny</li>
				<li>Daal</li>
				<li>Damavand</li>
				<li>Dana</li>
				<li>Gandom</li>
				<li>Irancell</li>
				<li>IRANSans</li>
				<li>IRANSans Dast Nevis</li>
				<li>IRANSans Farsi Number</li>
				<li>IRANYekan</li>
				<li>Kalameh</li>
				<li>Maneli</li>
				<li>Nahid</li>
				<li>Parastoo</li>
				<li>Sahel</li>
				<li>Samim</li>
				<li>Shabnam</li>
				<li>Tanha</li>
				<li>Vazir</li>
				<li>YekanBakh</li>
            </div>
			
			<div>

                <h4>آیکون های ایرانی</h4>
                <p>با نصب افزونه فارسی ساز، 48 فونت آیکون از برند های ایرانی به کتابخانه آیکون المنتور اضافه می شود. این آیکون ها برند های بانک ها، پیام رسان های داخلی و بیشتر می باشد.</p>
            </div>

        </div>
    </div>


</div>


    <?php
}