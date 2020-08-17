<?php
// set priority to lower i.e. greater than 10
function persian_elementor_panel(){
	add_menu_page('المنتور فارسی', 'المنتور فارسی', 'manage_options', 'persian_elementor', 'persian_elementor_func', plugins_url( 'persian-elementor/includes/assets/images/icon.png' ),58.5);
    // add_submenu_page( 'persian_elementor', 'لایسنس', 'لایسنس', 'manage_options', 'persian_elementor_license', 'persian_elementor_func_license');
}
add_action( 'admin_menu', 'persian_elementor_panel', 900 );

function persian_elementor_func()
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
         style="background-color:#93033C !important;background-image:url(<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/about.png'; ?>) !important;background-position: center center;background-size: 167px auto !important;"></a>

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
                <p>با فعال سازی افزونه فارسی ساز المنتور، فونت ایران یکان و 11 فونت رایگان دیگر را به المنتور اضافه کنید.</p>
				<p>لیست فونت های نسخه رایگان:</p>
				<li>Estedad</li>
				<li>Gandom</li>
				<li>IRANYekan</li>
				<li>Mikhak</li>
				<li>Nahid</li>
				<li>Parastoo</li>
				<li>Sahel</li>
				<li>Samim</li>
				<li>Shabnam</li>
				<li>Tanha</li>
				<li>Vazir</li>
            </div>
			
			<div>

               <h4>فونت های فارسی نسخه پرو</h4>
                <p>با تهیه نسخه پرو بسته <a href="https://elementorfa.ir/%d8%a7%d9%81%d8%b2%d9%88%d9%86%d9%87-%d9%81%d8%a7%d8%b1%d8%b3%db%8c-%d8%b3%d8%a7%d8%b2-%d8%a7%d9%84%d9%85%d9%86%d8%aa%d9%88%d8%b1/"> فارسی ساز المنتور </a>، 26 فونت فارسی را به این افزونه اضافه کنید.</p>
				<p>لیست فونت های نسخه پولی:</p>
				<li>Anjoman</li>
				<li>Aviny</li>
				<li>Daal</li>
				<li>Damavand</li>
				<li>Dana</li>
				<li>Emkan</li>
				<li>Estedad</li>
				<li>Farhang</li>
				<li>Gandom</li>
				<li>Irancell</li>
				<li>IRANSans</li>
				<li>IRANSans Dast Nevis</li>
				<li>IRANSans Farsi Number</li>
				<li>IRANYekan</li>
				<li>Kalameh</li>
				<li>Katibeh</li>
				<li>Maneli</li>
				<li>Mikhak</li>
				<li>Noora</li>
				<li>Nahid</li>
				<li>Parastoo</li>
				<li>Sahel</li>
				<li>Samim</li>
				<li>Shabnam</li>
				<li>Tanha</li>
				<li>Vazir</li>

            </div>
			
			<div>

                <h4>آیکون های ایرانی</h4>
                <p>با نصب افزونه فارسی ساز، 48 فونت آیکون از برند های ایرانی به کتابخانه آیکون المنتور اضافه می شود. این آیکون ها برند های بانک ها، پیام رسان های داخلی و بیشتر می باشد.</p>
            </div>	
			
			<div>

					<h4>قالب های ایرانی در کتابخانه المنتور</h4>
               <p>بعد از نصب افزونه فارسی ساز المنتور، کتابخانه قالب های ایرانی، فارسی شده و راستچین را به کتابخانه قالب های المنتور اضافه کنید.</p>
            </div>

        </div>
    </div>


</div>


    <?php
}
function efa_persian_li() {
	?>
	<style>
	.wrap-license-efa .pluginname {
    background: #f9f9f9;
    padding: 14px;
    border-bottom: 1px solid #ccc;
    margin: -14px -14px 20px;
    width: 100%;
}
.wrap-license-efa{
	margin: 25px 0px 10px 10px;
    background: #fff;
    border: 1px solid #ccc;
    max-width: 535px;
    padding: 15px;
    min-height: 220px;
    position: relative;
    box-sizing: border-box;
}
label.description {
    font-size: 13px;
    top: 9px;
    position: relative;
}
	</style>
	<?php
}
add_action( 'admin_head', 'efa_persian_li' );
