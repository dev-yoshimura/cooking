<div class="modal fade" tabindex="-1" role="dialog" id="confirmationModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <span><?= h($bodyText); ?></span>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="btnYes"  data-dismiss="modal" style="width: 60px;">はい</button>
                <button class="btn btn-primary btn-sm" data-dismiss="modal">いいえ</button>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('confirmationModal', ['block' => 'script']); ?>