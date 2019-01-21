<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Team Entity
 *
 * @property int $id
 * @property string $team_name
 * @property string $currency_code
 * @property string $currency_symbol
 * @property string $connection_number
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $ical
 *
 * @property \App\Model\Entity\Event[] $events
 * @property \App\Model\Entity\Fee[] $fees
 * @property \App\Model\Entity\Place[] $places
 * @property \App\Model\Entity\TeamMember[] $team_members
 * @property \App\Model\Entity\UsersFee[] $users_fees
 */
class Team extends Entity
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
        'team_name' => true,
        'currency_code' => true,
        'currency_symbol' => true,
        'connection_number' => true,
        'created' => true,
        'modified' => true,
        'ical' => true,
        'events' => true,
        'fees' => true,
        'places' => true,
        'team_members' => true,
        'users_fees' => true
    ];
}
