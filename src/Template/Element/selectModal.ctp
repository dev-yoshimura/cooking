<?= $this->Html->css('select-modal.css', ['block' => 'css']) ?>

<div class="modal" tabindex="-1" role="dialog" id="selectModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="name"></h5>
            </div>
            
            <div class="modal-body text-center">
                <img src="" class="selectModal__img" id="image">
            </div>
            
            <div class="modal-footer selectModal__button">
                <button class="btn btn-primary btn-sm" data-dismiss="modal" id="btnOK">OK</button>
                <button class="btn btn-primary btn-sm" id="btnAgain">もう一度選ぶ</button>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('selectModal', ['block' => 'script']); ?>