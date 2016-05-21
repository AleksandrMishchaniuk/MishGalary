<style>
    .galleria{height: 300px;}
</style>

<div class="galleria">
    <?php foreach($galary['images'] as $image): ?>
        <img src="<?= $image; ?>"/>
    <?php endforeach; ?>
</div>