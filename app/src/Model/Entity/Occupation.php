<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Occupation Entity
 *
 * @property int $id
 * @property int $office_id
 * @property string $occupation
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\Office $office
 * @property \App\Model\Entity\Staff[] $staffs
 */
class Occupation extends Entity
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
        'office_id' => true,
        'occupation' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'office' => true,
        'staffs' => true
    ];
}
