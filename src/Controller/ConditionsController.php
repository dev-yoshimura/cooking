<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * 料理を選ぶ条件コントローラ
 */
class ConditionsController extends AppController
{
    /**
     * 初期処理
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('ConditionManager');
    }

    /**
     * 料理を選ぶ条件画面
     */
    public function index()
    {
        $this->viewBuilder()->layout('cokking');
        $title = '料理を選ぶ条件';

        // 条件取得
        $condition = $this->ConditionManager->getCondition($this->Auth->user('id'));
        // 余った材料
        $surplusMaterials = $condition['surplusMaterials'];
        // 使用しない材料
        $notUseMaterials = $condition['notUseMaterials'];
        // 評価
        $evaluation = $condition['evaluation'];

        $this->set(compact('title', 'surplusMaterials', 'notUseMaterials', 'evaluation'));
    }

    /**
     * 条件保存
     */
    public function save()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {

            $userID = $this->Auth->user('id');
            $datas = $this->request->getParsedBody();
            if ($this->ConditionManager->save($userID, $datas)) {
                $this->Flash->success('更新しました');
            } else {
                $this->Flash->error('更新に失敗しました');
            }

            return $this->redirect(['controller' => 'Conditions', 'action' => 'index']);
        }
    }

}
