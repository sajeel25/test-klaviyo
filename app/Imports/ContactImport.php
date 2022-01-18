<?php

namespace App\Imports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Contact;

class ContactImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $route;
    protected $error_html;
    protected $user;
   

    public function __construct()
    {
        $this->route = route('contacts');
        $this->user = Auth::user();
        $this->error_html = $this->error_html.'
            <!DOCTYPE html>
            <html>
            <head>
            <title>Wrong File</title>
            </head>
            <body>
            <h1 style="color: #b90700">Error!</h1>
            <h2>The File you uploaded did not match the fields of a contact</h2>
            <p>The file you uploaded had the wrong columns for a contact. <a href=" '. $this->route .' ">click here</a> to try again</p>
            
            </body>
            </html>
        ';

    }
    public function collection(Collection $rows)
    {
        foreach($rows as $row){
        if($row->filter()->isNotEmpty()){
            if(($row['first_name'] != '') && ($row['email'] != '') && ($row['phone'] != '')){
                // check if contact already exists //
                $contact = Contact::where('user_id', $this->user->id)->where('email', $row['email'])->first();
                if(!$contact){
                    $contact = new Contact;
                }

                $contact->user_id = $this->user->id;
                $contact->first_name = $row['first_name'];
                $contact->email = $row['email'];
                $contact->phone = $row['phone'];
                $contact->save();
                
            }
             
            } 
        }
    }

    public function headingRow():int
    {
        // TODO: Implement startRow() method.
        return 1;
    }


}
