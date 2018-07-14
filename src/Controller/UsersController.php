<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Exception;
use Cake\Event\Event;
use Cake\Routing\Router;

/**
 * ユーザーコントローラー
 */
class UsersController extends AppController
{
    /**
     * 初期化
     */
    public function initialize()
    {
        parent::initialize();

        // Component読込み
        $this->loadComponent('MenuManager');
        $this->loadComponent('CsrfTokenChecker');

        // 認証を必要としないアクションのリストにlogout, validateアクションを追加
        $this->Auth->allow(['logout', 'validate']);
    }

    /**
     *
     */
    public function beforeFilter(Event $event)
    {

        parent::beforeFilter($event);

        if ($this->request->action === 'validate') {
            // saveアクションの時は独自でチェックする。
            $this->getEventManager()->off($this->Csrf);
        }
    }

    /**
     * ログイン
     */
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                $selectMenu = $this->MenuManager->getSelectMenu($this->Auth->user('id'));

                if ($selectMenu['saladMenu'] === null
                    && $selectMenu['soupMenu'] === null
                    && $selectMenu['sideDishMenu'] === null) {
                    return $this->redirect($this->Auth->redirectUrl());
                } else {
                    return $this->redirect(['controller' => 'Materials', 'action' => 'index']);
                }
            }
            $this->Flash->error('emailまたはpasswordが不正です。');
        }
    }

    /**
     * ログアウト
     */
    public function logout()
    {
        // セッションの破棄
        $this->request->session()->destroy();
        return $this->redirect($this->Auth->logout());
    }

    /**
     * ユーザー情報
     */
    public function edit()
    {
        $title = 'ユーザ情報';
        $id = $this->Auth->user('id');
        $user = $this->Users
            ->find()
            ->where(['id' => $id])
            ->select(['id', 'name', 'email', 'modified'])
            ->first();

        $user['modified'] = $user['modified']->i18nFormat('YYYY/MM/dd HH:mm:ss');

        if ($this->request->is(['patch', 'post', 'put'])) {

            // 排他チェック
            $beforeModified = new Time($user['modified']);
            $afterModified = new Time($this->request->getData('modified'));
            if ($beforeModified->i18nFormat('yyyyMMddHHmmss') === $afterModified->i18nFormat('yyyyMMddHHmmss')) {
                $user = $this->Users->patchEntity($user, $this->request->getData());
                if ($this->Users->save($user)) {
                    $this->Flash->success('更新しました');

                    return $this->redirect(['controller' => 'Histories', 'action' => 'index']);
                } else {
                    $this->Flash->error('更新に失敗しました。');
                }
            } else {
                $this->Flash->error('誰かが更新しました。');
                return $this->redirect(['controller' => 'Histories', 'action' => 'index']);
            }
        }

        $this->set(compact('user', 'title'));
    }

    /**
     * 入力値チェック
     */
    public function validate()
    {
        if ($this->request->is('ajax')) {

            $this->autoRender = false;

            try {

                $user = $this->Auth->user();
                $isSuccess = false;
                $isLogin = false;
                $loginURL = Router::url(['controller' => 'Users', 'action' => 'login']);
                $errors = null;
                if ($user !== null) {

                    $isLogin = true;
                    if ($this->CsrfTokenChecker->check($this->getRequest(), $this->Csrf->_config) === true) {

                        $saveData['name'] = $this->request->getData('name');
                        $saveData['email'] = $this->request->getData('email');
                        $saveData['password'] = $this->request->getData('password');
                        $saveData['auth'] = 1;
                        $saveData['creator'] = $this->Auth->user('id');
                        $saveData['modifier'] = $this->Auth->user('id');
                        $user = $this->Users->newEntity($saveData);

                        $errors = $user->getErrors();
                        if ($this->request->getData('password') !== $this->request->getData('password2')) {
                            $errors['password']['mismatch'] = 'パスワードとパスワードの確認が一致しません。';
                        }
                        $isSuccess = true;
                    }
                }
            } catch (Exception $ex) {
                $this->log($ex);
                $isSuccess = false;
            }

            $returnValue = [
                'errors' => $errors,
                'isSuccess' => $isSuccess,
                'isLogin' => $isLogin,
                'loginURL' => $loginURL,
            ];
            $this->response->body(json_encode($returnValue));
        }
    }
}
