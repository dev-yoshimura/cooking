<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.3.4
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Event\Event;

/**
 * Error Handling Controller
 *
 * Controller used by ExceptionRenderer to render error responses.
 */
class ErrorController extends AppController
{
    /**
     * 初期処理
     */
    public function initialize()
    {
        parent::initialize();

        // 認証を必要としないアクションのリストにerrorアクションを追加
        $this->Auth->allow(['error']);
    }

    /**
     * 描画前処理
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        // エラーの共通レイアウト名を指定
        $this->viewBuilder()->layout('error');

        //Templateファイルのあるパスを指定(src/Template/Error/)
        $this->viewBuilder()->templatePath('Error');

        // セッションの破棄
        $this->request->session()->destroy();

        // logアウト
        $this->Auth->logout();
    }

    /**
     * エラー画面
     */
    public function error()
    {

    }
}
