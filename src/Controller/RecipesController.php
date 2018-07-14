<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;

/**
 * 作り方コントローラ
 */
class RecipesController extends AppController
{
    /**
     * 初期処理
     */
    public function initialize()
    {
        parent::initialize();

        // Component読込み
        $this->loadComponent('HistoryManager');
        $this->loadComponent('MenuManager');
        $this->loadComponent('CsrfTokenChecker');

        // 認証を必要としないアクションのリストにsaveアクションを追加
        $this->Auth->allow(['save']);
    }

    /**
     *
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        if ($this->request->action === 'save') {
            // saveアクションの時は独自でチェックする。
            $this->getEventManager()->off($this->Csrf);
        }
    }
    
    /**
     * 作り方画面
     */
    public function index()
    { 
        $this->viewBuilder()->layout('cokking');
        $recipes = $this->MenuManager->getRecipes($this->Auth->user('id'));

        $title = '作り方';
        $saladRecipes = $recipes['saladRecipes'];
        $soupRecipes = $recipes['soupRecipes'];
        $sideDishRecipes = $recipes['sideDishRecipes'];

        $this->set(compact('title', 'saladRecipes', 'soupRecipes', 'sideDishRecipes'));
    }

    /**
     * 履歴保存
     */
    public function save()
    {
        if ($this->request->is('ajax')) {

            $this->autoRender = false;

            $user = $this->Auth->user();
            $isSuccess = false;
            $isLogin = false;
            $loginURL = Router::url(['controller' => 'Users', 'action' => 'login']);
            if ($user !== null) {
                $isLogin = true;
                if ($this->CsrfTokenChecker->check($this->getRequest(), $this->Csrf->_config) === true) {
                    $isSuccess = $this->HistoryManager->save($user['id']);
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
