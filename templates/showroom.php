<ul>
  <?php while (have_rows('field_showroom_window', 'product_cat_' . $term_id)): the_row(); ?>
  <li>
    <div class="showroom">
      <div class="scene">
        <div class="image">
          <?php $image = get_sub_field('field_showroom_image'); ?>
          <img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>" title="<?= $image['title'] ?>" />
        </div>
        <div class="description">
          <?= get_sub_field('field_showroom_description'); ?>
        </div>
      </div>
      <div class="related-products">
        <?php
        $related_products = get_sub_field('field_showroom_related_products');
        if ($related_products) :
          foreach ($related_products as $related_product_id):
        ?>
          <?php $product = wc_get_product($related_product_id); ?>
          <a href="<?= $product->get_permalink() ?>" title="<?= $product->get_title(); ?>">
            <div class="image">
              <?= $product->get_image() ?>
            </div>
            <div class="product-name">
              <?= $product->get_title(); ?>
            </div>
          </a>
        <?php
          endforeach;
        endif;
        ?>
      </div>
    </div>
  <li>
  <?php endwhile; ?>
</ul>
