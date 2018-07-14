<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;

/**
 * 料理選択コントローラ
 */
class SelectsController extends AppController
{
    /**
     * 初期処理
     */
    public function initialize()
    {
        parent::initialize();

        // Component読込み
        $this->loadComponent('MenuManager');
        $this->loadComponent('CsrfTokenChecker');

        // 認証を必要としないアクションのリストにselect, saveアクションを追加
        $this->Auth->allow(['select', 'save']);
    }

    /**
     *
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        if ($this->request->action === 'select' || $this->request->action === 'save') {
            // saveアクションの時は独自でチェックする。
            $this->getEventManager()->off($this->Csrf);
        }
    }

    /**
     * 料理選択画面
     */
    public function index()
    {
        $this->viewBuilder()->layout('cokking');
        $title = '料理を選ぶ';

        $selectMenu = $this->MenuManager->getSelectMenu($this->Auth->user('id'));
        $saladMenu = $selectMenu['saladMenu'];
        $soupMenu = $selectMenu['soupMenu'];
        $sideDishMenu = $selectMenu['sideDishMenu'];

        $this->set(compact('title', 'saladMenu', 'soupMenu', 'sideDishMenu'));
    }

    /**
     * 料理を選ぶ
     */
    public function select()
    {
        if ($this->request->is('ajax')) {

            $this->autoRender = false;

            $user = $this->Auth->user();
            $menu = null;
            $isLogin = false;
            $loginURL = Router::url(['controller' => 'Users', 'action' => 'login']);

            if ($user !== null) {
                $isLogin = true;
                if ($this->CsrfTokenChecker->check($this->getRequest(), $this->Csrf->_config) === true) {
                    $userID = $this->Auth->user('id');
                    $type = $this->request->data('type');
                    $isNew = (bool)$this->request->data('new');
                    $isOld = (bool)$this->request->data('old');
                    $menu = $this->MenuManager->selectMenu($userID, $type, $isNew, $isOld);
                }
            }

            $returnValue = [
                'menu' => $menu,
                'isLogin' => $isLogin,
                'loginURL' => $loginURL,
            ];

            $this->response->body(json_encode($returnValue));
        }
    }

    /**
     * 選択した料理を保存
     */
    public function save()
    {
        if ($this->request->is('ajax')) {

            $this->autoRender = false;

            $datas = $this->request->data;
            $user = $this->Auth->user();
            $isSuccess = false;
            $isLogin = false;
            $loginURL = Router::url(['controller' => 'Users', 'action' => 'login']);
            if ($user !== null) {
                $isLogin = true;
                if ($this->CsrfTokenChecker->check($this->getRequest(), $this->Csrf->_config) === true) {
                    $isSuccess = $this->MenuManager->saveSelectMenu($user['id'], $datas);
                }
            }

            $returnValue = [
                'isSuccess' => $isSuccess,
                'isLogin' => $isLogin,
                'loginURL' => $loginURL,
            ];

            $this->response->body(json_encode($returnValue));
        }
    }
}
