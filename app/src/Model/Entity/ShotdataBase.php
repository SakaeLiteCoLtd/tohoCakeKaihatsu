<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShotdataBase Entity
 *
 * @property int $id
 * @property string $factory_code
 * @property int $machine_num
 * @property string $conf_a_b
 * @property \Cake\I18n\FrozenTime $datetime
 * @property float $stop_time
 * @property int|null $temp_outside
 * @property int|null $temp_inside
 * @property int|null $temp_water
 * @property int|null $analog1_ch1
 * @property int|null $analog1_ch2
 * @property int|null $analog1_ch3
 * @property int|null $analog1_ch4
 * @property int|null $valid_data_num
 * @property int $existence_stop
 * @property int $place_stop
 * @property int $existence_out_limit
 * @property int $place_out_limit
 * @property int $existence_change_standard_value
 * @property float|null $value1_mode
 * @property float|null $value1_mean
 * @property float|null $value1_max
 * @property float|null $value1_min
 * @property float|null $value1_std
 * @property float|null $value2_mode
 * @property float|null $value2_mean
 * @property float|null $value2_max
 * @property float|null $value2_min
 * @property float|null $value2_std
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
        'factory_code' => true,
        'machine_num' => true,
        'conf_a_b' => true,
        'datetime' => true,
        'stop_time' => true,
        'temp_outside' => true,
        'temp_inside' => true,
        'temp_water' => true,
        'analog1_ch1' => true,
        'analog1_ch2' => true,
        'analog1_ch3' => true,
        'analog1_ch4' => true,
        'valid_data_num' => true,
        'existence_stop' => true,
        'place_stop' => true,
        'existence_out_limit' => true,
        'place_out_limit' => true,
        'existence_change_standard_value' => true,
        'value1_mode' => true,
        'value1_mean' => true,
        'value1_max' => true,
        'value1_min' => true,
        'value1_std' => true,
        'value2_mode' => true,
        'value2_mean' => true,
        'value2_max' => true,
        'value2_min' => true,
        'value2_std' => true,
        'delete_flag' => true,
        'created_at' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'factory' => true,
        'product_condition_parent' => true
    ];
}
