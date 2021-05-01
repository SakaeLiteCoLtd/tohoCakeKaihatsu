<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductConditonChild Entity
 *
 * @property int $id
 * @property int $product_condition_parent_id
 * @property int $cylinder_number
 * @property string $cylinder_name
 * @property float $temp_1
 * @property float $temp_2
 * @property float $temp_3
 * @property float $temp_4
 * @property float $temp_5
 * @property float $temp_6
 * @property float $temp_7
 * @property float $extrude_roatation
 * @property float $extrusion_load
 * @property float $pickup_speed
 * @property string $screw_mesh
 * @property string $screw_number
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\ProductConditionParent $product_condition_parent
 */
class ProductConditonChild extends Entity
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
        'product_condition_parent_id' => true,
        'cylinder_number' => true,
        'cylinder_name' => true,
        'temp_1' => true,
        'temp_2' => true,
        'temp_3' => true,
        'temp_4' => true,
        'temp_5' => true,
        'temp_6' => true,
        'temp_7' => true,
        'extrude_roatation' => true,
        'extrusion_load' => true,
        'pickup_speed' => true,
        'screw_mesh' => true,
        'screw_number' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'product_condition_parent' => true
    ];
}
