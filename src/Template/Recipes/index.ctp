<?= $this->Html->css('recipe.css', ['block' => 'css']); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h4 class="caption">サラダ</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <ol>
                <?php foreach($saladRecipes as $recipe): ?>
                <li><?= h($recipe->detail); ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
    
     <div class="row">
        <div class="col-12">
            <h4 class="caption">スープ</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <ol>
                <?php foreach($soupRecipes as $recipe): ?>
                <li><?= h($recipe->detail); ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>   
    
     <div class="row">
        <div class="col-12">
            <h4 class="caption">おかず</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <ol>
                <?php foreach($sideDishRecipes as $recipe): ?>
                <li><?= h($recipe->detail); ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
    
    <?=$this->Form->create(null, ['type' => 'post']); ?>
    <?=$this->Form->end(); ?>
</div>

<?php
    $this->Html->scriptStart(['block' => true]);

    // 前ページURL
    $backUrl = $this->Url->build(['controller' => 'Materials', 'action' => 'index'], false);
    echo sprintf("const BACK_URL = '%s';", $backUrl);

    // 次ページURL
    $nextUrl = $this->Url->build(['controller' => 'Histories', 'action' => 'index'], false);
    echo sprintf("const NEXT_URL = '%s';", $nextUrl);

    // 履歴保存，選択料理削除URL
    $saveURL = $this->Url->build(['controller' => 'Recipes', 'action' => 'save']);
    echo sprintf("const SAVE_URL = '%s';", $saveURL);

    $this->Html->scriptEnd();
?>

<?= $this->Html->script('recipe', ['block' => 'script']); ?>