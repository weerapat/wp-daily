<div id="td-header-menu" role="navigation" itemscope="itemscope" itemtype="<?php echo td_global::$http_or_https?>://schema.org/SiteNavigationElement">
    <div id="td-top-mobile-toggle"><a href="#"><i class="td-icon-font td-icon-mobile"></i></a></div>
    <div class="td-main-menu-logo">
        <?php
        if (td_util::get_option('tds_logo_menu_upload') == '') {
            locate_template('parts/header/logo.php', true, false);
            echo do_shortcode('[responsive-menu menu="top-menu"]');
        } else {
            locate_template('parts/header/logo-mobile.php', true, false);
        }?>
    </div>
    <?php
// ZA Custom
    if( is_user_logged_in() ) {
       $menu = 'logged-in';
   } else {
       $menu = 'logged-out';
   }
   wp_nav_menu(array(
    'theme_location' => 'header-menu',
    'menu_class'=> $menu.' sf-menu',
    'fallback_cb' => 'td_wp_page_menu',
    'walker' => new td_tagdiv_walker_nav_menu()
    ));
   // wp_nav_menu(array(
   //  'theme_location' => 'header-menu',
   //  'menu_class'=> 'sf-menu',
   //  'fallback_cb' => 'td_wp_page_menu',
   //  'walker' => new td_tagdiv_walker_nav_menu()
   //  ));

    //if no menu
    function td_wp_page_menu() {
        //this is the default menu
        echo '<ul class="sf-menu">';
        echo '<li class="menu-item-first"><a href="' . esc_url(home_url( '/' )) . 'wp-admin/nav-menus.php?action=locations">Click here - to select or create a menu</a></li>';
        echo '</ul>';
    }
    ?>
</div>

<!-- ZA Custom -->
<div class="td-search-wrapper popmake-495">
    <div id="td-top-search" style="right: 15px;">
        <div class="header-search-wrap">
            <div class="dropdown header-search" style="line-height: 42px;">
                <a href="http://rabbitworld.ready.co.th/?post_type=popup&p=495" role="button" class="popmake-495">
                    <img src="http://rabbitworld.ready.co.th/wp-content/uploads/2015/11/icon-risingArrow.png" width="20px">
                </a>
            </div>
        </div>
    </div>
</div>

<!-- End ZA Custom -->
<div class="td-search-wrapper">
    <div id="td-top-search">
        <!-- Search -->
        <div class="header-search-wrap">
            <div class="dropdown header-search">
                <a id="td-header-search-button" href="#" role="button" class="dropdown-toggle " data-toggle="dropdown"><i class="td-icon-search"></i></a>
            </div>
            <!-- ZA Custom -->
            <div class="td-search-wrapper popmake-565">
                <button href="#"><img src="/wp-content/uploads/2015/11/icon-subscribe.png" alt=""> <span>รับข่าวสาร</span></button>
            </div>
            <div class="ZA-dropdown-subscribe">
                <div class="ZA-dropdown-close">
                    <img src="/wp-content/uploads/2015/12/icon-close.png" alt="">
                </div>
                <?php echo do_shortcode('[widget id="sendy_widget-2"]'); ?>
            </div>
            <script>
                jQuery(".popmake-565 button").click(function(){
                    jQuery(".ZA-dropdown-subscribe").toggle();
                });
                jQuery(".ZA-dropdown-close img").click(function(){
                    jQuery(".ZA-dropdown-subscribe").hide();
                });
            </script>
            <!-- End ZA Custom -->
        </div>
    </div>
</div>

<div class="header-search-wrap">
	<div class="dropdown header-search">
		<div class="td-drop-down-search" aria-labelledby="td-header-search-button">
			<form role="search" method="get" class="td-search-form" action="<?php echo esc_url(home_url( '/' )); ?>">
				<div class="td-head-form-search-wrap">
					<input id="td-header-search" type="text" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" /><input class="wpb_button wpb_btn-inverse btn" type="submit" id="td-header-search-top" value="<?php _etd('Search', TD_THEME_NAME)?>" />
				</div>
			</form>
			<div id="td-aj-search"></div>
		</div>
	</div>
</div>

<!-- ZA Custom -->
<!-- <div class="td-search-wrapper popmake-565">
<a href="#"><img src="/wp-content/uploads/2015/11/icon-subscribe.png" alt="">subscribe</a>
</div> -->