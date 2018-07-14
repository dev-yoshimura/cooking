<?= $this->Html->css('loading.css',['block' => 'css']) ?>

<div id="loader-bg">
  <div id="loader">
    <?= $this->Html->image('loading.gif', ['width' => '80px', 'height' => '80px', 'alt' => 'Now Saving...']); ?>
    <p><?= h($text) ?></p>
  </div>
</div>

<?= $this->Html->script('loading', ['block' => 'script']); ?>