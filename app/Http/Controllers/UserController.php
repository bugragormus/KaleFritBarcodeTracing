<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->hasPermission(Permission::USER_LIST)) {
            toastr()->error('Yetki hatası.');

            return back()->withInput();
        }

        $users = User::all();

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->hasPermission(Permission::USER_MANAGE)) {
            toastr()->error('Yetki hatası.');

            return back()->withInput();
        }

        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\User\UserStoreRequest $request
     * @return string
     */
    public function store(UserStoreRequest $request)
    {
        if (!auth()->user()->hasPermission(Permission::USER_MANAGE)) {
            toastr()->error('Yetki hatası.');

            return back()->withInput();
        }

        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        if (!$user) {
            toastr()->error('Kullanıcı oluşturulamadı.');
        }

        toastr()->success('Kullanıcı başarıyla oluşturuldu.');

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->hasPermission(Permission::USER_MANAGE)) {
            toastr()->error('Yetki hatası.');

            return back()->withInput();
        }

        $user = User::findOrFail($id);

        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\User\UserUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        if (!auth()->user()->hasPermission(Permission::USER_MANAGE)) {
            toastr()->error('Yetki hatası.');

            return back()->withInput();
        }

        $data = $request->validated();

        $user = User::findOrFail($id);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'registration_number' => $data['registration_number'],
            'password' => isset($data['password']) ? bcrypt($data['password']) : $user->password
        ]);

        if (!$user) {
            toastr()->error('Kullanıcı düzenlenemedi.');
        }

        toastr()->success('Kullanıcı başarıyla düzenlendi.');

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasPermission(Permission::USER_MANAGE)) {
            toastr()->error('Yetki hatası.');

            return back()->withInput();
        }

        try {
            $user = User::findOrFail($id);

            // Check if user can be deleted
            if ($this->canUserBeDeleted($user)) {
                $user->delete();
                return response()->json(['success' => true, 'message' => 'Kullanıcı başarıyla silindi!']);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Bu kullanıcı silinemez çünkü sistemde aktif olarak kullanılmaktadır. İlişkili kayıtlar silinene kadar bu kullanıcı silinemez.'
                ], 422);
            }
        } catch (\Exception $e) {
            \Log::error('User deletion error: ' . $e->getMessage());
            
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Bu kullanıcı silinemez çünkü sistemde aktif olarak kullanılmaktadır. İlişkili kayıtlar silinene kadar bu kullanıcı silinemez.'
                ], 422);
            }
            
            return response()->json([
                'success' => false, 
                'message' => 'Kullanıcı silinirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user can be safely deleted
     */
    private function canUserBeDeleted($user)
    {
        // Check if user has any related barcodes
        $hasBarcodes = $user->barcodesCreated()->exists() || 
                       $user->barcodesProcessed()->exists() ||
                       \App\Models\Barcode::where('warehouse_transferred_by', $user->id)->exists() ||
                       \App\Models\Barcode::where('delivered_by', $user->id)->exists();

        // Check if user has any barcode history records
        $hasHistory = \App\Models\BarcodeHistory::where('user_id', $user->id)->exists();

        // Check if user has any permissions
        $hasPermissions = $user->permissions()->exists();

        return !($hasBarcodes || $hasHistory || $hasPermissions);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function permissionEdit($id)
    {
        if (!auth()->user()->hasPermission(Permission::USER_MANAGE)) {
            toastr()->error('Yetki hatası.');

            return back()->withInput();
        }

        $user = User::findOrFail($id);

        $user_permissions = $user->permissions->pluck('id')->toArray();

        $permissions = Permission::orderBy('order')->get();

        return view('admin.user.permission', compact([
            'user',
            'permissions',
            'user_permissions'
        ]));
    }

    public function permissionSync(Request $request, $id)
    {
        if (!auth()->user()->hasPermission(Permission::USER_MANAGE)) {
            toastr()->error('Yetki hatası.');

            return back()->withInput();
        }

        $user = User::findOrFail($id);

        // permissions[] array'ini al, eğer yoksa boş array kullan
        $permissions = $request->input('permissions', []);

        // Kullanıcının yetkilerini güncelle
        $user->permissions()->sync($permissions);

        toastr()->success('Kullanıcı yetkileri başarıyla güncellendi.');

        return redirect()->route('user.index');
    }
}
