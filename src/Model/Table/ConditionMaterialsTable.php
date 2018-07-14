<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ConditionMaterials Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\ConditionMaterial get($primaryKey, $options = [])
 * @method \App\Model\Entity\ConditionMaterial newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ConditionMaterial[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ConditionMaterial|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConditionMaterial patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ConditionMaterial[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ConditionMaterial findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ConditionMaterialsTable extends Table
{

    /** 種別（余った材料） */
    public const SURPLUS_TYPE = '1';
    /** 種別（使用しない材料） */
    public const NOT_USE_TYPE = '2';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('condition_materials');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('type')
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('hiragana')
            ->maxLength('hiragana', 255)
            ->requirePresence('hiragana', 'create')
            ->notEmpty('hiragana');

        $validator
            ->integer('creator')
            ->requirePresence('creator', 'create')
            ->notEmpty('creator');

        $validator
            ->integer('modifier')
            ->requirePresence('modifier', 'create')
            ->notEmpty('modifier');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
