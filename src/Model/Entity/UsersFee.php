<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsersFee Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $fee_id
 * @property int $team_id
 * @property int $paid
 * @property \Cake\I18n\FrozenDate $date
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Fee $fee
 * @property \App\Model\Entity\Team $team
 */
class UsersFee extends Entity
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
        'user_id' => true,
        'fee_id' => true,
        'team_id' => true,
        'paid' => true,
        'date' => true,
        'user' => true,
        'fee' => true,
        'team' => true
    ];
}
