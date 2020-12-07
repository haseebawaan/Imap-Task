<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\ClientManager;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function imapFetch(Request $request)
    {
        $client = Client::make([
            'host'          => 'imap.gmail.com',
            'port'          => 993,
            'encryption'    => 'ssl',
            'validate_cert' => false,
            'username'      => $request->email,
            'password'      => $request->password,
            'protocol'      => 'imap'
        ]);
        /* Alternative by using the Facade
        cksvtpoqvitlnlpk
        */
        // $client = Client::account('default');
        //Connect to the IMAP Server
       try
       {
        $client->connect();
           $subject = array();
           $date = array();
           $body = array(); 
           $record = array();
            //Get all Mailboxes
            /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
            $folders = $client->getFolders();
            //Loop through every Mailbox
            /** @var \Webklex\PHPIMAP\Folder $folder */
            foreach($folders as $folder){
                //Get all Messages of the current Mailbox $folder
                /** @var \Webklex\PHPIMAP\Support\MessageCollection $messages */
                $messages = $folder->messages()->all()->get();
            //   dd($messages);
                /** @var \Webklex\PHPIMAP\Message $message */
                foreach($messages as $message){
                    array_push($record,[
                        'subject' => $message->getSubject(),
                        'date' => $message->getDate()->toDateString(),
                        'body' => $message->getHTMLBody(),
                    ]);
                }
            }
         
            // dd($record);
            return view('email-list')->with(['record' => $record, 'password' => $request->password]);
        }
        catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function emailList(Request $request)
    {
        
        $client = Client::make([
            'host'          => 'imap.gmail.com',
            'port'          => 993,
            'encryption'    => 'ssl',
            'validate_cert' => false,
            'username'      => $request->email,
            'password'      => $request->hidden,
            'protocol'      => 'imap'
        ]);
        /* Alternative by using the Facade
        cksvtpoqvitlnlpk
        */
        // $client = Client::account('default');
        //Connect to the IMAP Server
       try
       {
        $client->connect();
           $subject = array();
           $date = array();
           $body = array(); 
           $record = array();
            //Get all Mailboxes
            /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
            $folders = $client->getFolders();
            //Loop through every Mailbox
            /** @var \Webklex\PHPIMAP\Folder $folder */
            foreach($folders as $folder){
            
                //Get all Messages of the current Mailbox $folder
                /** @var \Webklex\PHPIMAP\Support\MessageCollection $messages */
                $messages = $folder->query()->to($request->search)->get();
                if($messages->total() == null){
                    $messages = $folder->query()->from($request->search)->get();
                    if($messages->total() == null){
                        $messages = $messages;
                    }
                }
                /** @var \Webklex\PHPIMAP\Message $message */
                foreach($messages as $message){
                    array_push($record,[
                        'subject' => $message->getSubject(),
                        'date' => $message->getDate()->toDateString(),
                        'body' => $message->getHTMLBody(),
                    ]);
                }
            }
            
            
            // dd($record);
            return view('email-list')->with(['record' => $record, 'password' => $request->hidden]);
        }
        catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
