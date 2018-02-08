<?php

use Netzstrategen\WooCommerceShowroom\Plugin;

$image_attr = Plugin::isLazyLoadActive() ? ' data-no-lazy="1"' : '';
?>

<div class="showroom gallerya gallerya--slider">
  <ul class="js-gallerya-slider js-gallerya-lightbox">
    <?php while (have_rows('field_showroom_window', 'product_cat_' . $term_id)): the_row(); ?>
      <li class="showroom__item">
        <figure class="gallerya__image">
          <?php $image = get_sub_field('field_showroom_image'); ?>
          <img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>" title="<?= $image['title'] ?>" <?= $image_attr ?> />
          <?php if (!empty($caption = get_sub_field('field_showroom_description'))): ?>
            <figcaption class="showroom-item-description"><?= $caption ?></figcaption>
          <?php endif; ?>
        </figure>
        <?php
        $related_products = get_sub_field('field_showroom_related_products');
        if ($related_products) : ?>
          <ul class="showroom__related-products">
            <?php foreach ($related_products as $related_product_id): ?>
            <?php $product = wc_get_product($related_product_id); ?>
              <li class="showroom__related-product">
                <div class="showroom__related-product-image">
                  <a href="<?= $product->get_permalink() ?>" title="<?= $product->get_title() ?>">
                    <?= $product->get_image() ?>
                  </a>
                </div>
                <div class="showroom__related-products-details">
                  <a href="<?= $product->get_permalink() ?>" title="<?= $product->get_title() ?>">
                    <?= $product->get_title() ?>
                  </a>
                  <?= $product->get_price_html() ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </li>
    <?php endwhile; ?>
  </ul>
</div>
