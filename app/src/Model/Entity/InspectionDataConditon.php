<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InspectionDataConditon Entity
 *
 * @property int $id
 * @property int $product_conditon_child_id
 * @property float $inspection_temp_1
 * @property float $inspection_temp_2
 * @property float $inspection_temp_3
 * @property float $inspection_temp_4
 * @property float $inspection_temp_5
 * @property float $inspection_temp_6
 * @property float $inspection_temp_7
 * @property float|null $inspection_extrude_roatation
 * @property float|null $inspection_extrusion_load
 * @property float|null $inspection_pickup_speed
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\ProductConditonChild $product_conditon_child
 */
class InspectionDataConditon extends Entity
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
        'product_conditon_child_id' => true,
        'inspection_temp_1' => true,
        'inspection_temp_2' => true,
        'inspection_temp_3' => true,
        'inspection_temp_4' => true,
        'inspection_temp_5' => true,
        'inspection_temp_6' => true,
        'inspection_temp_7' => true,
        'inspection_extrude_roatation' => true,
        'inspection_extrusion_load' => true,
        'inspection_pickup_speed' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'product_conditon_child' => true
    ];
}
