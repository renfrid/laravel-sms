<?php

namespace App\Models;

use App\Rules\PhoneNumber;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //roles
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param string|array $roles
     * @return bool
     */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                abort(401, 'This action is unauthorized.');
        }

        return $this->hasRole($roles) ||
            abort(401, 'This action is unauthorized.');
    }

    /**
     * Check multiple roles
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
     * Check one role
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }


    //rules
    public static function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|max:255|unique:users',
            'phone' => ['required', new PhoneNumber()],
            'role_ids' => 'required',
            'password' => 'required|string|min:8',
            'password_confirm' => 'required|same:password'
        ];
    }

    //messages
    public static function messages(): array
    {
        return [
            'name.required' => 'Full name required',
            'email.required' => 'Email required',
            'email.unique' => 'Email must be unique',
            'phone.required' => 'Phone required',
            'role_ids.required' => 'Role(s) required',
            'password.required' => 'Password required',
            'password_confirm.required' => 'Confirm password required',
            'password_confirm.same:password' => 'Password must match',
        ];
    }
}
