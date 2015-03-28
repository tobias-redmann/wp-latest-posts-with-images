<?php
/*
Plugin Name: WP Latest Posts with images
Plugin URI: https://www.tricd.de/
Description: Add a widget which show the latest posts with images
Version: 0.1
Author: Tobias Redmann
Author URI: http://www.tricd.de
*/



class LatestPostsWithImages extends WP_Widget {

    static $textDomain = 'LatestPostsWithImages';


    function __construct() {
        parent::__construct(
            'latest_posts_with_images', // Base ID
            __( 'Latest Posts with Images widget', self::$textDomain ), // Name
            array( 'description' => __( 'Show Latest Posts with thumbnail images', self::$textDomain ), ) // Args
        );
    }



    public function widget( $args, $instance ) {

        ?>
        <style>

            .rpwi-item {
                margin-bottom: 1em;
                position: relative
            }

            .rpwi-item__thumbnail {
                position: absolute;
                top: 0;
                left: 0;
                height: auto;
                width: 90px;
                height: auto
            }

            .rpwi-item__text {
                position: relative;
                margin-left: 100px;
                min-height: 90px;
                line-height: 120%;
                font-size: 1.2em;
            }
        </style>

        <?php
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }
        //echo __( 'Hello, World!', 'text_domain' );


        $queryArgs = array(
            'post_type' => 'post',
            'posts_per_page' => 20,
            'no_found_rows' => true,
            'orderby'   => 'date'

        );

        $q = new WP_Query($queryArgs);

        foreach($q->posts as $p) {


            ?>

            <div class="rpwi-item">

                <a href="<?php echo get_permalink($p->ID); ?>">

                    <div class="rpwi-item__thumbnail">

                        <?php echo get_the_post_thumbnail( $p->ID, array(90, 90) ); ?>

                    </div>

                    <div class="rpwi-item__text">

                        <?php echo $p->post_title; ?>

                    </div>

                </a>

            </div>

            <?php

        }


        echo $args['after_widget'];
    }

    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['message'] = strip_tags($new_instance['message']);
        return $instance;
    }

    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {

        $title 		= esc_attr($instance['title']);
        $message	= esc_attr($instance['message']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Simple Message'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>" type="text" value="<?php echo $message; ?>" />
        </p>
    <?php
    }


} // end class example_widget
add_action('widgets_init', create_function('', 'return register_widget("LatestPostsWithImages");'));