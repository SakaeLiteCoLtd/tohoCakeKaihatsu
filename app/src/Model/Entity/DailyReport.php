<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DailyReport Entity
 *
 * @property int $id
 * @property int $product_id
 * @property string $machine_num
 * @property \Cake\I18n\FrozenTime $start_datetime
 * @property \Cake\I18n\FrozenTime $finish_datetime
 * @property int $amount
 * @property float $sum_weight
 * @property float $total_loss_weight
 * @property string|null $bik
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\InspectionDataResultParent[] $inspection_data_result_parents
 */
class DailyReport extends Entity
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
        'product_id' => true,
        'machine_num' => true,
        'start_datetime' => true,
        'finish_datetime' => true,
        'amount' => true,
        'sum_weight' => true,
        'total_loss_weight' => true,
        'bik' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'product' => true,
        'inspection_data_result_parents' => true
    ];
}
