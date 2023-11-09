<?php
/* Use shortcode [dwk_tabsmenu] */
add_shortcode( 'dwk_tabsmenu', 'dwk_tabsmenu_shortcode' );
function dwk_tabsmenu_shortcode(){
   ob_start(); ?>

  <div class="container">
    <div class="row">
      <ul class="tabs">
        <?php
          // Get the taxonomy's terms
            
            $terms = get_terms(
            array(
                'taxonomy'   => 'dwk_menu_cat',
                'hide_empty' => false,
            ),
            
            );
            
            // Check if any term exists
            if ( ! empty( $terms ) && is_array( $terms ) ) {
                
            $j=1;
            
            // Run a loop and print them all
            foreach ( $terms as $term ) {
                
                ?>
        <li rel="tab-<?php echo $term->slug; ?>"> <?php echo $term->name; ?></li><?php
            }
            } 
          ?>
      </ul>
      <div class="tab_container">
        <?php
            // Get list of all taxonomy terms  -- In simple categories title
            $args = array(
                        'taxonomy' => 'dwk_menu_cat',
                        'orderby' => 'name',
                       
                    );
            $cats = get_categories($args);
            $m=1;
            $i=1;

            // For every Terms of custom taxonomy get their posts by term_id
            foreach($cats as $cat) {
        ?>
        <h3 class="tab_drawer_heading" rel="tab-<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></h3>
        <div id="tab-<?php echo $cat->slug; ?>" class="tab_content">
          <?php
                // Query Arguments
                $args = array(
                    'post_type' => 'dwk_menu', // the post type
                    'posts_per_page' => 6,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'dwk_menu_cat', // the custom vocabulary
                            'field'    => 'term_id', 
                                     // term_id, slug or name  (Define by what you want to search the below term)    
                            'terms'    => $cat->term_id,      // provide the term slugs
                        ),
                    ),
                );

                // The query
                $the_query = new WP_Query( $args );

                // The Loop
                if ( $the_query->have_posts() ) {
                    ?>
          <ul class="menuul">
          <?php
            
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                ?>
            <li>
              <?php
            // Must be inside a loop.
            $attachment_image = wp_get_attachment_url( get_post_thumbnail_id() );
            if ( has_post_thumbnail() ) {
            ?>
              <div class="menu-image-thumbnail me-2">
                <a data-fancybox="gallery" href="<?php echo $attachment_image;?>">
                  <?php
                the_post_thumbnail();
                ?>
                </a>
              </div>
              <?php
            }
            else {
            ?>
              <div class="menu-image-thumbnail">
                <a data-fancybox="gallery" href="<?php echo $attachment_image;?>"><img
                    src="<?php echo get_stylesheet_directory_uri();?>/images/logo.png" alt="" width="100" height="100"></a>
              </div>
              <?php
            }
            ?>
              <div class="menu-content">
                <h2><?php echo get_the_title();?></h2>
                <?php the_content();?>
              </div>
              <div class="menu-price">
                <?php echo get_field('dwkmenu_price');?>
              </div>
            </li>
            <?php };?>
          </ul>
          <?php
                } else
                {
                    // no posts found
                }

                wp_reset_postdata(); // reset global $post;

                ?>
        </div>
        <?php
            } 
            ?>
      </div>
      <!-- .tab_container -->
    </div>
  </div>
   <?php
   $output = ob_get_contents();  
   ob_get_clean();
   return $output;
};?>