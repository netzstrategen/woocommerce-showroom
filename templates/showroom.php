<?php

use Netzstrategen\WooCommerceShowroom\Plugin;

$image_attr = Plugin::isLazyLoadActive() ? ' data-no-lazy="1"' : '';
?>

<div class="showroom gallerya gallerya--slider">
  <ul class="js-gallerya-slider js-gallerya-lightbox clearfix">
    <?php while (have_rows('field_showroom_window', 'product_cat_' . $term_id)): the_row(); ?>
      <li class="showroom-item clearfix">
        <figure class="gallerya__image">
          <?php $image = get_sub_field('field_showroom_image'); ?>
          <img class="showroom-item-image" src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>" title="<?= $image['title'] ?>" <?= $image_attr ?> />
          <?php if (!empty($caption = get_sub_field('field_showroom_description'))): ?>
            <figcaption class="showroom-item-description"><?= $caption ?></figcaption>
          <?php endif; ?>
        </figure>
        <?php
        $related_products = get_sub_field('field_showroom_related_products');
        if ($related_products) : ?>
          <div class="showroom-related-products">
            <ul>
              <?php foreach ($related_products as $related_product_id): ?>
              <li class="showroom-related-product">
                <div class="showroom-related-product-image">
                  <?php $product = wc_get_product($related_product_id); ?>
                  <a href="<?= $product->get_permalink() ?>" title="<?= $product->get_title(); ?>">
                    <?= $product->get_image() ?>
                  </a>
                </div>
                <div class="showroom-related-product-details">
                  <a href="<?= $product->get_permalink() ?>" title="<?= $product->get_title(); ?>">
                    <?= $product->get_title(); ?>
                  </a>
                  <?php echo $product->get_price_html(); ?>
                </div>
                <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </li>
    <?php endwhile; ?>
  </ul>
</div>
