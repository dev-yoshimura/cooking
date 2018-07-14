<?=$this->Html->css('select.css', ['block' => 'css']); ?>

<div class="container-fluid">
    <?=$this->Form->create(null, ['type' => 'post']); ?>
        <?=$this->Form->hidden('type', ['id' => 'type']); ?>
        <div class="row">
            <div class="col-3">
                <span>条　件</span>
            </div>
        </div>
        <div class="row ml-3">
            <div class="col-12">
                <div class="checkbox">
                    <label><?=$this->Form->checkbox('new', ['id' => 'chkNew', 'checked' => true]) ?>作ったことがない料理を選ぶ</label>
                </div>
                <div class="checkbox">
                    <label><?=$this->Form->checkbox('old', ['id' => 'chkOld', 'checked' => true]) ?>作ったことがある料理を選ぶ</label>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-3">
                <span>サラダ</span>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-9 recipe__name">
                <p id="salad"><?= h(empty($saladMenu)? '': $saladMenu->name); ?></p>
                <?= $this->Form->hidden('saladMenuID', ['id' => 'saladMenuID', 'value' => empty($saladMenu)? '-1': $saladMenu->id]); ?>
            </div>
            <div class="col-2 text-right ml-2">
                <button class="btn btn-primary" id="btnSalad">選択</button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-3">
                <span>スープ</span>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-9 recipe__name">
                <p id="soup"><?= h(empty($soupMenu)? '': $soupMenu->name); ?></p>
                <?= $this->Form->hidden('soupMenuID', ['id' => 'soupMenuID', 'value' => empty($soupMenu)? '-1': $soupMenu->id]); ?>
            </div>
            <div class="col-2 text-right ml-2">
                <button class="btn btn-primary" id="btnSoup">選択</button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-3">
                <span>おかず</span>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-9 recipe__name">
                <p id="sideDish"><?= h(empty($sideDishMenu)? '': $sideDishMenu->name); ?></p>
                <?= $this->Form->hidden('sideDishMenuID', ['id' => 'sideDishMenuID', 'value' => empty($sideDishMenu)? '-1': $sideDishMenu->id]); ?>
            </div>
            <div class="col-2 text-right ml-2">
                <button class="btn btn-primary" id="btnSideDish">選択</button>
            </div>
        </div>
    <?=$this->Form->end(); ?>
</div>

<!-- ローディング -->
<?= $this->element('loading', ['text' => 'Now Selecting...']); ?>

<!-- ダイアログ -->
<?=$this->element('selectModal'); ?>

<?php
    $this->Html->scriptStart(['block' => true]);

    // 前ページURL
    $backUrl = $this->Url->build(['controller' => 'Conditions', 'action' => 'index'], false);
    echo sprintf("const BACK_URL = '%s';", $backUrl);

    // 次ページURL
    $nextUrl = $this->Url->build(['controller' => 'Materials', 'action' => 'index'], false);
    echo sprintf("const NEXT_URL = '%s';", $nextUrl);

    // 料理を選ぶURL
    $selectURL = $this->Url->build(['controller' => 'Selects', 'action' => 'select'], false);
    echo sprintf("const SELECT_URL = '%s';", $selectURL);

    // 選択した料理を保存するURL
    $save = $this->Url->build(['controller' => 'Selects', 'action' => 'save'], false);
    echo sprintf("const SAVE_URL = '%s';", $save);

    $this->Html->scriptEnd();
?>

<?=$this->Html->script('select', ['block' => 'script']); ?>