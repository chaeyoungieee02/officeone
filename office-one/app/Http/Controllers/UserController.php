<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display listing of users (DataTable).
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query();

            return DataTables::of($query)
                ->addColumn('avatar', function ($user) {
                    if ($user->profile_photo) {
                        return '<img src="' . asset('storage/' . $user->profile_photo) . '" alt="' . e($user->name) . '" width="40" height="40" class="rounded-circle" style="object-fit:cover;">';
                    }
                    return '<span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary text-white" style="width:40px;height:40px;font-size:1rem;">' . strtoupper(substr($user->name, 0, 1)) . '</span>';
                })
                ->addColumn('role_badge', function ($user) {
                    return $user->role === 'admin'
                        ? '<span class="badge bg-danger">Admin</span>'
                        : '<span class="badge bg-primary">User</span>';
                })
                ->addColumn('status_badge', function ($user) {
                    return $user->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('action', function ($user) {
                    $viewBtn = '<a href="' . route('users.show', $user->id) . '" class="btn btn-sm btn-info me-1" title="View"><i class="bi bi-eye"></i></a>';
                    $editBtn = '<a href="' . route('users.edit', $user->id) . '" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil"></i></a>';
                    $deleteBtn = '';
                    // Prevent deleting yourself
                    if (auth()->id() !== $user->id) {
                        $deleteBtn = '
                            <form action="' . route('users.destroy', $user->id) . '" method="POST" class="d-inline">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Are you sure you want to delete this user?\')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>';
                    }
                    return $viewBtn . $editBtn . $deleteBtn;
                })
                ->rawColumns(['avatar', 'role_badge', 'status_badge', 'action'])
                ->make(true);
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'role'          => 'required|in:admin,user',
            'is_active'     => 'sometimes|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password'      => 'nullable|string|min:8|confirmed',
            'role'          => 'required|in:admin,user',
            'is_active'     => 'sometimes|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        // Handle photo removal
        if ($request->boolean('remove_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = null;
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
