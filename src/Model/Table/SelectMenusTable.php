<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SelectMenus Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\SelectMenu get($primaryKey, $options = [])
 * @method \App\Model\Entity\SelectMenu newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SelectMenu[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SelectMenu|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SelectMenu patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SelectMenu[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SelectMenu findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SelectMenusTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('select_menus');
        $this->setDisplayField('id');
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
            ->integer('salad')
            ->requirePresence('salad', 'create')
            ->notEmpty('salad');

        $validator
            ->integer('soup')
            ->requirePresence('soup', 'create')
            ->notEmpty('soup');

        $validator
            ->integer('side_dish')
            ->requirePresence('side_dish', 'create')
            ->notEmpty('side_dish');

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
