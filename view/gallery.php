<div class="mishGallery_wrap">
    <div class="mishGallery_title">
        <h3><?= $gallery->title ?></h3>
    </div>
    <div class="mishGallery_description">
        <p><?= $gallery->description ?></p>
    </div>
    <div class="mishGallery_images">
        <?php foreach($gallery->images_src as $src): ?>
            <img src="<?= $src; ?>"/>
        <?php endforeach; ?>
    </div>
</div>