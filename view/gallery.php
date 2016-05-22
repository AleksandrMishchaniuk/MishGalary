<style>
    .galleria{height: 300px;}
</style>

<div class="galleria">
    <?php foreach($gallery['images'] as $image): ?>
        <img src="<?= $image; ?>"/>
    <?php endforeach; ?>
</div>