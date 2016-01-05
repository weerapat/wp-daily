<?php
$wpfp_before = "";
echo "<div class='wpfp-span'>";
if (!empty($user)) {
    if (wpfp_is_user_favlist_public($user)) {
        $wpfp_before = "$user's Favorite Posts.";
    } else {
        $wpfp_before = "$user's list is not public.";
    }
}

if ($wpfp_before):
    echo '<div class="wpfp-page-before">'.$wpfp_before.'</div>';
endif;

if ($favorite_post_ids) {
  $favorite_post_ids = array_reverse($favorite_post_ids);
  $post_per_page = wpfp_get_option("post_per_page");
  $page = intval(get_query_var('paged'));

  $qry = array('post__in' => $favorite_post_ids, 'posts_per_page'=> $post_per_page, 'orderby' => 'post__in', 'paged' => $page);
        // custom post type support can easily be added with a line of code like below.
        // $qry['post_type'] = array('post','page');
  query_posts($qry);

//ZA Custom//
?>
<div class="wpfp-title">
    <h4 class="td-related-title">
        เก็บบทความ
        <div class="wpfp-title-link">
            <div class="wpfp-title-link-move">
                <a href="#" id="filter_all" class="wpfp-title-option">เลือกทั้งหมด</a>
                <a href="#" id="filter_clear" class="wpfp-title-option">ล้างตัวกรอง</a>
            </div>
            <a href="#" id="filter_hide">ซ่อนตัวกรอง <img src="/wp-content/uploads/2015/12/icon-caret-up.png" alt=""></a>
            <a href="#" id="filter_show">ตัวกรอง <img src="/wp-content/uploads/2015/12/icon-caret-down.png" alt=""></a>
        </div>
    </h4>
    <form id="filter_list">
        <div class="row">
            <div class="col-md-3"><div class="filter_list-block1"><div class="filter_list-block2"><div class="filter_list-text">
                <input type="checkbox" checked id="cate3" /><label for="cate3"><div class="icon-checked"><img src="/wp-content/uploads/2015/12/icon-checked.png" alt=""></div> ไลฟ์สไตล์</label>
            </div></div></div></div>
            <div class="col-md-3"><div class="filter_list-block1"><div class="filter_list-block2"><div class="filter_list-text">
                <input type="checkbox" checked id="cate4"/><label for="cate4"><div class="icon-checked"><img src="/wp-content/uploads/2015/12/icon-checked.png" alt=""></div> ไอที</label>
            </div></div></div></div>
            <div class="col-md-3"><div class="filter_list-block1"><div class="filter_list-block2"><div class="filter_list-text">
                <input type="checkbox" checked id="cate5"/><label for="cate5"><div class="icon-checked"><img src="/wp-content/uploads/2015/12/icon-checked.png" alt=""></div> ท่องเที่ยว</label>
            </div></div></div></div>
            <div class="col-md-3"><div class="filter_list-block1"><div class="filter_list-block2"><div class="filter_list-text">
                <input type="checkbox" checked id="cate6"/><label for="cate6"><div class="icon-checked"><img src="/wp-content/uploads/2015/12/icon-checked.png" alt=""></div> บันเทิง</label>
            </div></div></div></div>
        </div>
        <div class="row">
            <div class="col-md-3"><div class="filter_list-block1"><div class="filter_list-block2"><div class="filter_list-text">
                <input type="checkbox" checked id="cate7"/><label for="cate7"><div class="icon-checked"><img src="/wp-content/uploads/2015/12/icon-checked.png" alt=""></div> ผู้หญิง</label>
            </div></div></div></div>
            <div class="col-md-3"><div class="filter_list-block1"><div class="filter_list-block2"><div class="filter_list-text">
                <input type="checkbox" checked id="cate8"/><label for="cate8"><div class="icon-checked"><img src="/wp-content/uploads/2015/12/icon-checked.png" alt=""></div> ผู้ชาย</label>
            </div></div></div></div>
            <div class="col-md-3"><div class="filter_list-block1"><div class="filter_list-block2"><div class="filter_list-text">
                <input type="checkbox" checked id="cate10"/><label for="cate10"><div class="icon-checked"><img src="/wp-content/uploads/2015/12/icon-checked.png" alt=""></div> ธุรกิจ</label>
            </div></div></div></div>
            <div class="col-md-3"><div class="filter_list-block1"><div class="filter_list-block2"><div class="filter_list-text">
                <input type="checkbox" checked id="cate9"/><label for="cate9"><div class="icon-checked"><img src="/wp-content/uploads/2015/12/icon-checked.png" alt=""></div> เรื่องเด่น</label>
            </div></div></div></div>
        </div>
    </form>
</div>

<script>
jQuery( document ).ready(function() {
    jQuery("#filter_list input[type=checkbox]").click(function(){
        check_cate(jQuery(this));
    })

    jQuery("#filter_all").hide();
    jQuery("#filter_clear").hide();
    jQuery("#filter_hide").hide();
    jQuery("#filter_list").hide();
    jQuery("#filter_show").click(function(){
        showFilter();
    })
    jQuery("#filter_hide").click(function(){
        hideFilter();
    })
    jQuery("#filter_all").click(function(){
        jQuery("#filter_list input[type=checkbox]").attr('checked','checked');
        jQuery(".td_module_mx12").fadeIn();
        jQuery(".icon-checked img").show();
        jQuery( ".display" ).eq(0).removeClass( "first" );
        jQuery( ".td_module_mx12" ).addClass( "display" );
        jQuery( ".display" ).eq(0).addClass( "first" );
    })
    jQuery("#filter_clear").click(function(){
        jQuery("#filter_list input[type=checkbox]").removeAttr('checked');
        jQuery(".td_module_mx12").fadeOut();
        jQuery(".icon-checked img").hide();
        jQuery( ".display" ).eq(0).removeClass( "first" );
        jQuery( ".td_module_mx12" ).removeClass( "display" );
    })
    function showFilter(){
        jQuery("#filter_list").fadeIn();
        jQuery("#filter_hide").fadeIn();
        jQuery("#filter_show").hide();
        jQuery("#filter_clear").fadeIn();
        jQuery("#filter_all").fadeIn();
    }

    function hideFilter(){
        jQuery("#filter_list").fadeOut();
        jQuery("#filter_hide").hide();
        jQuery("#filter_show").fadeIn();
        jQuery("#filter_clear").fadeOut();
        jQuery("#filter_all").fadeOut();
    }

    function check_cate(me){
        if(jQuery(me).is(':checked')){
            jQuery("."+jQuery(me).attr("id")).fadeIn();
            jQuery("label[for='"+jQuery(me).attr("id")+"'] .icon-checked img").show();
            jQuery( ".display" ).eq(0).removeClass( "first" );
            jQuery( "."+jQuery(me).attr("id") ).addClass( "display" );
            jQuery( ".display" ).eq(0).addClass( "first" );
        }
        else{
            jQuery("."+jQuery(me).attr("id")).fadeOut();
            jQuery("label[for='"+jQuery(me).attr("id")+"'] .icon-checked img").hide();
            jQuery( ".display" ).eq(0).removeClass( "first" );
            jQuery( "."+jQuery(me).attr("id") ).removeClass( "display" );
            jQuery( ".display" ).eq(0).addClass( "first" );
        }
    }

    
});
</script>

  <div class="td_block_wrap td_block_big_grid_5 td_block_id_1017292846 td_uid_47_565c0d0c0cfa7_rand td-grid-style-1 td-hover-1 td-pb-border-top">
    <div id="td_uid_47_565c0d0c0cfa7" class="td_block_inner">
        <div class="td-big-grid-wrapper">
            <?php 
            while ( have_posts() ) : the_post(); 
            // ZA Custom
            $categories = get_the_category( $post->ID );
            $cat_id = $categories[0]-> cat_ID;
            $category = get_the_category();
            $catParID = $category[0]->category_parent;
            if ($catParID==0) {
                $catParID = $cat_id;
            }
            switch ($catParID) {
                case '3':
                    $color = '#ad47b3';
                    break;
                case '4 ':
                    $color = '#1A6CBE';
                    break;
                case '7':
                    $color = '#FF3963';
                    break;
                case '8':
                    $color = '#4E5864';
                    break;
                case '5':
                    $color = '#84bd00';
                    break;
                case '6':
                    $color = '#FFC000';
                    break;
                case '9':
                    $color = '#ff0000';
                    break;
                case '10':
                    $color = '#00abc4';
                    break;
                default:
                    $color = '#fd6f08';
                    break;
            }
            // end ZA Custom
            ?>
            <div class="td_module_mx12 td-animation-stack td-big-grid-post-1 td-big-grid-post td-small-thumb cate<?php echo $catParID; ?> display" itemscope="" itemtype="http://schema.org/Article">
                <div class="td-module-thumb">
                    <img style="height: 100%" itemprop="image" class="entry-thumb td-animation-stack-type0-2" src="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" alt="" title="<?php echo get_the_title() ?>">
                    <!-- <a id="bookmark_style" style="position: absolute;right: 5px;top: 5px;z-index:2;" href="?wpfpaction=add&amp;<?php echo get_the_ID(); ?>" title="บันทึกบทความ" rel="nofollow">
                        <img src="/wp-content/uploads/2015/11/icon-bookmark-added.png" class="td-animation-stack-type0-2">
                    </a> -->
                    <a href="<?php echo get_permalink(); ?>" rel="bookmark" title="<?php echo get_the_title() ?>"></a>
                </div>           
                <div class="td-meta-info-container">
                    <div class="td-meta-align">
                        <div class="td-big-grid-meta">
                            <a href="/?cat=<?php echo $categories[0]->cat_ID; ?>" class="td-post-category" style="background-color: <?php echo $color ?>"><?php echo $categories[0]->cat_name; ?></a>
                            <h3 itemprop="name" class="entry-title td-module-title">
                                <a itemprop="url" href="<?php echo get_permalink(); ?>" rel="bookmark" title="<?php echo get_the_title() ?>"><?php echo get_the_title() ?></a>
                            </h3>
                        </div>                  
                    </div>
                </div>
            </div>

            <meta itemprop="author" content="admin">
            <meta itemprop="datePublished" content="2015-11-27T15:11:28+00:00">
            <meta itemprop="headline " content="<?php echo get_the_title() ?>">
            <meta itemprop="image" content="http://i0.wp.com/rabbitworld.ready.co.th/wp-content/uploads/2015/11/ดูดวงพฤษจิกายน_feature-image-1024x683.jpg?resize=1024%2C683">
            <meta itemprop="interactionCount" content="UserComments:0">        
            <?php 
            endwhile; ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<?php
//close custom//

        // echo "<ul>";
        // while ( have_posts() ) : the_post();
        //     echo "<li><a href='".get_permalink()."' title='". get_the_title() ."'>" . get_the_title() . "</a> ";
        //     wpfp_remove_favorite_link(get_the_ID());
        //     echo "</li>";
        // endwhile;
        // echo "</ul>";

echo '<div class="navigation">';
if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
<div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'buddypress' ) ) ?></div>
<div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'buddypress' ) ) ?></div>
<?php }
echo '</div>';

wp_reset_query();
} else {
    $wpfp_options = wpfp_get_options();
    echo "<ul><li>";
    echo $wpfp_options['favorites_empty'];
    echo "</li></ul>";
}

echo '<p>'.wpfp_clear_list_link().'</p>';
echo "</div>";
wpfp_cookie_warning();
