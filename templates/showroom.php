<?php

use Netzstrategen\WooCommerceShowroom\Plugin;

$term_id = $term->term_id;
$image_attr = Plugin::isLazyLoadActive() ? ' data-no-lazy="1"' : '';
?>

<div class="showroom gallerya gallerya--slider">
  <ul class="js-gallerya-slider js-gallerya-lightbox">
    <?php while (have_rows('woocommerce-showroom_rooms', 'product_cat_' . $term_id)): the_row(); ?>
      <?php $image = get_sub_field('image'); ?>
      <li class="showroom__item">
        <figure class="gallerya__image">
          <img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>" title="<?= $image['title'] ?>"<?= $image_attr ?> />
          <?php if ($caption = get_sub_field('description')): ?>
            <figcaption class="showroom-item-description"><?= $caption ?></figcaption>
          <?php endif; ?>
        </figure>
        <?php if ($products = get_sub_field('products')): ?>
          <ul class="showroom__products">
            <?php foreach ($products as $product_id): ?>
              <?php $product = wc_get_product($product_id); ?>
              <li class="showroom__product">
                <div class="showroom__product-image">
                  <a href="<?= $product->get_permalink() ?>" title="<?= $product->get_title() ?>"><?= $product->get_image() ?></a>
                </div>
                <div class="showroom__products-details">
                  <a href="<?= $product->get_permalink() ?>" title="<?= $product->get_title() ?>"><?= $product->get_title() ?></a>
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
