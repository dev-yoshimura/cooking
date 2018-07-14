<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>レシピ</title>
    
    <?= $this->Html->meta('icon') ?>
    
    <?= $this->Html->css('bootstrap.min.css'); ?>
    <?= $this->Html->css('flash.css'); ?>
    <?= $this->Html->css('common.css'); ?>

    <?= $this->fetch('css'); ?>  
</head>
<body>
    
    <div class="d-flex justify-content-between pt-2 fixed-top bg-dark">
        <div>
            <button class="btn btn-dark" id="btnBack">前へ</button>
        </div>
        <div>
            <h3 class="text-light"><?= h($title); ?></h3>
        </div>
        <div>
            <button class="btn btn-dark" id="btnNext">次へ</button>
        </div>
    </div>
    <div style="height:49px"></div>
    <?=$this->Flash->render(); ?>
    <div class="mt-3">
    
    <?= $this->fetch('content'); ?>

    <?php 
        $this->Html->scriptStart(['block' => true]);

        // ErrorURL
        $errorUrl = $this->Url->build(['controller' => 'error', 'action' => 'error'], false);
        echo sprintf("const ERROR_URL = '%s';", $errorUrl);

        $this->Html->scriptEnd();
    ?>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <?= $this->Html->script('bootstrap.min'); ?>
    <?= $this->Html->script('common'); ?>
    <?= $this->fetch('script'); ?>
</body>
</html>