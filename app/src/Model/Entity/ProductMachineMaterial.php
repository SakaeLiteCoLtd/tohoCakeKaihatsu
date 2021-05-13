<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductMachineMaterial Entity
 *
 * @property int $id
 * @property int $product_material_machine_id
 * @property int $material_number
 * @property int $material_id
 * @property string $mixing_ratio
 * @property float $dry_temp
 * @property float $dry_hour
 * @property string $recycled_mixing_ratio
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\ProductMaterialMachine $product_material_machine
 * @property \App\Model\Entity\Material $material
 * @property \App\Model\Entity\ProductMaterialLotNumber[] $product_material_lot_numbers
 */
class ProductMachineMaterial extends Entity
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
        'material_number' => true,
        'material_id' => true,
        'mixing_ratio' => true,
        'dry_temp' => true,
        'dry_hour' => true,
        'recycled_mixing_ratio' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'product_material_machine' => true,
        'material' => true,
        'product_material_lot_numbers' => true
    ];
}
