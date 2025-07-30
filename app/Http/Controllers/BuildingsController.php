<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Faculty;
use Illuminate\Http\Request;

class BuildingsController extends Controller
{
    public function index(){
        $data = [];
        // $data = Faculty::where('user_id', session('school_id'))
        //         // ->with('addedBy')
        //         ->with('batches.classes.sections')
        //         ->orderBy('id','desc')
        //         ->get()
        //         ->toArray();
                // dd($data);
        return view('admin.buildings.index', compact('data'));
    }

    public function addElement(Request $request)
    {
        $data = $request->input('data');
        
        // Determine the type and perform appropriate action
        switch ($data['type']) {
            case 'building':
                return $this->addBuilding($data['title']);
            case 'room':
                // dd($data);
                return $this->addRoom($data);
            case 'row':
                // dd($data);
                return $this->addRow($data);
            case 'bench':
                return $this->addBench($data);
            case 'bench-edit':
                return $this->editBench($data);
            default:
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid type provided.'
                ], 400);
        }
    }

    // Add building method
    private function addBuilding($title)
    {
        // return response()->json([
        //     'status' => 'error',
        //     'message' => 'asd'
        // ], 400);
        try {
            $building = Building::create([
                'name' => $title,
                'rooms' => json_encode([]),  // Initialize rooms as an empty array
                'user_id' => session('school_id'),
                'added_by' => auth()->id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => "{$building->name} created.",
                'id' => $building->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Add room method
    private function addRoom($data)
    {
        try {
            // Retrieve building and room data
            $building = Building::findOrFail($data['building_id']);
            $roomName = $data['title'];

            // Initialize room data
            $roomData = [
                'name' => $roomName,
                'selected_type' => 'total', // Default type is 'total'
                'total' => [
                    'benches' => 0,
                    'seats' => 0
                ],
                'individual' => []
            ];

            // Add the room to the building's rooms data
            $rooms = json_decode($building->rooms, true) ?: [];
            $rooms[] = $roomData;
            $building->rooms = json_encode($rooms);
            $building->save();

            $roomIndex = count($rooms) - 1;  // Since the room was added to the end, its index is the last element

            return response()->json([
                'status' => 'success',
                'message' => "Room {$roomName} added to building {$building->name}.",
                'id' => $roomIndex
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Private function to add a row to a room
    private function addRow($data)
    {
        try {
            // Retrieve building and room data
            $building = Building::findOrFail($data['building_id']);
            $roomId = $data['room_id'];

            // Initialize the row data
            $rowData = [
                'name' => $data['title'], // Row name from the request
                'bench' => [] // Start with an empty bench array
            ];

            // Decode the rooms data to find the room
            $roomsData = json_decode($building->rooms, true);

            // Check if the room exists
            if (!isset($roomsData[$roomId])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Room not found.'
                ], 400);
            }

            // Add the new row to the room's 'individual' array
            $roomsData[$roomId]['individual'][] = $rowData;

            // Get the index of the new row
            $rowIndex = count($roomsData[$roomId]['individual']) - 1;  // Index of the newly added row

            // Save the updated rooms data back to the building
            $building->rooms = json_encode($roomsData);
            $building->save();

            // Return the success response
            return response()->json([
                'status' => 'success',
                'message' => "Row added to {$roomsData[$roomId]['name']}.",
                'room_id' => $roomId, // Return the room_id and row data
                'id' => $rowIndex,  // Return the index of the new row
                'row_data' => $rowData  // Optionally return the new row data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }


    // Add bench method
    private function addBench($data)
    {
        try {
            // Find the building by building_id from the passed data
            $building = Building::findOrFail($data['building_id']);

            // Decode the rooms data from JSON format
            $roomsData = json_decode($building->rooms, true);

            // Check if the room exists using the room_id index
            if (!isset($roomsData[$data['room_id']])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Room not found in the building.'
                ], 400);
            }

            // Retrieve the room data directly
            $roomDataReference = &$roomsData[$data['room_id']];

            // Check if the room name matches the provided room_name
            if ($roomDataReference['name'] !== $data['room_name']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Room name does not match the provided room data.'
                ], 400);
            }

            // If selected_type is 'individual'
            if ($data['selected_type'] == 'individual') {
                // Check if the row exists using the row_id index
                // $rowFound = false;
                $row = &$roomDataReference['individual'][$data['row_id']];

                if ($row['name'] !== $data['row_name']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Row name does not match the provided row data.'
                    ], 400);
                }
                $row['bench'][] = [
                    'name' => $data['title'],
                    'seats' => 0
                ];

                // dd($row);
                // foreach ($roomDataReference['individual'] as &$row) {
                //     if ($row['name'] === $data['row_name'] && $row['id'] === $data['row_id']) {
                //         $rowFound = true;
                //         // Add the bench to the row
                //         $row['bench'][] = [
                //             'name' => $data['title'],
                //             'seats' => $data['seats']
                //         ];
                //         break;
                //     }
                // }

                // if (!$rowFound) {
                //     return response()->json([
                //         'status' => 'error',
                //         'message' => 'Row not found in the room.'
                //     ], 400);
                // }

                // Get the index of the bench added
                $benchIndex = count($row['bench']) - 1;

                // Update the rooms data in the building
                $building->rooms = json_encode($roomsData);
                $building->save();

                return response()->json([
                    'status' => 'success',
                    'message' => "Bench data added to row {$data['row_name']} in room {$data['room_name']}.",
                    'id' => $benchIndex // Send the index of the added bench
                ]);
            } else {
                // If selected_type is 'total', we add benches to the 'total' section
                $roomDataReference['total']['benches'] += $data['total_benches'];  // Increment total benches
                $roomDataReference['total']['seats'] += $data['total_seats']; // Add seats

                // Update the rooms data in the building
                $building->rooms = json_encode($roomsData);
                $building->save();

                return response()->json([
                    'status' => 'success',
                    'message' => "Total bench data added to room {$roomDataReference['name']}.",
                    'bench_index' => '' // Empty string for non-individual type
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Add bench method
    private function editBench($data)
    {
        try {
            // Find the building by building_id from the passed data
            $building = Building::findOrFail($data['building_id']);

            // Decode the rooms data from JSON format
            $roomsData = json_decode($building->rooms, true);

            // Check if the room exists using the room_id index
            if (!isset($roomsData[$data['room_id']])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Room not found in the building.'
                ], 400);
            }

            // Retrieve the room data directly
            $roomDataReference = &$roomsData[$data['room_id']];

            // Check if the room name matches the provided room_name
            if ($roomDataReference['name'] !== $data['room_name']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Room name does not match the provided room data.'
                ], 400);
            }

            // If selected_type is 'individual'
            if ($data['selected_type'] == 'individual') {
                // Check if the row exists using the row_id index
                // $rowFound = false;
                $row = &$roomDataReference['individual'][$data['row_id']];

                if ($row['name'] !== $data['row_name']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Row name does not match the provided row data.'
                    ], 400);
                }

                $bench = &$row['bench'][$data['bench_id']];
                $bench['seats'] = $data['seats_value'];
                // $row['bench'][] = [
                //     'name' => $data['title'],
                //     'seats' => $data['seats_value']
                // ];

                // Get the index of the bench added
                $benchIndex = count($row['bench']) - 1;

                // Update the rooms data in the building
                $building->rooms = json_encode($roomsData);
                $building->save();

                return response()->json([
                    'status' => 'success',
                    'message' => "Bench data added to row {$data['row_name']} in room {$data['room_name']}.",
                    'id' => $benchIndex // Send the index of the added bench
                ]);
            } else {
                // If selected_type is 'total', we add benches to the 'total' section
                $roomDataReference['total']['benches'] += $data['total_benches'];  // Increment total benches
                $roomDataReference['total']['seats'] += $data['total_seats']; // Add seats

                // Update the rooms data in the building
                $building->rooms = json_encode($roomsData);
                $building->save();

                return response()->json([
                    'status' => 'success',
                    'message' => "Total bench data added to room {$roomDataReference['name']}.",
                    'bench_index' => '' // Empty string for non-individual type
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function deleteElement(Request $request)
    {
        $data = $request->input('data'); // The data should include 'type' and the relevant index IDs
        $type = $data['type']; // The type can be 'building', 'room', 'row', or 'bench'

        try {
            switch ($type) {
                case 'building':
                    return $this->deleteBuilding($data['building_id']);
                case 'room':
                    return $this->deleteRoom($data['building_id'], $data['room_id']);
                case 'row':
                    return $this->deleteRow($data['building_id'], $data['room_index'], $data['row_index']);
                case 'bench':
                    return $this->deleteBench($data['building_id'], $data['room_index'], $data['row_index'], $data['bench_index']);
                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid type provided.'
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Private function to delete building
    private function deleteBuilding($building_id)
    {
        $building = Building::findOrFail($building_id);
        $building->delete(); // Soft delete the building
        return response()->json([
            'status' => 'success',
            'message' => "Building with ID {$building_id} has been deleted."
        ]);
    }

    // Private function to delete room
    private function deleteRoom($building_id, $room_index)
    {
        $building = Building::findOrFail($building_id);
        $roomsData = json_decode($building->rooms, true);

        if (!isset($roomsData[$room_index])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Room not found.'
            ], 400);
        }

        // Remove the room
        unset($roomsData[$room_index]);

        // Reindex rooms after removal
        $roomsData = array_values($roomsData);

        // Save the updated rooms data back to the building
        $building->rooms = json_encode($roomsData);
        $building->save();

        return response()->json([
            'status' => 'success',
            'message' => "Room at index {$room_index} deleted from building."
        ]);
    }

    // Private function to delete row
    private function deleteRow($building_id, $room_index, $row_index)
    {
        $building = Building::findOrFail($building_id);
        $roomsData = json_decode($building->rooms, true);

        if (!isset($roomsData[$room_index])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Room not found.'
            ], 400);
        }

        if (!isset($roomsData[$room_index]['individual'][$row_index])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Row not found.'
            ], 400);
        }

        // Remove the row
        unset($roomsData[$room_index]['individual'][$row_index]);

        // Reindex the rows after removal
        $roomsData[$room_index]['individual'] = array_values($roomsData[$room_index]['individual']);

        // Save the updated rooms data back to the building
        $building->rooms = json_encode($roomsData);
        $building->save();

        return response()->json([
            'status' => 'success',
            'message' => "Row at index {$row_index} in room {$room_index} deleted."
        ]);
    }

    // Private function to delete bench
    private function deleteBench($building_id, $room_index, $row_index, $bench_index)
    {
        $building = Building::findOrFail($building_id);
        $roomsData = json_decode($building->rooms, true);

        if (!isset($roomsData[$room_index])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Room not found.'
            ], 400);
        }

        if (!isset($roomsData[$room_index]['individual'][$row_index])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Row not found.'
            ], 400);
        }

        if (!isset($roomsData[$room_index]['individual'][$row_index]['bench'][$bench_index])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bench not found.'
            ], 400);
        }

        // Remove the bench
        unset($roomsData[$room_index]['individual'][$row_index]['bench'][$bench_index]);

        // Reindex the bench array after removal
        $roomsData[$room_index]['individual'][$row_index]['bench'] = array_values($roomsData[$room_index]['individual'][$row_index]['bench']);

        // Save the updated rooms data back to the building
        $building->rooms = json_encode($roomsData);
        $building->save();

        return response()->json([
            'status' => 'success',
            'message' => "Bench at index {$bench_index} in row {$row_index}, room {$room_index} deleted."
        ]);
    }


}
