<?php

use Netzstrategen\WooCommerceShowroom\Plugin;

$image_attr = Plugin::isLazyLoadActive() ? ' data-no-lazy="1"' : '';
?>

<section class="section section--first">
  <div class="showroom container container--flex gallerya gallerya--slider">
    <ul class="js-gallerya-slider js-gallerya-lightbox">
      <?php while (have_rows('field_showroom_window', 'product_cat_' . $term_id)): the_row(); ?>
        <li>
          <figure class="gallerya__image">
            <?php $image = get_sub_field('field_showroom_image'); ?>
            <img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>" title="<?= $image['title'] ?>" <?= $image_attr ?> />
            <?php if (!empty($caption = get_sub_field('field_showroom_description'))): ?>
              <figcaption class="gallerya__image__caption"><?= $caption ?></figcaption>
            <?php endif; ?>
          </figure>
          <?php
          $related_products = get_sub_field('field_showroom_related_products');
          if ($related_products) : ?>
            <div class="related-products">
              <?php foreach ($related_products as $related_product_id): ?>
                <?php $product = wc_get_product($related_product_id); ?>
                <a href="<?= $product->get_permalink() ?>" title="<?= $product->get_title(); ?>">
                  <div class="image">
                    <?= $product->get_image() ?>
                  </div>
                  <div class="product-name">
                    <?= $product->get_title(); ?>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </li>
      <?php endwhile; ?>
    </ul>
  </div>
</section>
