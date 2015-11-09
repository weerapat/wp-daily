<?php

/**
 * Class td_block - base class for blocks
 * v 4.0 - wp_010
 */
class td_block {
    var $block_id; // the block type
    var $block_uid; // the block unique id, it changes on every render

    var $atts; //the atts used for rendering the current block
    var $td_query; //the query used to rendering the current block

    private $td_block_template_instance; // the current block template instance that this block is using
    protected $td_block_template_data;


    function __construct() {
        $this->block_id = get_class($this); // set the current block type id It is the class name of the parent block (ex: td_block_4)
    }



    /**
     * the base render function. This is called by all the child classes of this class
     * this function also ECHOES the block specific css to the buffer (for hover and stuff)
     * WARNING! THIS FUNCTIONS ECHOs THE CSS - it was made to work this way as a hack because the blocks do not get the returned value of render in a buffer
     * @param $atts
     * @return string ''
     */
    function render($atts, $content = null) {

        $this->atts = $this->add_live_filter_atts($atts); //add live filter atts
        $this->block_uid = td_global::td_generate_unique_id(); //update unique id on each render

        // This makes sure that the limit is set to the default magic value of 5
        // @todo trebuie refactoriata partea cu limita, in paginatie e hardcodat tot 5 si deja este setat in constructor aici
	    if (!isset($this->atts['limit'])) {
		    // this should be a general block limit setting defined in global/config file
		    $this->atts['limit'] = 5;
	    }

        $this->td_query = &td_data_source::get_wp_query($this->atts); //by ref do the query


        extract(shortcode_atts(
            array(
                'td_ajax_filter_type' => '',
                'td_ajax_filter_ids' => '',
                'td_filter_default_txt' => __td('All', TD_THEME_NAME),
                //'css' => ''  //visual composer designer options
            ),$this->atts));

        // add the visual composer class for the designer option
        // $vc_class = preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $css);
        // $this->add_class($vc_class);

        $td_pull_down_items = array();

        // td_block_mega_menu has it's own pull down implementation!
        if (get_class($this) != 'td_block_mega_menu') {
            // prepare the array for the td_pull_down_items, we send this array to the block_template

            if (!empty($td_ajax_filter_type)) {

                // make the default current pull down item (the first one is the default)
                $td_pull_down_items[0] = array (
                    'name' => $td_filter_default_txt,
                    'id' => ''
                );

                switch($td_ajax_filter_type) {
                    case 'td_category_ids_filter': // by category
                        $td_categories = get_categories(array(
                            'include' => $td_ajax_filter_ids,
                            'exclude' => '1',
                            'number' => 100 //limit the number of categories shown in the drop down
                        ));
                        foreach ($td_categories as $td_category) {
                            $td_pull_down_items []= array (
                                'name' => $td_category->name,
                                'id' => $td_category->cat_ID,
                            );
                        }
                        break;

                    case 'td_author_ids_filter': // by author
                        $td_authors = get_users(array('who' => 'authors', 'include' => $td_ajax_filter_ids));
                        foreach ($td_authors as $td_author) {
                            $td_pull_down_items []= array (
                                'name' => $td_author->display_name,
                                'id' => $td_author->ID,
                            );
                        }
                        break;

                    case 'td_tag_slug_filter': // by tag slug
                        $td_tags = get_tags(array(
                            'include' => $td_ajax_filter_ids
                        ));
                        foreach ($td_tags as $td_tag) {
                            $td_pull_down_items []= array (
                                'name' => $td_tag->name,
                                'id' => $td_tag->term_id,
                            );
                        }
                        break;

                    case 'td_popularity_filter_fa': // by popularity
                        $td_pull_down_items []= array (
                            'name' => __td('Featured', TD_THEME_NAME),
                            'id' => 'featured',
                        );
                        $td_pull_down_items []= array (
                            'name' => __td('All time popular', TD_THEME_NAME),
                            'id' => 'popular',
                        );
                        break;
                }
            }
        }



        // add a persistent atts based block class (crc32 of atts + block_id)
        if (is_array($this->atts)) {  // double check to prevent warnings if no atts
            $this->add_class('td_block_id_' .
                sanitize_html_class(
                    str_replace('-', '',
                        crc32(
                            implode($this->atts) . $this->block_id
                        )
                    )
                )
            );
        }


        // add a unique class to the block
        $unique_block_class = $this->block_uid . '_rand';
        $this->add_class($unique_block_class);


        /**
         * Make a new block template instance (NOTE: ON EACH RENDER WE GENERATE A NEW BLOCK TEMPLATE)
         * td_block_template_x - Loaded via autoload
         * @see td_autoload_classes::loading_classes
         */
        $td_block_template_id = 'td_block_template_1';

        $this->td_block_template_data = array(
            'atts' => $this->atts,
            'block_uid' => $this->block_uid,
            'unique_block_class' => $unique_block_class,
            'td_pull_down_items' => $td_pull_down_items,
        );
        $this->td_block_template_instance = new $td_block_template_id($this->td_block_template_data);





        // echo the default style of the block
        echo $this->block_template()->get_css();

        return '';
    }


    /**
     * this function adds the live filters atts (for example the current category or the current post)
     * @param $atts
     * @return mixed
     */
    function add_live_filter_atts($atts) {
        if (!empty($atts['live_filter'])) {
            $atts['live_filter_cur_post_id'] = get_queried_object_id(); //add the current post id
            $atts['live_filter_cur_post_author'] =  get_post_field( 'post_author', $atts['live_filter_cur_post_id']); //get the current author
        }
        return $atts;
    }



    /**
     * Used by blocks that need auto generated titles
     * @return string
     */
    function get_block_title() {
        return $this->block_template()->get_block_title();
    }


    /**
     * shows a pull down filter based on the $this->atts
     * @return string
     */
    function get_pull_down_filter() {
        return $this->block_template()->get_pull_down_filter();
    }




    function get_block_pagination() {
        extract(shortcode_atts(
            array(
                'limit' => 5,
                'sort' => '',
                'category_id' => '',
                'category_ids' => '',
                'custom_title' => '',
                'custom_url' => '',
                'show_child_cat' => '',
                'sub_cat_ajax' => '',
                'ajax_pagination' => ''
            ),$this->atts));

	    $offset = 0;

	    if (isset($this->atts['offset'])) {
		    $offset = $this->atts['offset'];
	    }

	    $buffy = '';

        switch ($ajax_pagination) {

            case 'next_prev':
                    $buffy .= '<div class="td-next-prev-wrap">';
                    $buffy .= '<a href="#" class="td-ajax-prev-page ajax-page-disabled" id="prev-page-' . $this->block_uid . '" data-td_block_id="' . $this->block_uid . '"><i class="td-icon-font td-icon-menu-left"></i></a>';

					//if ($this->td_query->found_posts <= $limit) {
					if ($this->td_query->found_posts - $offset <= $limit) {
                        //hide next page because we don't have enough results
                        $buffy .= '<a href="#"  class="td-ajax-next-page ajax-page-disabled" id="next-page-' . $this->block_uid . '" data-td_block_id="' . $this->block_uid . '"><i class="td-icon-font td-icon-menu-right"></i></a>';
                    } else {
                        $buffy .= '<a href="#"  class="td-ajax-next-page" id="next-page-' . $this->block_uid . '" data-td_block_id="' . $this->block_uid . '"><i class="td-icon-font td-icon-menu-right"></i></a>';
                    }

                    $buffy .= '</div>';
                break;

            case 'load_more':
	            //if ($this->td_query->found_posts > $limit) {
	            if ($this->td_query->found_posts - $offset > $limit) {
		            $buffy .= '<div class="td-load-more-wrap">';
                    $buffy .= '<a href="#" class="td_ajax_load_more td_ajax_load_more_js" id="next-page-' . $this->block_uid . '" data-td_block_id="' . $this->block_uid . '">' . __td('Load more', TD_THEME_NAME);
		            $buffy .= '<i class="td-icon-font td-icon-menu-down"></i>';
		            $buffy .= '</a>';
		            $buffy .= '</div>';
	            }
                break;

            case 'infinite':
				// show the infinite pagination only if we have more posts
		        if ($this->td_query->found_posts - $offset > $limit) {
		            $buffy .= '<div class="td_ajax_infinite" id="next-page-' . $this->block_uid . '" data-td_block_id="' . $this->block_uid . '">';
		            $buffy .= ' ';
		            $buffy .= '</div>';


		            $buffy .= '<div class="td-load-more-wrap td-load-more-infinite-wrap" id="infinite-lm-' . $this->block_uid . '">';
                    $buffy .= '<a href="#" class="td_ajax_load_more td_ajax_load_more_js" id="next-page-' . $this->block_uid . '" data-td_block_id="' . $this->block_uid . '">' . __td('Load more', TD_THEME_NAME);
		            $buffy .= '<i class="td-icon-font td-icon-menu-down"></i>';
		            $buffy .= '</a>';
		            $buffy .= '</div>';
	            }
                break;

        }

        return $buffy;
    }




    function get_block_js() {

        //get the js for this block - do not load it in inline mode in visual composer
        if (td_util::vc_is_inline()) {
            return '';
        }

        extract(shortcode_atts(
            array(
                'limit' => 5,
                'sort' => '',
                'category_id' => '',
                'category_ids' => '',
                'custom_title' => '',
                'custom_url' => '',
                'show_child_cat' => '',
                'sub_cat_ajax' => '',
                'ajax_pagination' => '',
                'header_color' => '',
                'ajax_pagination_infinite_stop' => '',
                'td_column_number' => '' //pass a user defined column number to the block
            ), $this->atts));


	    if (!empty($this->atts['custom_title'])) {
            $this->atts['custom_title'] = htmlspecialchars($this->atts['custom_title'], ENT_QUOTES );
        }

        if (!empty($this->atts['custom_url'])) {
            $this->atts['custom_url'] = htmlspecialchars($this->atts['custom_url'], ENT_QUOTES );
        }

        if (empty($td_column_number)) {
            $td_column_number = td_util::vc_get_column_number(); // get the column width of the block so we can sent it to the server. If the shortcode already has a user defined column number, we use that
        }


        $block_item = 'block_' . $this->block_uid;

        $buffy = '';

        $buffy .= '<script>';
        $buffy .= 'var ' . $block_item . ' = new tdBlock();' . "\n";
        $buffy .= $block_item . '.id = "' . $this->block_uid . '";' . "\n";
        $buffy .= $block_item . ".atts = '" . json_encode($this->atts) . "';" . "\n";
        $buffy .= $block_item . '.td_column_number = "' . $td_column_number . '";' . "\n";
        $buffy .= $block_item . '.block_type = "' . $this->block_id . '";' . "\n";

        //wordpress wp query parms
        $buffy .= $block_item . '.post_count = "' . $this->td_query->post_count . '";' . "\n";
        $buffy .= $block_item . '.found_posts = "' . $this->td_query->found_posts . '";' . "\n";

	    $buffy .= $block_item . '.header_color = "' . $header_color . '";' . "\n";
        $buffy .= $block_item . '.ajax_pagination_infinite_stop = "' . $ajax_pagination_infinite_stop . '";' . "\n";


		// The max_num_pages is computed so it considers the offset and the limit atts settings
	    // There were necessary these changes because on the user interface there are js scripts that use the max_num_pages js variable to show/hide some ui components
	    if (!empty($this->atts['offset'])) {

		    if ($this->atts['limit'] != 0) {
			    $buffy .= $block_item . '.max_num_pages = "' . ceil( ( $this->td_query->found_posts - $this->atts['offset'] ) / $this->atts['limit'] ) . '";' . "\n";

		    } else if (get_option('posts_per_page') != 0) {
			    $buffy .= $block_item . '.max_num_pages = "' . ceil( ( $this->td_query->found_posts - $this->atts['offset'] ) / get_option('posts_per_page') ) . '";' . "\n";
		    }
	    } else {
		    $buffy .= $block_item . '.max_num_pages = "' . $this->td_query->max_num_pages . '";' . "\n";
	    }

        $buffy .= 'tdBlocksArray.push(' . $block_item . ');' . "\n";
        $buffy .= '</script>';



        //print_r($this->td_block_template_data);





        // ajax subcategories preloader
        // @todo preloading "all" filter content should happen regardless of the setting
        if (
            !empty($this->td_block_template_data['td_pull_down_items'])
            and !empty($this->atts['td_ajax_preloading'])
        ) {


	        /*  -------------------------------------------------------------------------------------
	            add 'ALL' item to the cache
	        */
            // pagination - we need to compute the pagination for each cache entry
            $td_hide_next = false;
            if (!empty($this->atts['offset']) && !empty($this->atts['limit']) && ($this->atts['limit'] != 0)) {
                if (1 >= ceil(($this->td_query->found_posts - $this->atts['offset']) / $this->atts['limit'])) {
                    $td_hide_next = true; //hide link on last page
                }
            } else if (1 >= $this->td_query->max_num_pages) {
                $td_hide_next = true; //hide link on last page
            }

            // this will be send to JS bellow
            $buffyArray = array (
                'td_data' => $this->inner($this->td_query->posts, $td_column_number),
                'td_block_id' => $this->block_uid,
                'td_hide_prev' => true,  // this is the first page
                'td_hide_next' => $td_hide_next
            );



	        /*  -------------------------------------------------------------------------------------
	            add the rest of the items to the local cache
	        */
            ob_start();
            // we need to clone the object to set is_ajax_running to true
            // first we set an object for the all filter
            ?>
            <script>
                var tmpObj = JSON.parse(JSON.stringify(<?php echo $block_item ?>));
                tmpObj.is_ajax_running = true;
                var currentBlockObjSignature = JSON.stringify(tmpObj);
                tdLocalCache.set(currentBlockObjSignature, JSON.stringify(<?php echo json_encode($buffyArray) ?>));
                <?php
                    foreach ($this->td_block_template_data['td_pull_down_items'] as $count => $item) {
                        if (empty($item['id'])) {
                            continue;
                        }

                        // preload only 6 or 20 items depending on the setting
                        if ($this->atts['td_ajax_preloading'] == 'preload_all' and $count > 20) {
                            break;
                        }
                        else if ($this->atts['td_ajax_preloading'] == 'preload' and $count > 6) {
                            break;
                        }

                        $ajax_parameters = array (
                            'td_atts' => $this->atts,            // original block atts
                            'td_column_number' => $td_column_number,    // should not be 0 (1 - 2 - 3)
                            'td_current_page' => 1,    // the current page of the block
                            'td_block_id' => $this->block_uid,        // block uid
                            'block_type' => get_class($this),         // the type of the block / block class
                            'td_filter_value' => $item['id']     // the id for this specific filter type. The filter type is in the td_atts
                        );
                        ?>
                            tmpObj = JSON.parse(JSON.stringify(<?php echo $block_item ?>));
                            tmpObj.is_ajax_running = true;
                            tmpObj.td_current_page = 1;
                            tmpObj.td_filter_value = <?php echo $item['id'] ?>;
                            var currentBlockObjSignature = JSON.stringify(tmpObj);
                            tdLocalCache.set(currentBlockObjSignature, JSON.stringify(<?php echo td_ajax_block($ajax_parameters) ?>));
                        <?php
                    }
                ?>
            </script>
            <?php
            //ob_clean();
            $buffy.= ob_get_clean();
        } // end preloader if





        return $buffy;
    }

    /**
     * @param $additional_classes_array - array of classes to add to the block
     * @return string
     */
    function get_block_classes($additional_classes_array = '') {
        $color_preset = '';

        extract(shortcode_atts(
            array(
                'color_preset' => '',
                'border_top' => '',
                'class' => '', //add additional classes via short code - used by the widget builder to add the td_block_widget class
            ),$this->atts));


        //add the block wrap and block id class
        $block_classes = array(
            'td_block_wrap',
            $this->block_id
        );

        //add the classes that we receive via shortcode
        if (!empty($class)) {
            $class_array = explode(' ', $class);
            $block_classes = array_merge(
                $block_classes,
                $class_array
            );
        }

        //marge the additional classes received from blocks code
        if ($additional_classes_array != '') {
            $block_classes = array_merge(
                $block_classes,
                $additional_classes_array
            );
        }


        //add the full cell class + the color preset class
        if (!empty($color_preset)) {
            $block_classes[]= 'td-pb-full-cell';
            $block_classes[]= $color_preset;
        }


	    /**
	     * - used to add td_block_loading css class on the blocks having pagination
	     * - the class has a force css transform for lazy devices
	     */
	    if (array_key_exists('ajax_pagination', $this->atts)) {
		    $block_classes[]= 'td_with_ajax_pagination';
	    }


        /**
         * add the border top class - this one comes from the atts
         */
        if (empty($border_top)) {
            $block_classes[]= 'td-pb-border-top';
        }


        //remove duplicates
        $block_classes = array_unique($block_classes);


        return implode(' ', $block_classes);
    }


    /**
     * adds a class to the current block's ats
     * @param $raw_class_name string the class name is not sanitized, so make sure you send a sanitized one
     */
    private function add_class($raw_class_name) {
        if (!empty($this->atts['class'])) {
            $this->atts['class'] = $this->atts['class'] . ' ' . $raw_class_name;
        } else {
            $this->atts['class'] = $raw_class_name;
        }
    }


    /**
     * gets the current template instance, if no instance it's found throws error
     * @return mixed the template instance
     * @throws ErrorException - no template instance found
     */
    private function block_template() {
        if (isset($this->td_block_template_instance)) {
            return $this->td_block_template_instance;
        } else {
            throw new ErrorException("td_block: " . get_class($this) . " did not call render, no td_block_template_instance in td_block");
        }
    }


}

