<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * LESSON: Model Conventions + Mass Assignment
 *
 * This model demonstrates:
 * - Branch 01: Naming conventions (table: projects, model: Project)
 * - Branch 02: Mass assignment protection with $fillable
 */
class Project extends Model
{
    /**
     * LESSON: Mass Assignment Protection (Branch 02)
     *
     * The $fillable array whitelists which fields can be mass assigned.
     * This protects against attackers injecting malicious data.
     *
     * Example attack prevented:
     * Project::create(['name' => 'Hack', 'is_admin' => true]);
     * ^ 'is_admin' would be IGNORED because it's not in $fillable
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        // 'user_id' is NOT here - we set it explicitly in controller
        // This prevents users from assigning projects to other users!
    ];

    /**
     * ALTERNATIVE: Using $guarded (blacklist approach)
     *
     * Instead of whitelisting with $fillable, you can blacklist with $guarded:
     *
     * protected $guarded = ['id', 'user_id', 'created_at', 'updated_at'];
     *
     * This allows all OTHER fields to be mass assigned.
     * Use $fillable for stricter control, $guarded for flexibility.
     */
}
