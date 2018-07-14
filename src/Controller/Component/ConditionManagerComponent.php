<?php

namespace App\Controller\Component;

use App\Model\Table\ConditionMaterialsTable;
use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * 料理を選ぶ条件管理コンポーネント
 */
class ConditionManagerComponent extends Component
{
    public $components = ['Convert'];
    private $__conditionMaterials;
    private $__conditionEvaluations;

    /**
     * 初期化処理
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->__conditionMaterials = TableRegistry::get('condition_materials');
        $this->__conditionEvaluations = TableRegistry::get('condition_evaluations');
    }

    /**
     * 条件取得
     * @param ログインユーザーID
     * @return 料理を選ぶ条件
     */
    public function getCondition($userID)
    {
        // 余った材料取得
        $surplusMaterials = $this->__conditionMaterials
            ->find()
            ->where([
                'type' => ConditionMaterialsTable::SURPLUS_TYPE,
                'user_id' => $userID,
            ])
            ->toList();

        // 使用しない材料取得
        $notUseMaterials = $this->__conditionMaterials
            ->find()
            ->where([
                'type' => ConditionMaterialsTable::NOT_USE_TYPE,
                'user_id' => $userID,
            ])
            ->toList();

        // 評価取得
        $evaluation = $this->__conditionEvaluations
            ->find()
            ->where([
                'user_id' => $userID,
            ])
            ->first();

        return [
            'surplusMaterials' => $surplusMaterials,
            'notUseMaterials' => $notUseMaterials,
            'evaluation' => $evaluation,
        ];
    }

    /**
     * 保存
     * @param ログインユーザーID
     * @param postデータ
     * @return bool 成功有無
     */
    public function save($userID, $datas)
    {
        $connection = ConnectionManager::get('default');
        $connection->begin();
        try {
            $isSuccess = false;
            if ($this->__saveConditionMaterials($userID, $datas)) {
                if ($this->__saveConditionEvaluations($userID, $datas)) {
                    $isSuccess = true;
                }
            }

            if ($isSuccess === true) {
                $connection->commit();
            } else {
                $connection->rollback();
            }

            return $isSuccess;
        } catch (Exception $ex) {

            Log::write('debug', $ex);
            $connection->rollback();
            throw $ex;
        }
    }

    /**
     * 条件_材料を保存
     * @param ログインユーザーID
     * @param postデータ
     * @return 成功有無
     */
    private function __saveConditionMaterials($userID, $datas)
    {
        // 余った材料
        if (array_key_exists('surplusMaterial', $datas)) {
            $surplusMaterialCount = count($datas['surplusMaterial']);
            for ($i = 0; $i < $surplusMaterialCount; $i++) {

                if (empty($datas['surplusMaterial'][$i]) == false) {
                    $saveData['user_id'] = $userID;
                    $saveData['type'] = ConditionMaterialsTable::SURPLUS_TYPE;
                    $saveData['name'] = $datas['surplusMaterial'][$i];
                    $saveData['hiragana'] = $this->Convert->toHiragana($datas['surplusMaterial'][$i]);
                    $saveData['creator'] = $userID;
                    $saveData['modifier'] = $userID;

                    $saveDatas[] = $saveData;
                }
            }
        }

        // 使用しない材料
        if (array_key_exists('notUseMaterial', $datas)) {
            $notUseMaterialCount = count($datas['notUseMaterial']);
            for ($i = 0; $i < $notUseMaterialCount; $i++) {

                if (empty($datas['notUseMaterial'][$i]) == false) {
                    $saveData['user_id'] = $userID;
                    $saveData['type'] = ConditionMaterialsTable::NOT_USE_TYPE;
                    $saveData['name'] = $datas['notUseMaterial'][$i];
                    $saveData['hiragana'] = $this->Convert->toHiragana($datas['notUseMaterial'][$i]);
                    $saveData['creator'] = $userID;
                    $saveData['modifier'] = $userID;

                    $saveDatas[] = $saveData;
                }
            }
        }

        // 保存
        $this->__conditionMaterials->deleteAll(['user_id' => $userID]);
        if (empty($saveDatas) === false) {
            $entities = $this->__conditionMaterials->newEntities($saveDatas);
            if ($this->__conditionMaterials->saveMany($entities)) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * 条件_評価を保存
     * @param ログインユーザーID
     * @param postデータ
     * @return 成功有無
     */
    private function __saveConditionEvaluations($userID, $datas)
    {
        // 検索条件_評価を保存
        $saveData['user_id'] = $userID;
        $saveData['cospa'] = $datas['evaluationCospa'];
        $saveData['ease'] = $datas['evaluationEase'];
        $saveData['taste'] = $datas['evaluationTaste'];
        $saveData['creator'] = $userID;
        $saveData['modifier'] = $userID;

        $conditionEvaluation = $this->__conditionEvaluations
            ->find()
            ->select([
                'id',
            ])
            ->where([
                'user_id' => $userID,
            ])
            ->first();

        if (empty($conditionEvaluation)) {
            $conditionEvaluation = $this->__conditionEvaluations->newEntity();
        }

        $entitie = $this->__conditionEvaluations->patchEntity($conditionEvaluation, $saveData);
        if ($this->__conditionEvaluations->save($entitie)) {
            return true;
        }
        return false;
    }

}
