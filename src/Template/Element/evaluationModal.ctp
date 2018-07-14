<?= $this->Html->css('evaluation-modal.css', ['block' => 'css']) ?>

<div class="modal fade" id="evaluationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">評価</h4>
            </div>

            <div class="modal-body">
                <?=$this->Form->create(null, ['type' => 'post',
                'url' => ['controller' => 'Histories', 'action' => 'save']]); ?>
                <?= $this->Form->hidden('id',['id' => 'id']); ?>
                <div class="evaluation">
                    <div>
                        <span class="evaluation__cospa">コスパ</span>
                    </div>
                    <div>
                        <input type="button" class="star" id="cospa1" value="★">
                        <input type="button" class="star" id="cospa2" value="★">
                        <input type="button" class="star" id="cospa3" value="★">
                        <?= $this->Form->hidden('evaluationCospa', ['id' => 'evaluationCospa']); ?>
                    </div>
                </div>

                <div class="evaluation">
                    <div>
                        <span class="evaluation__ease">作りやすさ</span>
                    </div>
                    <div>
                        <input type="button" class="star" id="ease1" value="★">
                        <input type="button" class="star" id="ease2" value="★">
                        <input type="button" class="star" id="ease3" value="★">
                        <?= $this->Form->hidden('evaluationEase', ['id' => 'evaluationEase']); ?>
                    </div>
                </div>

                <div class="evaluation">
                    <div>
                        <span class="evaluation__taste">味</span>
                    </div>
                    <div>
                        <input type="button" class="star" id="taste1" value="★">
                        <input type="button" class="star" id="taste2" value="★">
                        <input type="button" class="star" id="taste3" value="★">
                        <?= $this->Form->hidden('evaluationTaste', ['id' => 'evaluationTaste']); ?>
                    </div>
                </div>
                <?=$this->Form->end(); ?>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm btn-ok" id="btnOK">OK</button>
                <button class="btn btn-primary btn-sm" data-dismiss="modal" id="btnCancel">キャンセル</button>
            </div>
        </div>
    </div>
</div>

<!-- ローディング -->
<?= $this->element('loading', ['text' => 'Now Saving...']); ?>

<?= $this->Html->script('evaluationModal', ['block' => 'script']); ?>