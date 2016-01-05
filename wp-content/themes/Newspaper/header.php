<!doctype html >
<!--[if IE 8]>    <html class="ie8" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <title><?php wp_title('|', true, 'right'); ?></title>
    <meta charset="<?php bloginfo( 'charset' );?>" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php
    wp_head(); /** we hook up in wp_booster @see td_wp_booster_functions::hook_wp_head */
    ?>
    <link rel="stylesheet" id="wpfp-css" href="http://rabbitworld.ready.co.th/wp-content/plugins/wp-favorite-posts/wpfp.css" type="text/css">
</head>


<body <?php body_class() ?> itemscope="itemscope" itemtype="<?php echo td_global::$http_or_https?>://schema.org/WebPage">
<!-- hidden login, register menu -->
<?php hidden_menu() ?>

<?php //this is closing in the footer.php file ?>

<?php /* scroll to top */?>
<div class="td-scroll-up"><i class="td-icon-menu-up"></i></div>

<div id="td-outer-wrap">

    <div class="td-transition-content-and-menu td-mobile-nav-wrap">
        
        <?php 
        // ZA Custom
        if ( is_user_logged_in() ) {
            echo '<div class="mobile-user">'.do_shortcode('[current-avatar]');
            echo 'สวัสดี, '.do_shortcode('[current-firstname]').'</div>';
            echo '<a href="'.wp_logout_url().'"><div class="mobile-logout">ออกจากระบบ</div></a>';
            
        }else{
            // echo '<a href="#" class="fb-login fb-login-logo" data-zm_alr_facebook_security="6c59ea6c99"><img src="/wp-content/uploads/2015/11/RBW_menu_not_login_crop.png"></a>';
            echo '<a href="#" class="fb-login fb-login-logo" data-zm_alr_facebook_security="6c59ea6c99"><div class="ZA-facebook-login-mainmenu-mobile">เข้าสู่ระบบด้วย Facebook</div></a>';
            echo '<div class="register-dialog"><a href="#"><div class="ZA-login-mainmenu-mobile">สมัครสมาชิก</div></a></div>';
        }
        ?>
            <div class="mobile-main-menu-search">
                <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
                    <label>
                        <span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
                        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'ค้นหาอะไรบ้าง', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
                        <button type="reset" value="Reset">x</button>
                    </label>
                    
                </form>
            </div>
<?php

        locate_template('parts/menu-mobile.php', true);

        // ZA Custom
        echo do_shortcode('[widget id="td_block_social_counter_widget-2"]');
        echo do_shortcode('[widget id="sendy_widget-2"]');
        ?>

    </div>

    <?php //this is closing in the footer.php file ?>
    <div class="td-transition-content-and-menu td-content-wrap">



<?php
td_api_header_style::_helper_show_header();

do_action('td_wp_booster_after_header'); //used by unique articles
