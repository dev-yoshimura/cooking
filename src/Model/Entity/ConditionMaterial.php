<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ConditionMaterial Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $type
 * @property string $name
 * @property string $hiragana
 * @property \Cake\I18n\FrozenTime $created
 * @property int $creator
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $modifier
 *
 * @property \App\Model\Entity\User $user
 */
class ConditionMaterial extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'type' => true,
        'name' => true,
        'hiragana' => true,
        'created' => true,
        'creator' => true,
        'modified' => true,
        'modifier' => true,
        'user' => true
    ];
}
