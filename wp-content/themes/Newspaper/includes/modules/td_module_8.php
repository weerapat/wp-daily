<?php

class td_module_8 extends td_module {

    function __construct($post) {
        //run the parrent constructor
        parent::__construct($post);
    }

    function render() {
        ob_start();
        ?>

        <div class="<?php echo $this->get_module_classes();?>" <?php echo $this->get_item_scope();?>>

            <div class="item-details">
                <div class="td-module-meta-info">
                    <?php if (td_util::get_option('tds_category_module_8') == 'yes') { echo $this->get_category(); }?>
                    <?php // echo $this->get_author();?>
                    <?php // echo $this->get_date();?>
                    <?php //echo $this->get_comments();?>
                </div>
                <?php echo $this->get_title();?>

            </div>

            <?php echo $this->get_quotes_on_blocks();?>

            <?php echo $this->get_item_scope_meta();?>
        </div>

        <?php return ob_get_clean();
    }
}