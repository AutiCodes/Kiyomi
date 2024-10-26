<?php

namespace Modules\Members\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Members\Models\Member;
use Carbon\Carbon;
use Modules\Members\Enums\ClubStatus;
use Log;
use Modules\Members\Models\UserSync;
use App\Mail\MembersClubStatusChange;
use App\Mail\MembersHonorary;
use App\Mail\AcceptedMember;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Modules\Members\Events\NewMember;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     * Gets and count members
     *
     * @author AutiCodes
     * @return View
     */
    public function index()
    {
        return view('members::pages.index', [
            'members' => Member::orderBy('name', 'asc')
                                    ->where('club_status', '!=', ClubStatus::REMOVED_MEMBER->value) 
                                    ->get(),
            'totalAspirantMember' => Member::where('club_status', '=', ClubStatus::ASPIRANT_MEMBER->value)
                                                ->count(),
            'totalNormalMembers' => Member::where('club_status', '=', ClubStatus::MEMBER->value)
                                                ->count(),
            'totalManagement' => Member::where('club_status', '=', ClubStatus::MANAGEMENT->value)
                                            ->count(),
            'totalDonators' => Member::where('club_status', '=', ClubStatus::DONOR->value)
                                            ->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return View
     */
    public function create()
    {
        return view('members::pages.create_member');
    }

    /**
     * Store a newly created resource in storage.
     * Eventcall NewMember
     * 
     * @author AutiCodes
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'birthdate' => ['required', 'date'],
            'address' => ['required', 'string', 'max:100'],
            'postcode' => ['required', 'string', 'max:10'],
            'city' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:15'],
            'rdw_number' => ['nullable'],
            'knvvl' => ['nullable'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:Members'],
            'club_status' => ['required', 'integer', 'max:8'],
            'instruct' => ['required', 'integer', 'max:1'],
            'PlaneCertCheckbox' => ['nullable'],
            'HeliCertCheckbox' => ['nullable'],
            'gliderCertCheckbox' => ['nullable'],
            'honoraryMemberCheckbox' => ['nullable'], 
            'droneA1Checkbox' => ['nullable'],
            'droneA2Checkbox' => ['nullable'],
            'droneA3Checkbox' => ['nullable'],
        ]);

        try {
            $member = Member::create([
                'name' => $validated['name'],
                'birthdate' => Carbon::parse($validated['birthdate'])->format('Y-m-d'),	
                'address' => $validated['address'],
                'postcode' => $validated['postcode'],
                'city' => $validated['city'],
                'phone' => $validated['phone'],
                'rdw_number' => $validated['rdw_number'],
                'KNVvl' => $validated['knvvl'],
                'email' => $validated['email'],
                'club_status' => $validated['club_status'],
                'instruct' => $validated['instruct'],
                'has_plane_brevet' => $validated['PlaneCertCheckbox'] ?? 0,
                'has_helicopter_brevet' => $validated['HeliCertCheckbox'] ?? 0,	
                'has_glider_brevet' => $validated['gliderCertCheckbox'] ?? 0,
                'in_memoriam' => $validated['honoraryMemberCheckbox'] ?? 0,
                'has_drone_a1' => $validated['droneA1Checkbox'] ?? 0,
                'has_drone_a2' => $validated['droneA2Checkbox'] ?? 0,
                'has_drone_a3' => $validated['droneA3Checkbox'] ?? 0,
            ]);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()
                        ->back()
                        ->withErrors(['error' => 'Iets ging er mis! Contacteer Kelvin voor meer informatie. Foutmelding: ' . $exception->getMessage()]);
        }

        Log::channel('user_activity')->info('Member '. $validated['name'] . 'has been added by '. auth()->user()->name);

        event(new NewMember($member));

        // If the member is a new registration or not yet member, redirect back with text
        return redirect(route('members.index'))->with('success', "Het lid is toegevoegd!");
    }

    /**
     * Show the specified resource.
     *
     * @author AutiCodes
     * @param int $id the id of the member
     * @return View
     */
    public function show($id)
    {
        $member = Member::find($id);

        if (!$member) {
            return redirect(route('members.index'))->with('Lid niet gevonden!');
        }

        return view('members::pages.show_member', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @author AutiCodes
     * @param int $id the id of the member
     * @return View
     */
    public function edit($id)
    {
        $member = Member::find($id);

        if (!$member) {
            return redirect(route('members.index'))->with('error', 'Lid niet gevonden!');
        }

        return view('members::pages.edit_member', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     *
     * TODO: Make new WP acc
     * @author AutiCodes
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'birthdate' => ['required', 'date'],
            'address' => ['required', 'string', 'max:100'],
            'postcode' => ['required', 'string', 'max:10'],
            'city' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:15'],
            'rdw_number' => ['nullable'],
            'knvvl' => ['nullable'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'club_status' => ['required', 'integer', 'max:8'],
            'instruct' => ['required', 'integer', 'max:1'],
            'PlaneCertCheckbox' => ['nullable'],
            'HeliCertCheckbox' => ['nullable'],
            'gliderCertCheckbox' => ['nullable'],
            'honoraryMemberCheckbox' => ['nullable'] ?? 0,
            'droneA1Checkbox' => ['nullable'],
            'droneA2Checkbox' => ['nullable'],
            'droneA3Checkbox' => ['nullable'],
        ]);

        $memberOldData = Member::find($id);

        if (!$memberOldData) {
            return redirect(route('members.index'))->with('error', 'Lid niet gevonden!');
        }

        Member::find($id)->update([
            'name' => $validated['name'],
            'birthdate' => Carbon::parse($validated['birthdate'])->format('Y-m-d'),	
            'address' => $validated['address'],
            'postcode' => $validated['postcode'],
            'city' => $validated['city'],
            'phone' => $validated['phone'],
            'rdw_number' => $validated['rdw_number'],
            'KNVvl' => $validated['knvvl'],
            'email' => $validated['email'],
            'club_status' => $validated['club_status'],
            'instruct' => $validated['instruct'],
            'has_plane_brevet' => $validated['PlaneCertCheckbox'] ?? 0,
            'has_helicopter_brevet' => $validated['HeliCertCheckbox'] ?? 0,
            'has_glider_brevet' => $validated['gliderCertCheckbox'] ?? 0,
            'in_memoriam' => $validated['honoraryMemberCheckbox'] ?? 0,
            'has_drone_a1' => $validated['droneA1Checkbox'] ?? 0,
            'has_drone_a2' => $validated['droneA2Checkbox'] ?? 0,
            'has_drone_a3' => $validated['droneA3Checkbox'] ?? 0,
        ]);

        if (!array_key_exists('honoraryMemberCheckbox', $validated)) {
            $validated['honoraryMemberCheckbox'] = 0;
        }

        // If not member goes to any other member, send mail and update Wordpress login
        if ($memberOldData['club_status'] == ClubStatus::NOT_YET_MEMBER->value && $validated['club_status'] != ClubStatus::NOT_YET_MEMBER->value) {
            // Generate random password
            $userPasswordWP = bin2hex(random_bytes(10));

            try {
                // Update Wordpress login
                UserSync::updateUserPassword($validated['email'], $userPasswordWP);
            }
            catch (\Exception $e) {
                Log::channel('user_activity')->error($e->getMessage());
                return redirect(route('members.index'))->with('error', 'Er is een fout opgetreden bij het aanpassen van het lid. Neem contact op met de beheerder.');
            }

            // Send email to member
            Mail::to($validated['email'])->send(new AcceptedMember($validated['name'], $validated['club_status'], $validated['email'], $userPasswordWP));

            Log::channel('user_activity')->info('Member '. $validated['name'] . ' has been updated by '. auth()->user()->name);

            // Flush cache
            Cache::flush();

            return redirect(route('members.index'))->with('success', 'Het lid is nu geaccepteerd binnen T.R.M.C! Hij krijgt nu een email.');
        }

        // If new registration goes to any other member, send mail and update Wordpress login TODO: Fix replicated code
        if ($memberOldData['club_status'] == ClubStatus::NEW_REGISTRATION->value && $validated['club_status'] != ClubStatus::NEW_REGISTRATION->value) {
            // Generate random password
            $userPasswordWP = bin2hex(random_bytes(10));

            try {
                // Update Wordpress login
                UserSync::updateUserPassword($validated['email'], $userPasswordWP);
            }
            catch (\Exception $e) {
                Log::channel('user_activity')->error($e->getMessage());
                return redirect(route('members.index'))->with('error', 'Er is een fout opgetreden bij het aanpassen van het lid. Neem contact op met de beheerder.');
            }

            // Send email to member
            Mail::to($validated['email'])->send(new AcceptedMember($validated['name'], $validated['club_status'], $validated['email'], $userPasswordWP));

            Log::channel('user_activity')->info('Member '. $validated['name'] . ' has been updated by '. auth()->user()->name);

            // Flush cache
            Cache::flush();

            return redirect(route('members.index'))->with('success', 'Het lid is nu geaccepteerd binnen T.R.M.C! Hij krijgt nu een email.');        
        }

        // Club status mail TODO: Cleaner code
        if ($validated['club_status'] != $memberOldData['club_status'] && $validated['club_status'] != ClubStatus::NOT_YET_MEMBER->value) {	
            // Send email to member
            Mail::to($validated['email'])->send(new MembersClubStatusChange($validated['name'], $memberOldData['club_status'], $validated['club_status']));
        }

        if (!array_key_exists('honoraryMemberCheckbox', $validated) && $validated['club_status'] != ClubStatus::NOT_YET_MEMBER->value) {
            $validated['honoraryMemberCheckbox'] = 0;
        }

        if ($validated['honoraryMemberCheckbox'] != $memberOldData['in_memoriam'] && $validated['club_status'] != ClubStatus::NOT_YET_MEMBER->value) {
            // Send email to member
            Mail::to($validated['email'])->send(new MembersHonorary($validated['name'], $memberOldData['in_memoriam']));
        }

        if ($memberOldData['in_memoriam'] != $validated['honoraryMemberCheckbox'] && $validated['club_status'] != ClubStatus::NOT_YET_MEMBER->value) {
            // Send email to member
            Mail::to($validated['email'])->send(new MembersHonorary($validated['name'], $memberOldData['in_memoriam']));
        }

        // Update user in Wordpress
        try {
            UserSync::updateUser($memberOldData['name'], $validated['name'], $validated['email'], $validated['name']);
        } 
        catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()
                        ->back()
                        ->withErrors(['error' => 'Er ging iets mis! Contacteer Kelvin voor meer informatie. Foutcode: ' . $exception->getCode()]);
        }

        Log::channel('user_activity')->info('Member '. $validated['name'] . ' has been updated by '. auth()->user()->name);

        // Flush cache
        Cache::flush();

        return redirect(route('members.index'))->with('success', 'Het lid is bewerkt!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author AutiCodes
     * @param int $id the id of the member
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        // Find member by $id
        $member = Member::find($id);

        if (!$member) {
            return redirect(route('members.index'))->with('error', 'Lid niet gevonden!');
        }

        try {
            // Put Member om non active to hide it from the member list
            $member->update([
                'club_status' => ClubStatus::REMOVED_MEMBER->value,
            ]);

            // Delete user in Wordpress
            UserSync::deleteUser($member->name);
        } 
        catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()
                        ->back()
                        ->withErrors(['error' => 'Er ging iets mis! Contacteer Kelvin voor meer informatie. Foutcode:' . $exception->getMessage()]);
        }

        Log::channel('user_activity')->warning('Member '. $member->name . ' has been removed by ' . auth()->user()->name);

        // Flush cache
        Cache::flush();

        return redirect(route('members.index'))->with('success', 'Het lid is verwijderd! Hij staat nog in de database maar is op non actief gezet. Ook is hij verwijderd uit de database van WP.');
    }

    /**
     * Export members to PDF
     *
     * @author AutiCodes
     * @return PDF
    */
    public function exportPDF()
    {
        $members = Member::where('club_status', '!=', ClubStatus::REMOVED_MEMBER->value)->get();
        $pdf = PDF::loadView('members::pages.export_members_pdf.blade.php', compact('members'));

        return $pdf->stream('vluchten.pdf');
    }

    public function testing()
    {
        $member = Member::find(1);

        event(new NewMember($member));

        return 'boobs';
    }
}