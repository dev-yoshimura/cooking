<?=$this->Html->css('condition.css', ['block' => 'css']); ?>

<div class="container-fluid">
    <?=$this->Form->create(null, ['type' => 'post', 
        'url' => ['controller' => 'Conditions', 'action' => 'save']]); ?>
        <!-- 余った材料 *********************************************************************** -->
        <div class="row">
            <div class="col-12">
                <h4 class="caption">余った材料</h4>
            </div>
        </div>

        <?php
            $count = 0;
            if (empty($surplusMaterials)) {
                echo $this->element('materialInput', ['name' => 'surplusMaterial', 'count' => $count, 'value' => '']);
            } else {
                foreach ($surplusMaterials as $surplusMaterial) {
                    echo $this->element('materialInput', ['name' => 'surplusMaterial', 'count' => $count, 'value' => $surplusMaterial->name]);
                    $count++;
                }
            }
        ?>
        
        <div class="row" id="addSurplusMaterial">
            <div class="col-12 d-flex mt-3">
                <div class="col-1"></div>
                <div class="col-8"></div>
                <div class="col-3 text-right">
                    <button class="btn btn-primary" id="btnAddSurplusMaterial">追加</button>
                </div>
            </div>
        </div>

        <!-- 使用しない材料 *********************************************************************** -->
        <div class="row">
            <div class="col-12">
                <h4 class="caption">使用しない材料</h4>
            </div>
        </div>
        <?php
            $count = 0;
            if (empty($notUseMaterials)) {
                echo $this->element('materialInput', ['name' => 'notUseMaterial', 'count' => $count, 'value' => '']);
            } else {
                foreach ($notUseMaterials as $notUseMaterial) {
                    echo $this->element('materialInput', ['name' => 'notUseMaterial', 'count' => $count, 'value' => $notUseMaterial->name]);
                    $count++;
                }
            }
        ?>

        <div class="row" id="addNotUseMaterial">
            <div class="col-12 d-flex mt-3">
                <div class="col-1"></div>
                <div class="col-8"></div>
                <div class="col-3 text-right">
                    <button class="btn btn-primary" id="btnAddNotUseMaterial">追加</button>
                </div>
            </div>
        </div>

        <!-- 前回の評価 *************************************************************************** -->
        <div class="row">
            <div class="col-12">
                <h4 class="caption">前回の評価</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12 d-flex align-items-center">
                <div class="col-5 mt-1">
                    <span>コスパ</span>
                </div>
                <div class="col-7 text-right">
                    <input type="button" class="star" id="cospa1" value="★">
                    <input type="button" class="star" id="cospa2" value="★">
                    <input type="button" class="star" id="cospa3" value="★">
                    <?= $this->Form->hidden('evaluationCospa', ['id' => 'evaluationCospa', 'value' => $evaluation['cospa']]); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 d-flex align-items-center">
                <div class="col-5 mt-1">
                    <span>作りやすさ</span>
                </div>
                <div class="col-7 text-right">
                    <input type="button" class="star" id="ease1" value="★">
                    <input type="button" class="star" id="ease2" value="★">
                    <input type="button" class="star" id="ease3" value="★">
                    <?= $this->Form->hidden('evaluationEase', ['id' => 'evaluationEase', 'value' => $evaluation['ease']]); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 d-flex align-items-center">
                <div class="col-5 mt-1">
                    <span>味</span>
                </div>
                <div class="col-7 text-right">
                    <input type="button" class="star" id="taste1" value="★">
                    <input type="button" class="star" id="taste2" value="★">
                    <input type="button" class="star" id="taste3" value="★">
                    <?= $this->Form->hidden('evaluationTaste', ['id' => 'evaluationTaste', 'value' => $evaluation['taste']]); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 d-flex mt-5">
                <div class="col-1"></div>
                <div class="col-8"></div>
                <div class="col-3 text-right">
                    <button class="btn btn-success" id="btnSave">保存</button>
                </div>
            </div>
        </div>
    <?=$this->Form->end(); ?>
</div>

<!-- ローディング -->
<?= $this->element('loading', ['text' => 'Now Saving...']); ?>

<!-- ダイアログ -->
<?= $this->element('confirmationModal', ['bodyText' => '保存しますか？']); ?>

<?php
    $this->Html->scriptStart(['block' => true]);

    // 前ページURL
    $backUrl = $this->Url->build(['controller' => 'Histories', 'action' => 'index'], false);
    echo sprintf("const BACK_URL = '%s';", $backUrl);

    // 次ページURL
    $nextUrl = $this->Url->build(['controller' => 'Selects', 'action' => 'index'], false);
    echo sprintf("const NEXT_URL = '%s';", $nextUrl);

    // 材料入力項目
    $materialInput = preg_replace('/(?:\n|\r|\r\n)/', '', $this->element('materialInput', ['name' => 'divName', 'count' => 'count', 'value' => '']));
    echo sprintf("const MATERIAL_INPUT = '%s';", $materialInput);

    $this->Html->scriptEnd();
?>

<?=$this->Html->script('condition', ['block' => 'script']); ?>


