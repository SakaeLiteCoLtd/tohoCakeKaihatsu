<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductMaterialLotNumber Entity
 *
 * @property int $id
 * @property int $product_machine_material_id
 * @property \Cake\I18n\FrozenTime $datetme
 * @property string $lot_number
 * @property int $staff_id
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\ProductMachineMaterial $product_machine_material
 * @property \App\Model\Entity\Staff $staff
 */
class ProductMaterialLotNumber extends Entity
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
        'product_machine_material_id' => true,
        'datetme' => true,
        'lot_number' => true,
        'staff_id' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'product_machine_material' => true,
        'staff' => true
    ];
}
