<?= $this->Html->css('material.css', ['block' => 'css']); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr class="recipe__header">
                        <th></th>
                        <th></th>
                        <th>材料</th>
                        <th>分量</th>
                    </tr>
                </thead>
                <?php foreach ($materials as $material): ?>
                <tr>
                    <td class="recipe__img">
                        <?= $this->Html->image('icoon-mono.png', ['style' => 'display:none']); ?>
                    </td>
                    <td class="recipe__checkbox">
                        <input type="checkbox">
                    </td>
                    <td>
                        <?= h($material->name); ?>
                    </td>
                    <td>
                        <?= h($material->quantity); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

<?php 
    $this->Html->scriptStart(['block' => true]);
    
    // 前ページURL
    $backUrl = $this->Url->build(['controller' => 'Selects', 'action' => 'index'], false);
    echo sprintf("const BACK_URL = '%s';", $backUrl);
    
    // 次ページURL
    $nextUrl = $this->Url->build(['controller' => 'Recipes', 'action' => 'index'], false);
    echo sprintf("const NEXT_URL = '%s';", $nextUrl);
    
    $this->Html->scriptEnd();
?>

<?= $this->Html->script('material', ['block' => 'script']); ?>