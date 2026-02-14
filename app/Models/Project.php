<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * LESSON: Model Conventions
 *
 * This model follows all Laravel conventions:
 * - Model name: Project (singular, PascalCase)
 * - Table name: projects (plural, snake_case) - auto-detected!
 * - Primary key: id (auto-incrementing) - auto-detected!
 * - Timestamps: created_at, updated_at - auto-managed!
 *
 * Because we follow conventions, we don't need ANY extra configuration!
 */
class Project extends Model
{
    // LESSON: When following conventions, the model can be nearly empty!
    // Laravel handles table name, primary key, and timestamps automatically.

    // UNCOMMENT BELOW to see how to customize (but don't unless needed!)

    // Custom table name (only if your table isn't 'projects')
    // protected $table = 'tbl_projects';

    // Custom primary key (only if it's not 'id')
    // protected $primaryKey = 'project_id';

    // Non-incrementing primary key (only for UUIDs or strings)
    // public $incrementing = false;
    // protected $keyType = 'string';

    // Disable timestamps (only if table lacks created_at/updated_at)
    // public $timestamps = false;
}
