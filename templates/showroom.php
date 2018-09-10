<?php

use Netzstrategen\WooCommerceShowroom\Plugin;

$term_id = $term->term_id;
if (!$slides = get_field('woocommerce-showroom_rooms', 'product_cat_' . $term_id)) {
  return;
};

if (get_field('woocommerce-showroom_shuffle_slides', 'product_cat_' . $term_id)) {
  shuffle($slides);
}

$image_attr = Plugin::isLazyLoadActive() ? ' data-no-lazy="1"' : '';
?>

<div class="showroom gallerya gallerya--slider">
  <ul class="js-gallerya-slider js-gallerya-lightbox">
    <?php foreach ($slides as $slide): ?>
      <li class="showroom__item">
        <figure class="showroom__image">
          <img src="<?= $slide['image']['sizes']['large'] ?>" alt="<?= $slide['image']['alt'] ?>" title="<?= $slide['image']['title'] ?>"<?= $image_attr ?> />
          <?php if ($caption = $slide['description']): ?>
            <figcaption class="showroom-item-description"><?= $caption ?></figcaption>
          <?php endif; ?>
        </figure>
        <?php if ($products = $slide['products']): ?>
          <ul class="showroom__products product_list_widget">
            <?php foreach ($products as $product_id): ?>
              <?php $post = get_post($product_id); ?>
              <?php setup_postdata($post); ?>
              <?php wc_get_template('content-widget-product.php'); ?>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
