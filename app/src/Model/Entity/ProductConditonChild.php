<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductConditonChild Entity
 *
 * @property int $id
 * @property int $product_material_machine_id
 * @property int $cylinder_number
 * @property string $cylinder_name
 * @property float $temp_1
 * @property float|null $temp_1_upper_limit
 * @property float|null $temp_1_lower_limit
 * @property float $temp_2
 * @property float|null $temp_2_upper_limit
 * @property float|null $temp_2_lower_limit
 * @property float $temp_3
 * @property float|null $temp_3_upper_limit
 * @property float|null $temp_3_lower_limit
 * @property float $temp_4
 * @property float|null $temp_4_upper_limit
 * @property float|null $temp_4_lower_limit
 * @property float $temp_5
 * @property float|null $temp_5_upper_limit
 * @property float|null $temp_5_lower_limit
 * @property float $temp_6
 * @property float|null $temp_6_upper_limit
 * @property float|null $temp_6_lower_limit
 * @property float $temp_7
 * @property float|null $temp_7_upper_limit
 * @property float|null $temp_7_lower_limit
 * @property float $extrude_roatation
 * @property float $extrusion_load
 * @property float|null $extrusion_upper_limit
 * @property float|null $extrusion_lower_limit
 * @property float $pickup_speed
 * @property float|null $pickup_speed_upper_limit
 * @property float|null $pickup_speed_lower_limit
 * @property string|null $screw_mesh_1
 * @property string|null $screw_number_1
 * @property string|null $screw_mesh_2
 * @property string|null $screw_number_2
 * @property string|null $screw_mesh_3
 * @property string|null $screw_number_3
 * @property string|null $screw
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\ProductMaterialMachine $product_material_machine
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
        'product_material_machine_id' => true,
        'cylinder_number' => true,
        'cylinder_name' => true,
        'temp_1' => true,
        'temp_1_upper_limit' => true,
        'temp_1_lower_limit' => true,
        'temp_2' => true,
        'temp_2_upper_limit' => true,
        'temp_2_lower_limit' => true,
        'temp_3' => true,
        'temp_3_upper_limit' => true,
        'temp_3_lower_limit' => true,
        'temp_4' => true,
        'temp_4_upper_limit' => true,
        'temp_4_lower_limit' => true,
        'temp_5' => true,
        'temp_5_upper_limit' => true,
        'temp_5_lower_limit' => true,
        'temp_6' => true,
        'temp_6_upper_limit' => true,
        'temp_6_lower_limit' => true,
        'temp_7' => true,
        'temp_7_upper_limit' => true,
        'temp_7_lower_limit' => true,
        'extrude_roatation' => true,
        'extrusion_load' => true,
        'extrusion_upper_limit' => true,
        'extrusion_lower_limit' => true,
        'pickup_speed' => true,
        'pickup_speed_upper_limit' => true,
        'pickup_speed_lower_limit' => true,
        'screw_mesh_1' => true,
        'screw_number_1' => true,
        'screw_mesh_2' => true,
        'screw_number_2' => true,
        'screw_mesh_3' => true,
        'screw_number_3' => true,
        'screw' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'product_material_machine' => true
    ];
}
