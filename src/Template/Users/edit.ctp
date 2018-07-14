<?= $this->Html->css('user.css', ['block' => 'css']); ?>

<?= $this->element('nav'); ?>
<?= $this->Flash->render(); ?>

<?=$this->Form->create($user, ['type' => 'post',
    'url' => ['controller' => 'users', 'action' => 'edit']
]);?>
    <?= $this->Form->hidden('modified'); ?>
    <div class="mt-1">
        <div class="form-group col-12">
            <label>名前</label>
            <?= $this->Form->text('name', ['class' => 'form-control', 'maxlength' => '255']); ?>
            <?= $this->Form->error('name'); ?>
        </div>
    </div>
    <div>
        <div class="form-group col-12">
            <label>メールアドレス</label>
            <?= $this->Form->email('email', ['class' => 'form-control', 'maxlength' => '255']); ?>
            <?= $this->Form->error('email'); ?>
        </div>
    </div>
    <div>
        <div class="form-group col-12">
            <label>パスワード</label>
            <?= $this->Form->password('password', ['class' => 'form-control', 'maxlength' => '255']); ?>
            <?= $this->Form->error('password'); ?>
        </div>
    </div>
    <div>
        <div class="form-group col-12">
            <label>パスワードの確認入力</label>
            <?= $this->Form->password('password2', ['class' => 'form-control', 'maxlength' => '255']); ?>
        </div>
    </div>
<?= $this->Form->end(); ?>

<div>
    <div class="col-12 text-right">
        <button id="btnSave"  class="btn btn-success btn-lg">更新</button>
    </div>
</div>

<!-- ローディング -->
<?= $this->element('loading', ['text' => 'Now Saving...']); ?>

<!-- ダイアログ -->
<?= $this->element('confirmationModal', ['bodyText' => '更新しますか？']); ?>

<?php
    $this->Html->scriptStart(['block' => true]);

    // 入力チェックURL
    $url = $this->Url->build(['controller' => 'Users', 'action' => 'validate'], false);
    echo sprintf("const VALIDATE_URL = '%s';", $url);

    $this->Html->scriptEnd();
?>

<?= $this->Html->script('user', ['block' => 'script']); ?>