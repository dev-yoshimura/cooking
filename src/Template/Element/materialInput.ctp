<div class="row">
    <div class="col-12 d-flex align-items-center mt-2" name="<?= h($name); ?>">
        <div class="col-1 text-right">
            <span><?= is_numeric($count)? $count + 1 : 1; ?></span>
        </div>
        <div class="col-8">
            <?= $this->Form->text($name.'.'.$count, ['class' => 'form-control','value' => $value, 'maxlength' => '255']); ?>
        </div>
        <div class="col-3  text-right">
            <button class="btn btn-primary">削除</button>
        </div>
    </div>
</div>


