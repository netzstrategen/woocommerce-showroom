<?php

use Netzstrategen\WooCommerceShowroom\Plugin;

global $post;

$term_id = $term->term_id;
$image_attr = Plugin::isLazyLoadActive() ? ' data-no-lazy="1"' : '';
?>

<div class="showroom gallerya gallerya--slider">
  <ul class="js-gallerya-slider js-gallerya-lightbox">
    <?php while (have_rows('woocommerce-showroom_rooms', 'product_cat_' . $term_id)): the_row(); ?>
      <?php $image = get_sub_field('image'); ?>
      <li class="showroom__item">
        <figure class="showroom__image">
          <img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>" title="<?= $image['title'] ?>"<?= $image_attr ?> />
          <?php if ($caption = get_sub_field('description')): ?>
            <figcaption class="showroom-item-description"><?= $caption ?></figcaption>
          <?php endif; ?>
        </figure>
        <?php if ($products = get_sub_field('products')): ?>
          <ul class="showroom__products product_list_widget">
            <?php foreach ($products as $product_id): ?>
              <?php $post = get_post($product_id); ?>
              <?php setup_postdata($post); ?>
              <?php wc_get_template('content-widget-product.php'); ?>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </li>
    <?php endwhile; ?>
  </ul>
</div>
