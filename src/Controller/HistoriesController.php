<?php
namespace App\Controller;

use App\Controller\AppController;

class HistoriesController extends AppController
{
    // PaginatorHelper テンプレート
    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates'],
    ];

    /**
     * 初期処理
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('HistoryManager');
    }

    /**
     * 履歴画面
     */
    public function index()
    {
        $title = '作った料理一覧';

        // 履歴一覧取得定義取得
        $historyList = $this->HistoryManager->getDefinition($this->Auth->user('id'));

        // 検索条件を定義
        $this->paginate = $historyList['paginate'];

        // SQL定義
        $this->Histories = $historyList['histories'];

        $histories = $this->paginate($this->Histories);

        $this->set(compact('histories', 'title'));
    }

    /**
     * 評価保存
     */
    public function save()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {

            $userID = $this->Auth->user('id');
            $datas = $this->request->getParsedBody();

            if ($this->HistoryManager->updateEvaluation($userID, $datas)) {
                $this->Flash->success('更新しました');
            } else {
                $this->Flash->error('更新に失敗しました');
            }

            return $this->redirect(['controller' => 'Histories', 'action' => 'index']);
        }
    }
}
