<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShotWork Entity
 *
 * @property int $id
 * @property int $factory_id
 * @property int|null $product_condition_parent_id
 * @property \Cake\I18n\FrozenTime $datetime_start
 * @property \Cake\I18n\FrozenTime $datetime_finish
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\Factory $factory
 * @property \App\Model\Entity\ProductConditionParent $product_condition_parent
 */
class ShotWork extends Entity
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
        'factory_id' => true,
        'product_condition_parent_id' => true,
        'datetime_start' => true,
        'datetime_finish' => true,
        'delete_flag' => true,
        'created_at' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'factory' => true,
        'product_condition_parent' => true
    ];
}
