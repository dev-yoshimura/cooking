<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * 履歴管理コンポーネント
 */
class HistoryManagerComponent extends Component
{
    private $__selectMenus;
    private $__histories;

    /**
     * 初期化処理
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->__selectMenus = TableRegistry::get('select_menus');
        $this->__histories = TableRegistry::get('histories');
    }

    /**
     * 履歴一覧取得定義取得
     * @param ログインユーザーID
     * @return list(検索条件, SQL)
     */
    public function getDefinition($userID)
    {
        // 検索条件を定義
        $paginate = [
            // 取得項目
             'fields' => [
                'id' => 'A.id',
                'name' => 'B.name',
                'cospa' => 'A.cospa',
                'ease' => 'A.ease',
                'taste' => 'A.taste',
                'modified' => 'A.modified',
            ],
            // 並び順
             'order' => [
                'A.modified' => 'DESC',
            ],
            // 並び替えに使用するフィールド
             'sortWhitelist' => [
                'A.cospa', 'A.ease', 'A.taste', 'A.modified',
            ],
            // ページごとに行数の最大値
             'maxLimit' => '10',
        ];

        // SQL定義
        $histories = $this->__histories
            ->setAlias('A')
            ->find()
            ->join([
                'table' => 'menus',
                'alias' => 'B',
                'type' => 'INNER',
                'conditions' => 'A.menu_ID = B.id',
            ])
            ->where([
                'A.user_id' => $userID,
            ]);

        return [
            'paginate' => $paginate,
            'histories' => $histories,
        ];
    }

    /**
     * 保存
     * @param ログインユーザーID
     * @return 成功有無
     */
    public function save($userID)
    {
        
        $isError = false;
        try {
            // 選択した料理を取得
            $selectMenu = $this->__selectMenus
                ->find()
                ->select(['id', 'salad', 'soup', 'side_dish'])
                ->where(['user_id' => $userID])
                ->first();

            if ($selectMenu === null) {
                return true;
            }

            $connection = ConnectionManager::get('default');
            $connection->begin();
            // 保存データ
            $saveDatas['modifier'] = $userID;

            // サラダ保存
            if ($selectMenu['salad'] !== -1) {

                $entity = $this->__getHistory($userID, $selectMenu['salad']);
                if (empty($entity)) {
                    $saveDatas['user_id'] = $userID;
                    $saveDatas['cospa'] = 0;
                    $saveDatas['ease'] = 0;
                    $saveDatas['taste'] = 0;
                    $saveDatas['creator'] = $userID;
                    $entity = $this->__histories->newEntity();
                }
                $saveDatas['menu_ID'] = $selectMenu['salad'];
                if ($this->__histories->save($this->__histories->patchEntity($entity, $saveDatas)) === null) {
                    $isError = true;
                }
            }

            // スープ保存
            if ($selectMenu['soup'] !== -1 && $isError === false) {

                $entity = $this->__getHistory($userID, $selectMenu['soup']);
                if (empty($entity)) {
                    $saveDatas['user_id'] = $userID;
                    $saveDatas['cospa'] = 0;
                    $saveDatas['ease'] = 0;
                    $saveDatas['taste'] = 0;
                    $saveDatas['creator'] = $userID;
                    $entity = $this->__histories->newEntity();
                }
                $saveDatas['menu_ID'] = $selectMenu['soup'];
                if ($this->__histories->save($this->__histories->patchEntity($entity, $saveDatas)) === null) {
                    $isError = true;
                }
            }

            // おかず保存
            if ($selectMenu['side_dish'] !== -1 && $isError === false) {

                $entity = $this->__getHistory($userID, $selectMenu['side_dish']);
                if (empty($entity)) {
                    $saveDatas['user_id'] = $userID;
                    $saveDatas['cospa'] = 0;
                    $saveDatas['ease'] = 0;
                    $saveDatas['taste'] = 0;
                    $saveDatas['creator'] = $userID;
                    $entity = $this->__histories->newEntity();
                }
                $saveDatas['menu_ID'] = $selectMenu['side_dish'];
                if ($this->__histories->save($this->__histories->patchEntity($entity, $saveDatas)) === null) {
                    $isError = true;
                }
            }

            // 選択した料理を削除
            if ($isError === false) {
                $this->__selectMenus->delete($selectMenu);
            }

            if ($isError === false) {
                $connection->commit();
            } else {
                $connection->rollback();
            }

            return !$isError;
        } catch (Exception $ex) {

            Log::write('debug', $ex);
            $connection->rollback();
            return false;
        }
    }

    /**
     * 評価更新
     * ※例外処理は呼び出し元で処理をする
     * @param ログインユーザーID
     * @param postデータ
     * @return 成功有無
     */
    public function updateEvaluation($userID, $datas)
    {
        $history = $this->__histories->get($datas['id']);
        $saveDatas['cospa'] = $datas['evaluationCospa'];
        $saveDatas['ease'] = $datas['evaluationEase'];
        $saveDatas['taste'] = $datas['evaluationTaste'];
        $saveData['modifier'] = $userID;
        $entity = $this->__histories->patchEntity($history, $saveDatas);

        // 更新日付を更新しない
        $history->dirty('modified', true);

        if ($this->__histories->save($entity)) {
            return true;
        }

        return false;
    }

    /**
     * 履歴取得
     * @param ログインユーザーID
     * @param メニューID
     */
    private function __getHistory($userID, $menuID)
    {
        $history = $this->__histories
            ->find()
            ->select([
                'id',
            ])
            ->where([
                'user_id' => $userID,
                'menu_ID' => $menuID,
            ])
            ->first();

        return $history;
    }
}
