<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Klaviyo\Klaviyo as Klaviyo;
use Klaviyo\Model\EventModel as KlaviyoEvent;
use Maatwebsite\Excel\Excel;
use App\Imports\ContactImport;


#Models
use App\Models\Contact;

class ContactController extends Controller
{

	public function index(){
		$contacts = Contact::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
		return view('contact', compact('contacts'));
	}

    public function create(Request $request){
    	// check if email exists
    	$contact = Contact::where(['email' => $request->email, 'user_id' => auth()->user()->id])->first();
    	if(!$contact){
    		$contact =  new Contact;
    	}
    	$contact->user_id = auth()->user()->id;
    	$contact->first_name = $request->first_name;
    	$contact->email = $request->email;
    	$contact->phone = $request->phone;
    	$contact->save();

    	return redirect()->back()->with('successmsg', 'Contact successfully created.');
    }

    public function uploadSampleFile(Request $request){
        $validator = $request->validate([
            'data_file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try{
        	config(['excel.import.startRow' => 1 ]);
            \Excel::import(new ContactImport(), $request->data_file);
        }catch(\Exception $ex){
            return redirect()->back()->with('errormsg' , $ex->getMessage());
        }

        return redirect()->route('contacts')->with('successmsg', 'Contacts successfully imported.');
    }

    public function clickEvent(){

        $client = new Klaviyo('pk_9d624182dee30ae5c47955fdc2cbc113da', 'XmSfUz');

		$event = new KlaviyoEvent(
		    array(
		        'event' => 'Clicked a button',
		        'customer_properties' => array(
		            '$email' => 'sajeel.sheikh2015@gmail.com'
		        ),
		        'properties' => array(
		            'Date' => now()
		        )
		    )
		);

		$client->publicAPI->track( $event, true );

		return response()->json(['success' => true]);
    }
}
