<?=$this->Html->css('histories.css', ['block' => 'css']); ?>

<?= $this->element('nav'); ?>
<?= $this->Flash->render(); ?>

<div class="m-2">
    <table class="table table-sm history__tabel">
        <thead class="thead-light"> 
            <tr>
                <th>料理名</th>
                <th><?= $this->Paginator->sort('A.cospa', 'コ'); ?></th>
                <th><?= $this->Paginator->sort('A.ease', '作'); ?></th>
                <th><?= $this->Paginator->sort('A.taste', '味'); ?></th>
                <th><?= $this->Paginator->sort('A.modified', '料理日'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($histories as $history): ?>
                <tr>
                    <td>
                        <?= h($history->name); ?>
                        <?= $this->Form->hidden('id',['value' => $history->id]); ?>
                    </td>
                    <td>
                        <a href="" name='evaluation'><?= $history->cospa ?></a>
                    </td>
                    <td>
                        <a href="" name='evaluation'><?= $history->ease ?></a>
                    </td>
                    <td>
                        <a href="" name='evaluation'><?= $history->taste ?></a>
                    </td>
                    <td>
                        <?= $history->modified->year ?><br><?= $history->modified->format('m/d') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody> 
    </table>

    <nav aria-label="ページ送り">
        <ul class="pagination  justify-content-center">
            <?=$this->Paginator->numbers(); ?>
        </ul>
    </nav>
<div>

<!-- ダイアログ -->
<?=$this->element('evaluationModal'); ?>

<?=$this->Html->script('history', ['block' => 'script']); ?>
