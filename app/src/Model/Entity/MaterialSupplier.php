<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MaterialSupplier Entity
 *
 * @property int $id
 * @property int $factory_id
 * @property string $name
 * @property string|null $office
 * @property string|null $department
 * @property string|null $address
 * @property string|null $tel
 * @property string|null $fax
 * @property int $is_active
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\Factory $factory
 * @property \App\Model\Entity\PriceMaterial[] $price_materials
 */
class MaterialSupplier extends Entity
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
        'name' => true,
        'office' => true,
        'department' => true,
        'address' => true,
        'tel' => true,
        'fax' => true,
        'is_active' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'factory' => true,
        'price_materials' => true
    ];
}
