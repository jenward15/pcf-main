<?php

namespace App\Http\Controllers;

use App\Models\PCFRequest;
use App\Models\PCFList;
use Illuminate\Http\Request;
use Alert;
use Validator;
use Yajra\Datatables\Datatables;

class PCFRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $getPCFRequest = PCFRequest::orderBy('pcf_no')->get();

            return Datatables::of($getPCFRequest)
                ->addIndexColumn()
                ->addColumn('pcf_no', function ($data) {
                    return $data->pcf_no;
                })
                ->addColumn('date', function ($data) {
                    return $data->date;
                })
                ->addColumn('institution', function ($data) {
                    return $data->institution;
                })
                ->addColumn('psr', function ($data) {
                    return $data->psr;
                })
                ->addColumn('profit', function ($data) {
                    return $data->profit;
                })
                ->addColumn('profit_rate', function ($data) {
                    return $data->profit_rate;
                })
                ->addColumn('actions', function ($data) {

                    if ($data->status == 0) {

                    return
                        ' 
                    <td style="text-align: center; vertical-align: middle">
                        <a href="#" class="badge badge-info" data-toggle="modal"
                            data-id="'.$data->id .'"
                            data-name="'.$data->name .'"
                            data-description="'.$data->description .'"
                            data-target="#editSpecimenTypesModal"
                            onclick="editSpecimenTypes($(this))"><i
                                class="far fa-eye"></i> 
                            View List</a>
                            <a href="#" class="badge badge-success"
                            data-id="' . $data->id . '"
                            onclick="enableSpecimen($(this))"><i
                                class="fas fa-plus"></i> 
                            Add Item</a>
                            <a href="#" class="badge badge-success"
                                data-id="' . $data->id . '"
                                onclick="ApproveRequest($(this))"><i
                                    class="fas fa-check"></i> 
                            Approve</a>
                    </td>
                    ';
                    }

                    return
                        ' 
                    <td style="text-align: center; vertical-align: middle">
                        <a href="#" class="badge badge-info" data-toggle="modal"
                            data-id="'.$data->id .'"
                            data-name="'.$data->name .'"
                            data-description="'.$data->description .'"
                            data-target="#editSpecimenTypesModal"
                            onclick="editSpecimenTypes($(this))"><i
                                class="far fa-eye"></i> 
                            View List</a>
                            <a href="#" class="badge badge-success"
                            data-id="' . $data->id . '"
                            onclick="enableSpecimen($(this))"><i
                                class="fas fa-plus"></i> 
                            Add Item</a>
                            <a href="#" class="badge badge-danger"
                                data-id="' . $data->id . '"
                                onclick="DisApproveRequest($(this))"><i
                                    class="fas fa-times"></i> 
                            Dis-Approve</a>
                    </td>
                    ';
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('PCF.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'pcf_no'   => 'required|string',
            'date'   => 'required|string',
            'institution'   => 'nullable|string',
            'duration_contract'   => 'required|string',
            'date_bidding'   => 'required|string',
            'bid_docs_price'   => 'required|string',
            'psr'   => 'required|string',
            'manager'   => 'required|string',
            'annual_profit'   => 'required|string',
            'annual_profit_rate'   => 'nullable|string'
        ]);

        if ($validator->fails()) {
            Alert::error('Invalid Data', $validator->errors()->first()); 
            return view('PCF.index');
        }

        $savePcfRequest = new PCFRequest;
        $savePcfRequest->pcf_no = $request->pcf_no;
        $savePcfRequest->date = $request->date;
        $savePcfRequest->institution = $request->institution;
        $savePcfRequest->duration = $request->duration_contract;
        $savePcfRequest->date_biding = $request->date_bidding;
        $savePcfRequest->bid_docs_price = $request->bid_docs_price;
        $savePcfRequest->psr = $request->psr;
        $savePcfRequest->manager = $request->manager;
        $savePcfRequest->profit = $request->annual_profit;
        $savePcfRequest->profit_rate = $request->annual_profit_rate;
        $savePcfRequest->created_by = \Auth::user()->id;
        $savePcfRequest->save();

        Alert::success('PCF Request Details', 'Added successfully'); 

        return view('PCF.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PCFRequest  $pCFRequest
     * @return \Illuminate\Http\Response
     */
    public function show(PCFRequest $pCFRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PCFRequest  $pCFRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(PCFRequest $pCFRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PCFRequest  $pCFRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PCFRequest $PCFRequest)
    {
        $validator = Validator::make($request->all(), [ //ignore this line error it still works
            'specimen_type_id' => 'required|numeric',
            'specimen_type' => 'required|string|unique:p_c_f_s,name,' . $request->specimen_type_id,
            'description'   => 'nullable|string'
        ]);

        if ($validator->fails()) {
            Alert::error('Invalid Data', $validator->errors()->first()); //ignore the error it still works
            return redirect()->route('PCF');
        }

        $updateSpecimenTypes = PCFRequest::findOrFail($request->specimen_type_id);
        $updateSpecimenTypes->name = $request->specimen_type;
        $updateSpecimenTypes->description = $request->description;
        $updateSpecimenTypes->save();

        Alert::success('Specimen Types', 'Updated successfully'); //ignore the error it still works

        return redirect()->route('PCF');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PCFRequest  $pCFRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(PCFRequest $pCFRequest)
    {
        //
    }

    public function ApproveRequest($id)
    {

        if (!empty($id)) {
            $getRequestSataus = PCFRequest::find($id);
            $getRequestSataus->status = 1;
            $getRequestSataus->save();

            return response()->json(['success' => 'success'], 200);
        }

        return response()->json(['error' => 'invalid'], 401);
    }

    public function DisapproveRequest($id)
    {

        if (!empty($id)) {
            $getRequestSataus = PCFRequest::find($id);
            $getRequestSataus->status = 0;
            $getRequestSataus->save();

            return response()->json(['success' => 'success'], 200);
        }

        return response()->json(['error' => 'invalid'], 401);
    }
}
