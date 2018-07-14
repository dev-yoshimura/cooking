<!--レイアウト側で$this->fetch('css')した場所にcss設定-->
<?= $this->Html->css('login.css', ['block' => true]); ?>

<?= $this->Flash->render(); ?>

<div class="main">
  <?= $this->Form->create(null,['type'  => 'post', 
                                'url'   => ['controller' => 'users', 'action' => 'login'],
                                'class' => ['form-signin']
                          ]); ?>
      <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">ログイン</h1>
      </div>

      <div class="form-label-group">
        <input type="email" id="email" name ="email" class="form-control" placeholder="Emailアドレス" required autofocus>
        <label for="email">Emailアドレス</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="password" name="password" class="form-control" placeholder="パスワード" required>
        <label for="password">パスワード</label>
      </div>

      <button class="btn btn-lg btn-primary btn-block" type="submit">ログイン</button>
  <?= $this->Form->end(); ?>
</div>