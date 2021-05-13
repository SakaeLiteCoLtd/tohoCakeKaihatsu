<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PriceMaterial Entity
 *
 * @property int $id
 * @property int $material_id
 * @property int $material_supplier_id
 * @property float $price
 * @property string|null $lot_remarks
 * @property \Cake\I18n\FrozenDate $start_deal
 * @property \Cake\I18n\FrozenDate|null $finish_deal
 * @property int $is_active
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\Material $material
 * @property \App\Model\Entity\MaterialSupplier $material_supplier
 */
class PriceMaterial extends Entity
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
        'material_id' => true,
        'material_supplier_id' => true,
        'price' => true,
        'lot_remarks' => true,
        'start_deal' => true,
        'finish_deal' => true,
        'is_active' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'material' => true,
        'material_supplier' => true
    ];
}
