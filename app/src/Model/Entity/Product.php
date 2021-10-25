<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property int $factory_id
 * @property string $product_code
 * @property string $name
 * @property string|null $tanni
 * @property float $length
 * @property float $length_cut
 * @property float|null $length_upper_limit
 * @property float|null $length_lower_limit
 * @property int $status_length
 * @property int $status_kensahyou
 * @property int|null $ig_bank_modes
 * @property float|null $weight
 * @property string|null $sakuin
 * @property string|null $bik
 * @property int $customer_id
 * @property int $is_active
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\Factory $factory
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\InspectionDataResultParent[] $inspection_data_result_parents
 * @property \App\Model\Entity\InspectionStandardSizeParent[] $inspection_standard_size_parents
 * @property \App\Model\Entity\PriceProduct[] $price_products
 * @property \App\Model\Entity\ProductConditionParent[] $product_condition_parents
 */
class Product extends Entity
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
        'product_code' => true,
        'name' => true,
        'tanni' => true,
        'length' => true,
        'length_cut' => true,
        'length_upper_limit' => true,
        'length_lower_limit' => true,
        'status_length' => true,
        'status_kensahyou' => true,
        'ig_bank_modes' => true,
        'weight' => true,
        'sakuin' => true,
        'bik' => true,
        'customer_id' => true,
        'is_active' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'factory' => true,
        'customer' => true,
        'inspection_data_result_parents' => true,
        'inspection_standard_size_parents' => true,
        'price_products' => true,
        'product_condition_parents' => true
    ];
}
