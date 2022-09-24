<?php
/**
 * Users Model
 *
 * @author    Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * @var string constant table name
     */
    const TABLE_NAME = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function franchisees() {
        return $this->hasOne(Franchisees::class, 'id', 'franchisees_id');
    }

    public function role() {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    /**
     * Get all users
     *
     * @return object
     */
    public static function get_all_users() {
        $route_model = new BreadRoutes(self::TABLE_NAME);
        $all_users = self::with('franchisees')->with('role')->get();

        $mapped = $all_users->map(function ($value) use ($route_model) {

            $delete_button = '<button type="button" class="btn btn-sm btn-danger btn-delete" data-action="' . $route_model->get_route('delete', ['id' => $value->id]) . '">
        <i class="fa fa-trash"></i>
      </button>';
            $user = \Illuminate\Support\Facades\Auth::user();
            $role_name = Role::find_role_by_id($user->role_id);
            if ($role_name !== 'admin') {
                $delete_button = '';
            }

            $actions = [
                '<a href="' . $route_model->get_route('edit', ['id' => $value->id]) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a> ',
                '<a href="' . $route_model->get_route('set_status', ['id' => $value->id]) . '" class="btn btn-sm ' . (!$value->is_inactive ? 'btn-success' : 'btn-secondary') . '"><i class="fa ' . (!$value->is_inactive ? 'fa-toggle-on' : 'fa-toggle-off') . '"></i></a> ',
                $delete_button,
            ];

            return [
                'id' => $value->id,
                'Name' => $value->name,
                'Branch' => (isset($value->franchisees->name)) ? $value->franchisees->name : '',
                'Role' => (isset($value->role->name)) ? $value->role->name : '',
                'Created At' => strtotime($value->created_at),
                'Updated At' => strtotime($value->updated_at),
                'Actions' => implode(' ', $actions)
            ];
        })->toArray();

        $headers = [];
        if (isset($mapped[0])) {
            $mappedHeaders = $mapped[0];
            $headers = array_keys($mappedHeaders);
        }

        $return = [
            'headers' => $headers,
            'data' => $mapped,
        ];

        return $return;
    }

    /**
     * Get all fields of users table
     *
     * @return BreadFields
     */
    public static function get_all_users_fields() {
        $bread_fields = new BreadFields(self::TABLE_NAME);
        return $bread_fields->get_all_fields();
    }

    /**
     * Store Supply
     *
     * @return BreadFields
     */
    public function store_users($request) {
        try {
            $this->name = $request->name;
            $this->password = Hash::make($request->password);
            $this->email = $request->email;
            $this->role_id = $request->role_id;
            $this->franchisees_id = $request->franchisees_id;
            $this->save();

            self::update_users($request, $this->id);

        } catch (\Throwable$th) {
            //throw $th;
            return false;
        }

        return true;
    }

    /**
     * Update Supply
     *
     * @return BreadFields
     */
    public static function update_users($request, $id) {
        try {
            $users = self::find($id);
            if ($users->tokens->isEmpty()) {
                $token = $users->createToken($request->password);
                $users->api_token = $token->plainTextToken;
            }
            $users->name = $request->name;
            $users->password = Hash::make($request->password);
            $users->email = $request->email;
            $users->role_id = $request->role_id;
            $users->franchisees_id = $request->franchisees_id;
            $users->update();

        } catch (\Exception$th) {
            dd($th);
        }
        return true;
    }

    /**
     * get users
     *
     * @return Collection
     */
    public static function get_users($id) {
        return self::find($id);
    }
}
