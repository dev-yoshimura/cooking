<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * 材料コントローラ
 */
class MaterialsController extends AppController
{
    /**
     * 初期処理
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('MenuManager');
    }

    /**
     * 材料画面
     */
    public function index()
    {
        $this->viewBuilder()->layout('cokking');
        $materials = $this->MenuManager->getMaterials($this->Auth->user('id'));
        $title = '材料';

        $this->set(compact('title', 'materials'));
    }
}
