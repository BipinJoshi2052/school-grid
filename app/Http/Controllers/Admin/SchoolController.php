<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HelperFile;
use App\Http\Controllers\Controller;
use App\Models\InstitutionDetail;
use App\Models\User;
use App\Models\UserType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('super-admin.schools.index');
    }

    public function listPartial(Request $request) {
        // Get the search term from the request
        $searchTerm = $request->get('search')['value'] ?? '';  // DataTables sends the search term in search[value]

        // Pagination parameters
        $page = $request->get('page', 1);  // Default page is 1
        $perPage = $request->get('pageLength', 10);  // Use the pageLength parameter to get the number of items per page
        
        $user_type_id_of_school = UserType::where('name', 'client')->first();
        // Build the query to filter data
        $query = User::where('user_type_id', $user_type_id_of_school->id)
                        ->with('details')
                        ->orderBy('id', 'desc');

        // Apply the search filter if a search term is provided
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('namex', 'like', '%' . $searchTerm . '%')
                ->orwhere('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('name', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('details', function ($query) use ($searchTerm) {
                    $query->where('client_id', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Paginate the results
        $users = $query->paginate($perPage);

        // Return the response in JSON format for DataTables
        return response()->json([
            'draw' => $request->get('draw'),
            'recordsTotal' => $users->total(),
            'recordsFiltered' => $users->total(), // Since we're not using a separate filtered count, this is the same as recordsTotal
            'data' => $users->items()  // Return the paginated data
        ]);
    }

    
    public function createPartial(){
        $data = [];
        return view('super-admin.schools.create-partial',compact('data'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        return view('super-admin.schools.create-partial', compact('data'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'email|unique:users,email',
            'phone' => 'nullable|string|min:10',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
            // 'department_id' => 'required|exists:departments,id',
            // 'position_id' => 'required|exists:positions,id',
        // dd('here');
        try{
            $userCount = User::where('user_type_id', 2)->count(); // Get the count of existing users

            // Generate client_id based on the count
            $clientId = 'CLNT-' . now()->year . '-' . str_pad($userCount + 1, 5, '0', STR_PAD_LEFT); // Example: CLNT-2025-00001

            // Create the user first
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('secret'),  // Set password as 'secret'
                'phone' => $request->phone,
                // 'avatar' => HelperFile::uploadFile($request,'avatars/staff'), // Handle avatar upload
                'user_type_id' => 2, // Staff
                'added_by' => auth()->id(),
            ]);

            // Upload the avatar and update the user record
            $avatar = HelperFile::uploadFileSuperAdmin($request, 'avatars', $user->id);  // Pass the user ID into the helper

            // Update the user with the avatar path
            $user->update([
                'avatar' => $avatar,  // Save the avatar URL or path
            ]);
            // Create the staff record
            $staff = InstitutionDetail::create([
                'user_id' => $user->id,
                'client_id' => $clientId,
                'institution_name' => $request->name,
                'registration_id' => $request->registration_id,
                'expiration_date' => $request->expiration_date
            ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => $e->getMessage()]);    
        }

        return response()->json(['success' => true, 'staff' => $staff]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $staff = User::findOrFail($id);
        return response()->json($staff);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $school = User::where('id',$id)
                        // ->with('details')
                        ->first();
                        // dd($school);
        return view('super-admin.schools.edit-partial', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Start a transaction for the update process
        // echo 'asdfasdfasdfasfd';
        // dd($request->all());
        DB::beginTransaction();

        try {
            // Find the staff record
            $user = User::findOrFail($id);

            // Find the associated user based on the user_id in the staff table
            $institution_details = InstitutionDetail::where('user_id',$user->id)->first();
            $old_avatar = $user->avatar;

            // Update the user's table with the new data
            // dd($this->uploadAvatar($request));
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'suspend' => $request->input('suspend'),
                'avatar' => $request->hasFile('avatar') ? HelperFile::uploadFile($request,'avatars/schools') : $user->avatar,  // Update avatar if a new one is uploaded
                'phone' => $request->input('phone'),
            ]);

            // Update the staff's table with the new data
            $institution_details->update([
                'institution_name' => $request->input('institution_name'),
                'registration_id' => $request->input('registration_id'),
                'expiration_date' => $request->input('expiration_date')
            ]);

            // Check if the staff already has an avatar, and delete it if it exists
            if ($old_avatar && file_exists(storage_path('app/public/' . $old_avatar))) {
                // Delete the old avatar image from the storage
                unlink(storage_path('app/public/' . $old_avatar));
            }

            // Commit the transaction after both user and staff updates
            DB::commit();

            // Return the updated staff record as a JSON response
            return response()->json($user);
        } catch (\Exception $e) {
            // Rollback in case of an error
            DB::rollBack();

            // Return error response
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();  // Start a transaction

        try {
            
            // Get the user associated with this staff
            $user = User::where('id',$id)->first();

            // Find the InstitutionDetail record
            $institution_details = InstitutionDetail::where('user_id',$user->id)->first();

            // Delete the user record first
            $userDeleted = $user ? $user->delete() : true;

            // Delete the staff record
            $institutionDetailsDeleted = $institution_details->delete();

            // Check if both deletions were successful
            if ($userDeleted && $institutionDetailsDeleted) {
                // Now delete the avatar image after the deletions are successful
                if ($user && $user->avatar) {
                    $avatarPath = storage_path('app/public/' . $user->avatar);  // Get the avatar path
                    if (file_exists($avatarPath)) {
                        unlink($avatarPath);  // Delete the avatar image file
                    }
                }

                DB::commit();  // Commit the transaction if everything was successful
                return response()->json(null, 204);  // Return success response
            } else {
                DB::rollBack();  // Rollback if any deletion failed
                return response()->json(['message' => 'Error deleting school'], 500);
            }
        } catch (\Exception $e) {
            // Rollback in case of any exception
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


}
