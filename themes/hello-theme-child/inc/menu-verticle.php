<section class="section-padding section-menu-inner elementor-section elementor-section-boxed">
  <div class="elementor-container">

    <div class="row">

      <div class="tab_container tab_container_verticle">
        <?php
              // Get list of all taxonomy terms  -- In simple categories title
              $args = array(
                          'taxonomy' => 'dwk_menu_cat',
                          'order'=> 'order'
                      );
              $cats = get_categories($args);
              $m=1;
              $i=1;

              // For every Terms of custom taxonomy get their posts by term_id
              foreach($cats as $cat) {
          ?>
        <h3 class="d_active tab_drawer_heading" rel="tab<?php echo $i++;?>"><?php echo $cat->name; ?></h3>
          <div id="tab<?php echo $m++;?>" class="tab_content <?php echo $cat->slug; ?>">
            <div class="row">
              <div class="col-lg-12">
                <div class="section-title">
                  <h2 class="color-red"><?php echo $cat->name; ?></h2>
                  
                  <h4 class="color-offblack"><?php echo $cat->category_description; ?></h4>
                </div>
              </div>
            </div>
          <?php
                // Query Arguments
                $args = array(
                    'post_type' => 'dwk_menu', // the post type
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'dwk_menu_cat', // the custom vocabulary
                            'field'    => 'term_id',          // term_id, slug or name  (Define by what you want to search the below term)    
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
            <a data-fancybox="gallery" href="<?php echo $attachment_image;?>"><img src="<?php echo get_stylesheet_directory_uri();?>/images/logo.png" alt="" width="100" height="100"></a>
            </div>
                    <?php
            }
            ?>
            <?php 
            $menu_items = get_field('menu_items');
            ?>
              <div class="menu-content <?php if( $menu_items == 'true') { echo "haschildren";}?>">
                <h2><?php the_title();?></h2>
                
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

        <?php } ?>
      </div>

      <ul class="tabs tabs_verticle">
        <?php
          // Get the taxonomy's terms
            $terms = get_terms(
            array(
                'taxonomy'   => 'dwk_menu_cat',
                'hide_empty' => false,
                'order'=> 'order'
            )
            );
            // Check if any term exists
            if ( ! empty( $terms ) && is_array( $terms ) ) {
            $j=1;
            // Run a loop and print them all
            foreach ( $terms as $term ) { ?>
                    <li rel="tab<?php echo $j++;?>"> <?php echo $term->name; ?></li><?php
            }
            } 
          ?>

          </ul>
    </div>
    <!-- .tab_container -->
  </div>
</section>
<div style="clear:both"></div>