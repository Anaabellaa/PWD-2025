<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Bookmark[] $bookmarks
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'email' => true,
        'password' => true,
        'created' => true,
        'modified' => true,
        'bookmarks' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    /**
     * Automatically hash passwords when setting them.
     *
     * @param string $value The password to hash
     * @return string|null The hashed password
     */
    protected function _setPassword(string $value): ?string
    {
        if (strlen($value) === 0) {
            return null;
        }

        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($value);
    }
}