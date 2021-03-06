
<div class="block-list">
    <input type="text" id="search" class="form-control" placeholder="<?php echo t('Search blocks'); ?>" aria-label="<?php echo t('Search blocks'); ?>" onkeyup="search_blocks();">
</div>
<?php echo $form->form_open(); ?>

<div class="row block-list">
    <?php foreach ($blocks as $key => $block): ?>

    <div class="col-md-4 block-item search_item">
        <h3 class="search_text"><?php echo $block[ 'title' ]; ?></h3>
        <label class="block-body" for="<?php echo "type_block-$key"; ?>">
            <?php echo $form->form_group("type_block_$key-group"); ?>

        </label>
    </div>
    <?php endforeach; ?>

</div>
<?php echo $form->form_input("token_$section"); ?>
<?php echo $form->form_input('submit'); ?>
<?php echo $form->form_close(); ?>