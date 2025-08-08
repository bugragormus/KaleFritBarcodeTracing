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

        $user = User::findOrFail($id);

        $user->delete();

        if (!$user) {
            toastr()->error('Kullanıcı silinemedi.');
        }

        return response()->json(['message' => 'Kullanıcı silindi!']);
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
