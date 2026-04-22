<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $members = $query->latest()->paginate(15);
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|unique:members,phone',
            'name' => 'required|string|max:255',
        ]);

        Member::create($request->only('phone', 'name'));
        return redirect('/members')->with('success', 'Member berhasil ditambahkan.');
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'phone' => 'required|string|unique:members,phone,' . $member->id,
            'name' => 'required|string|max:255',
        ]);

        $member->update($request->only('phone', 'name'));
        return redirect('/members')->with('success', 'Member berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect('/members')->with('success', 'Member berhasil dihapus.');
    }
}
