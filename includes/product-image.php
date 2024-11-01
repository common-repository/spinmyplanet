<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product, $woocommerce;
$version = '3.0';
?>


<div class="images">
    
	<?php
		if ( has_post_thumbnail() ) 
		{
			if( version_compare( $woocommerce->version, $version, ">=" ) ) {
				$attachment_ids = $product->get_gallery_image_ids();
			}else{
				$attachment_ids = $product->get_gallery_attachment_ids();
			}
			
			$attachment_count = count( $attachment_ids);
			$props            = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );

			$test = "";
            $test1 = array();
            $image_src = array();
            $i = 0;
			foreach( $attachment_ids as $attachment_id ) {
			   $imgfull_src = wp_get_attachment_image_src( $attachment_id,'full');
			   $image_src   = wp_get_attachment_image_src( $attachment_id,'shop_single');
			   $test1[$i] = $image_src[0];
			   $i++;
            }
                      
            $images = implode(",", $test1);
            if($attachment_count==1){
                echo '<img src="'.$image_src[0].'" />';

            }
            else{
               $test .= '<div class="spin-container" style="position: relative;">
                            <article class="spin state-loading" data-spin-image-urls="'.$images.'">
                                <div class="spin-area">
                                    <img class="img-responsive spin-my-planet spin-placeholder-image" src="'.$image_src[0].'  " />';
                                    if($attachment_count > 1){
                                        $test .= '<div class="spin-overlay" style="position: absolute; height: 99%; width: 100%; top:0; left :0; background: rgba(0, 0, 0, 0.3);">
                                                       <span class="drag">drag to</span><span class="spin-drag"><span><</span>Spin<span>></span></span>					
                                                  </div>';
                                                  }
                                    $test .= '</div>
                                            </article>
                                        </div>';
			
                          
            }

			echo apply_filters(
				'woocommerce_single_product_image_html',
				$test,
				$post->ID
			);
		} else {
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
		}

		do_action( 'woocommerce_product_thumbnails' );
                
	?>
</div>





