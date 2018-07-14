<?php

namespace App\Controller\Component;

use App\Model\Table\ConditionMaterialsTable;
use Cake\Controller\Component;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * メニュー管理コンポーネント
 */
class MenuManagerComponent extends Component
{
    private $__selectMenus;
    private $__menus;
    private $__materials;
    private $__recipes;
    private $__conditionMaterials;
    private $__histories;

    /**
     * 初期化処理
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->__selectMenus = TableRegistry::get('select_menus');
        $this->__menus = TableRegistry::get('menus');
        $this->__materials = TableRegistry::get('materials');
        $this->__recipes = TableRegistry::get('recipes');
        $this->__conditionMaterials = TableRegistry::get('condition_materials');
        $this->__histories = TableRegistry::get('histories');
    }

    /**
     * 選択した料理を取得
     * @param ログインユーザーID
     * @return 選択した料理
     */
    public function getSelectMenu($userID)
    {
        // サラダ
        $saladMenu = $this->__selectMenus
            ->setAlias('A')
            ->find()
            ->select([
                'id' => 'A.salad',
                'name' => 'B.name',
            ])
            ->join([
                'table' => 'menus',
                'alias' => 'B',
                'type' => 'LEFT',
                'conditions' => 'A.salad = B.id',
            ])
            ->where([
                'user_id' => $userID,
            ])
            ->first();

        // スープ
        $soupMenu = $this->__selectMenus
            ->setAlias('A')
            ->find()
            ->select([
                'id' => 'A.soup',
                'name' => 'B.name',
            ])
            ->join([
                'table' => 'menus',
                'alias' => 'B',
                'type' => 'LEFT',
                'conditions' => 'A.soup = B.id',
            ])
            ->where([
                'user_id' => $userID,
            ])
            ->first();

        // おかず
        $sideDishMenu = $this->__selectMenus
            ->setAlias('A')
            ->find()
            ->select([
                'id' => 'A.side_dish',
                'name' => 'B.name',
            ])
            ->join([
                'table' => 'menus',
                'alias' => 'B',
                'type' => 'LEFT',
                'conditions' => 'A.side_dish = B.id',
            ])
            ->where([
                'user_id' => $userID,
            ])
            ->first();

        return [
            'saladMenu' => $saladMenu,
            'soupMenu' => $soupMenu,
            'sideDishMenu' => $sideDishMenu,
        ];
    }

    /**
     * 選択した料理の材料を取得
     * @param ログインユーザーID
     * @return 選択した料理の材料
     */
    public function getMaterials($userID)
    {
        $selectMenu = $this->__selectMenus
            ->find()
            ->select(['salad', 'soup', 'side_dish'])
            ->where(['user_id' => $userID])
            ->first();

        $menuIDs[] = $selectMenu['salad'];
        $menuIDs[] = $selectMenu['soup'];
        $menuIDs[] = $selectMenu['side_dish'];

        $materials = $this->__materials;
        $maxCount = count($menuIDs);
        if (0 < $maxCount) {
            $materials = $this->__materials->find()
                ->select(['name', 'quantity'])
                ->where(['menu_id IN' => $menuIDs])
                ->order(['type' => 'ASC', 'CAST(hiragana AS char)' => 'ASC']);
        }

        return $materials;
    }

    /**
     * 選択した料理の作り方を取得
     * @param ログインユーザーID
     * @return 選択した料理の作り方
     */
    public function getRecipes($userID)
    {
        $selectMenu = $this->__selectMenus
            ->find()
            ->select(['salad', 'soup', 'side_dish'])
            ->where(['user_id' => $userID])
            ->first();

        $saladMenuID = $selectMenu['salad'];
        $soupMenuID = $selectMenu['soup'];
        $sideDishMenuID = $selectMenu['side_dish'];

        $saladRecipes = $this->__recipes->find()
            ->select(['detail'])
            ->where(['menu_id' => $saladMenuID])
            ->order(['id' => 'ASC']);

        $soupRecipes = $this->__recipes->find()
            ->select(['detail'])
            ->where(['menu_id' => $soupMenuID])
            ->order(['id' => 'ASC']);

        $sideDishRecipes = $this->__recipes->find()
            ->select(['detail'])
            ->where(['menu_id' => $sideDishMenuID])
            ->order(['id' => 'ASC']);

        return [
            'saladRecipes' => $saladRecipes,
            'soupRecipes' => $soupRecipes,
            'sideDishRecipes' => $sideDishRecipes,
        ];
    }

    /**
     * 選択した料理を保存
     * @param ログインユーザーID
     * @param postデータ
     * @return 成功有無
     */
    public function saveSelectMenu($userID, $datas)
    {
        try {
            if ($datas['saladMenuID'] === '-1'
                && $datas['soupMenuID'] === '-1'
                && $datas['sideDishMenuID'] === '-1') {
                // 未選択の場合
                return true;
            }

            $saveData['salad'] = $datas['saladMenuID'];
            $saveData['soup'] = $datas['soupMenuID'];
            $saveData['side_dish'] = $datas['sideDishMenuID'];
            $saveData['modifier'] = $userID;

            $entity = $this->__selectMenus
                ->find()
                ->where([
                    'user_id' => $userID,
                ])
                ->first();

            if (empty($entity)) {
                $saveData['user_id'] = $userID;
                $saveData['creator'] = $userID;
                $entity = $this->__selectMenus->newEntity();
            }

            if ($this->__selectMenus->save($this->__selectMenus->patchEntity($entity, $saveData))) {
                return true;
            }
        } catch (Exception $ex) {
            Log::write('debug', $ex);
        }

        return false;
    }

    /**
     * 料理を選択
     * @param ログインユーザーID
     * @param 種別
     * @param 作ったことがない料理を選ぶチェック有無
     * @param 作ったことがある料理を選ぶチェック有無
     * @return 料理
     */
    public function selectMenu($userID, $type, $isNew, $isOld)
    {
        $returnValue = [
            'id' => '',
            'name' => '',
            'image' => '',
        ];
        $isSuccess = false;
        try {
            $newMenus = [];
            $oldMenus = [];
            if ($isNew == true) {
                $newMenus = $this->__selectToNewMenu($userID, $type);
            }

            if ($isOld == true) {
                $oldMenus = $this->__selectToOldMenu($userID, $type);
            }

            if ($isNew == false
                && $isOld == false) {
                $newMenus = $this->__selectToNewMenu($userID, $type);
            }

            $values = array_merge($newMenus, $oldMenus);
            $maxCount = count($values);
            if (0 < $maxCount) {
                $key = mt_rand(0, $maxCount - 1);
                $returnValue = [
                    'id' => $values[$key]['id'],
                    'name' => $values[$key]['name'],
                    'image' => base64_encode(stream_get_contents($values[$key]['image'])),
                ];
            }
            $isSuccess = true;
        } catch (Exception $ex) {
            Log::write('debug', $ex);
        }

        $returnValue['isSuccess'] = $isSuccess;
        return $returnValue;
    }

    /**
     * 作ったことがない料理を選ぶ
     * @param ログインユーザーID
     * @param 種別
     * @return 料理
     */
    private function __selectToNewMenu($userID, $type)
    {
        $this->__menus->setAlias('A');
        $this->__conditionMaterials->setAlias('C');
        $this->__histories->setAlias('D');

        // 余ってる材料
        $surplusMaterials = $this->__conditionMaterials
            ->find()
            ->where([
                'user_id' => $userID,
                'type' => ConditionMaterialsTable::SURPLUS_TYPE,
            ])
            ->toArray();

        $subquery1 = $this->__conditionMaterials
            ->find()
            ->select('B.id')
            ->where(function ($exp, $q) {
                return $exp->equalFields('C.hiragana', 'B.hiragana');
            })
            ->where([
                'C.user_id' => $userID,
                'C.type' => ConditionMaterialsTable::SURPLUS_TYPE,
            ]);

        // 使用しない材料
        $notUseMaterials = $this->__conditionMaterials
            ->find()
            ->where([
                'user_id' => $userID,
                'type' => ConditionMaterialsTable::NOT_USE_TYPE,
            ])
            ->toArray();

        $subquery2 = $this->__conditionMaterials
            ->find()
            ->select('B.id')
            ->where(function ($exp, $q) {
                return $exp->equalFields('C.hiragana', 'B.hiragana');
            })
            ->where([
                'C.user_id' => $userID,
                'C.type' => ConditionMaterialsTable::NOT_USE_TYPE,
            ]);

        // 履歴
        $subquery3 = $this->__histories
            ->find()
            ->select('D.id')
            ->where(function ($exp, $q) {
                return $exp->equalFields('D.menu_id', 'A.id');
            })
            ->where(['D.user_id' => $userID]);

        $menus = $this->__menus
            ->find()
            ->distinct()
            ->select(['A.id', 'A.name', 'A.image'])
            ->join([
                'table' => 'materials',
                'alias' => 'B',
                'type' => 'INNER',
                'conditions' => 'A.id = B.menu_id',
            ])
            ->where(function ($exp, $q) use ($subquery3) {
                return $exp->notExists($subquery3);
            })
            ->where([
                'A.type' => $type,
            ]);

        if (empty($surplusMaterials) == false) {
            $menus = $menus->where(function ($exp, $q) use ($subquery1) {
                return $exp->exists($subquery1);
            });
        }

        if (empty($notUseMaterials) == false) {
            $menus = $menus->where(function ($exp, $q) use ($subquery2) {
                return $exp->notExists($subquery2);
            });
        }

        return $menus->toArray();
    }

    /**
     * 作ったことがある料理を選ぶ
     * @param ログインユーザーID
     * @param 種別
     * @return 料理
     */
    private function __selectToOldMenu($userID, $type)
    {
        $this->__menus->setAlias('A');
        $this->__conditionMaterials->setAlias('E');

        // 余ってる材料
        $surplusMaterials = $this->__conditionMaterials
            ->find()
            ->where([
                'user_id' => $userID,
                'type' => ConditionMaterialsTable::SURPLUS_TYPE,
            ])
            ->toArray();

        $subquery1 = $this->__conditionMaterials
            ->find()
            ->select('D.id')
            ->where(function ($exp, $q) {
                return $exp->equalFields('E.hiragana', 'D.hiragana');
            })
            ->where([
                'E.user_id' => $userID,
                'E.type' => ConditionMaterialsTable::SURPLUS_TYPE,
            ]);

        // 使用しない材料
        $notUseMaterials = $this->__conditionMaterials
            ->find()
            ->where([
                'user_id' => $userID,
                'type' => ConditionMaterialsTable::NOT_USE_TYPE,
            ])
            ->toArray();

        $subquery2 = $this->__conditionMaterials
            ->find()
            ->select('D.id')
            ->where(function ($exp, $q) {
                return $exp->equalFields('E.hiragana', 'D.hiragana');
            })
            ->where([
                'E.user_id' => $userID,
                'E.type' => ConditionMaterialsTable::NOT_USE_TYPE,
            ]);

        $menus = $this->__menus
            ->find()
            ->distinct()
            ->select(['A.id', 'A.name', 'A.image'])
            ->join([
                'table' => 'histories',
                'alias' => 'B',
                'type' => 'INNER',
                'conditions' => 'A.id = B.menu_id',
            ])
            ->join([
                'table' => 'condition_evaluations',
                'alias' => 'C',
                'type' => 'INNER',
                'conditions' => 'B.user_id = C.user_id',
            ])
            ->join([
                'table' => 'materials',
                'alias' => 'D',
                'type' => 'INNER',
                'conditions' => 'A.id = D.menu_id',
            ])
            ->where([
                'B.user_id' => $userID,
                'C.user_id' => $userID,
                'B.cospa >= C.cospa',
                'B.ease >= C.ease',
                'B.taste >= C.taste',
                'A.type' => $type,
            ]);

        if (empty($surplusMaterials) == false) {
            $menus = $menus->where(function ($exp, $q) use ($subquery1) {
                return $exp->exists($subquery1);
            });
        }

        if (empty($notUseMaterials) == false) {
            $menus = $menus->where(function ($exp, $q) use ($subquery2) {
                return $exp->notExists($subquery2);
            });
        }
        return $menus->toArray();
    }
}
