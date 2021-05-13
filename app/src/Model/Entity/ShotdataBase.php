<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShotdataBase Entity
 *
 * @property int $id
 * @property int $factory_id
 * @property int|null $product_condition_parent_id
 * @property \Cake\I18n\FrozenTime $datetime
 * @property int $valid_data_num
 * @property float $stop_time
 * @property int $extrusion_switch_conf
 * @property int $pickup_switch_conf
 * @property float|null $value_mode
 * @property float|null $value_ave
 * @property float|null $value_max
 * @property float|null $value_min
 * @property float $value_std
 * @property int $status_sencer
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\Factory $factory
 * @property \App\Model\Entity\ProductConditionParent $product_condition_parent
 */
class ShotdataBase extends Entity
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
        'datetime' => true,
        'valid_data_num' => true,
        'stop_time' => true,
        'extrusion_switch_conf' => true,
        'pickup_switch_conf' => true,
        'value_mode' => true,
        'value_ave' => true,
        'value_max' => true,
        'value_min' => true,
        'value_std' => true,
        'status_sencer' => true,
        'delete_flag' => true,
        'created_at' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'factory' => true,
        'product_condition_parent' => true
    ];
}
