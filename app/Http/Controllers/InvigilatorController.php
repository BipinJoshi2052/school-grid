<?php

namespace App\Http\Controllers;

use App\Helpers\HelperFile;
use App\Models\Building;
use App\Models\InvigilatorPlanDetail;
use App\Models\SeatPlan;
use App\Models\Staff;
use Illuminate\Http\Request;

class InvigilatorController extends Controller
{

    public function seatInvigilatorLayout($id)
    {
        // Step 1: Get seat plan data
        $data['seat_plan'] = SeatPlan::select('id', 'title', 'unassigned_students', 'unassigned_staffs')
            ->where('id', $id)
            ->first()
            ->toArray();

        // Step 2: Get distinct building IDs from seat_plan_details
        $buildingIds = InvigilatorPlanDetail::where('seat_plan_id', $id)
            ->distinct()
            ->pluck('building_id');

        // Step 3: Get building information from the buildings table
        $buildings = Building::whereIn('id', $buildingIds)
            ->get();
        $data['buildings'] = $buildings->toArray();

        // Step 4: Get all seat plan details for the given seat_plan_id
        $seatPlanDetails = InvigilatorPlanDetail::where('seat_plan_id', $id)
            ->get();

        // Step 5: Group by building_id and room, then map staff details
        $groupedStaff = [];

        foreach ($seatPlanDetails as $detail) {
            // Check if staff exists for the current detail
            // $staff = $detail->staff;
            // if ($staff) {
                // Initialize the group if it doesn't exist
                if (!isset($groupedStaff[$detail->building_id])) {
                    $groupedStaff[$detail->building_id] = [];
                }

                // Initialize the room index if it doesn't exist
                if (!isset($groupedStaff[$detail->building_id][$detail->room])) {
                    $groupedStaff[$detail->building_id][$detail->room] = [];
                }

                // Add the staff details
                $groupedStaff[$detail->building_id][$detail->room][$detail->id] = [
                    'id' => $detail->id,
                    'staff_id' => $detail->staff_id,
                    'name' => $detail->staff_name,
                    'department' => $detail->staff_department,
                    'position' => $detail->staff_position
                ];
            // }
        }

        // Step 6: Handle unassigned staff
        // if (!empty($data['seat_plan']['unassigned_staffs'])) {
        //     $unassignedStaffIds = json_decode($data['seat_plan']['unassigned_staffs'], true);

        //     // Fetch details of unassigned staff in one query
        //     $unassignedStaffs = Staff::whereIn('id', $unassignedStaffIds)
        //         ->with('user') // Eager load the user relation
        //         ->get();

        //     // Store the staff details in an array
        //     $unassignedStaffDetails = [];
        //     foreach ($unassignedStaffs as $staff) {
        //         $unassignedStaffDetails[$staff->id] = [
        //             'id' => $staff->id,
        //             'name' => $staff->name,
        //             'department' => $staff->department->title,
        //             'position' => $staff->position->title,
        //             'avatar' => $staff->user ? $staff->user->avatar : null // Get avatar from the user relation
        //         ];
        //     }

        //     // Add unassigned staff details to data
        //     $data['unassigned_staffs'] = $unassignedStaffDetails;
        // } else {
        //     $data['unassigned_staffs'] = [];
        // }

        // Step 7: Return the grouped staff data
        $data['grouped_staff'] = $groupedStaff;
        // dd($data);
        $data['configs'] = HelperFile::getSchoolConfigs();
        $data['seat_plan_id'] = $id;

        return view('admin.invigilator-plan.invig-show', compact('data'));
        
        // For debugging purposes
    }

    public function removeInvigilator(Request $request){
        $id = $request->id;
        try {
            $invigilator = InvigilatorPlanDetail::findOrFail($id);
            
            // Store the staff_id before updating
            $staffId = $invigilator->staff_id;
            $seatPlanId = $invigilator->seat_plan_id;
            
            // Update the unassigned_staffs in seat_plans
            $seatPlan = SeatPlan::findOrFail($seatPlanId);
            $unassignedStaffs = $seatPlan->unassigned_staffs;

            // Handle different cases for unassigned_staffs
            if (is_null($unassignedStaffs)) {
                $unassignedStaffs = [$staffId];
            } elseif (is_array($unassignedStaffs)) {
                if (!in_array($staffId, $unassignedStaffs)) {
                    $unassignedStaffs[] = $staffId;
                }
            } else {
                // In case unassigned_staffs is stored as a JSON string
                $unassignedStaffs = json_decode($unassignedStaffs, true) ?? [];
                if (!in_array($staffId, $unassignedStaffs)) {
                    $unassignedStaffs[] = $staffId;
                }
            }

            // Update the seat_plans table with the new unassigned_staffs array
            $seatPlan->update([
                'unassigned_staffs' => $unassignedStaffs
            ]);
            // Update the invigilator plan details to null
            $invigilator->update([
                'staff_id' => null,
                'staff_name' => null,
                'staff_department' => null,
                'staff_position' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Invigilator removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing invigilator: ' . $e->getMessage()
            ], 500);
        }
    }

    public function loadInvigilator(Request $request)
    {
        $id = $request->id;
        $roomIndex = $request->roomIndex;
        $buildingId = $request->buildingId;
        $seatPlanId = $request->seatPlanId;
        $row = $request->row;

        try {
            // Find the invigilator plan detail
            // $invigilator = InvigilatorPlanDetail::findOrFail($id);
            // $seatPlanId = $invigilator->seat_plan_id;
            // $seatPlanId = $seat_plan_id;

            // Find the seat plan
            $seatPlan = SeatPlan::findOrFail($seatPlanId);
            $unassignedStaffs = $seatPlan->unassigned_staffs;

            $staffs = [];
            if (!is_null($unassignedStaffs) && !empty($unassignedStaffs)) {
                $unassignedStaffs = json_decode($unassignedStaffs,true);
                // Fetch staff details with department and position relationships
                $staffs = Staff::whereIn('id', $unassignedStaffs)
                    ->with(['department', 'position'])
                    ->get()->toArray();
            }
            // dd($staffs);
            // Return the partial view with staff data
            return view('admin.invigilator-plan.load-staffs-partial', compact(
                'id',
                'staffs', 
                'seatPlanId',
                'buildingId', 
                'roomIndex', 
                'row', 
            ));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading invigilators: ' . $e->getMessage()
            ], 500);
        }
    }

    public function replaceInvigilator(Request $request)
    {
        try {
            $seatPlaDetailId = $request->id;
            $staffId = $request->staff_id;
            $buildingId = $request->building_id;
            $roomIndex = $request->room_index;
            $seatPlanId = $request->seat_plan_id;
            // dd($request->all());
            // Find the invigilator plan detail
            $invigilator = InvigilatorPlanDetail::where([
                'id' => $seatPlaDetailId
            ])->first();
            // Fetch the new staff details
            $staff = Staff::with(['department', 'position'])->findOrFail($staffId);
            $oldStaffId = null;

            if($invigilator){
                // Save the current staff_id before updating
                $oldStaffId = $invigilator->staff_id;
                // Update the invigilator plan details
                $invigilator->update([
                    'staff_id' => $staff->id,
                    'staff_name' => $staff->name,
                    'staff_department' => $staff->department ? $staff->department->title : null,
                    'staff_position' => $staff->position ? $staff->position->title : null,
                ]);
            }else{
                $invigilator = InvigilatorPlanDetail::create([
                    'seat_plan_id' => $seatPlanId,
                    'building_id' => $buildingId,
                    'room' => $roomIndex,
                    'staff_id' => $staff->id,
                    'staff_name' => $staff->name ? $staff->name : null,
                    'staff_department' => $staff->department ? $staff->department->title : null,
                    'staff_position' => $staff->position ? $staff->position->title : null,
                ]);
            }        

            // Remove the old staff and add the new staff in unassigned_staffs in seat_plans
            $seatPlan = SeatPlan::findOrFail($seatPlanId);
            $unassignedStaffs = json_decode($seatPlan->unassigned_staffs,true) ?? [];

            // Remove the staff that is being replaced (if any)
            if (is_array($unassignedStaffs)) {
                // Remove the new staff ID if it exists
                $unassignedStaffs = array_filter($unassignedStaffs, fn($id) => $id != $staff->id);

                // Add the old staff ID back into the unassigned staff list
                if ($oldStaffId) {
                    $unassignedStaffs[] = $oldStaffId;
                }

                $seatPlan->update(['unassigned_staffs' => array_values($unassignedStaffs)]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Invigilator replaced successfully',
                'staff' => [
                    'seat_plan_detail_id' => $invigilator->id,
                    'staff_id' => $staff->id,
                    'staff_name' => $staff->name,
                    'staff_department' => $staff->department ? $staff->department->title : null,
                    'staff_position' => $staff->position ? $staff->position->title : null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error replacing invigilator: ' . $e->getMessage()
            ], 500);
        }
    }

}
