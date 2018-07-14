<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <span class="navbar-brand"><?= h($title); ?></span>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#Navber" aria-controls="Navber" aria-expanded="false" aria-label="レスポンシブ・ナビゲーションバー">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="Navber">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <?= $this->Html->link('Home', ['controller' => 'Histories', 'action' => 'index'], ['class' => 'nav-link']); ?>
            </li>
            <li class="nav-item">
                <?= $this->Html->link('Cooking', ['controller' => 'Conditions', 'action' => 'index'], ['class' => 'nav-link']); ?>
            </li>
            <li class="nav-item">
                <?= $this->Html->link('User', ['controller' => 'Users', 'action' => 'edit'], ['class' => 'nav-link']); ?>
            </li>
            <li class="nav-item">
                <?= $this->Html->link('Logout', ['controller' => 'Users', 'action' => 'logout'], ['class' => 'nav-link']); ?>
            </li>
        </ul>
    </div><!-- /.navbar-collapse -->
</nav>


