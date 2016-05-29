<?php

namespace App\Http\Controllers;

use App\QuoteRequest;
use App\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use PhpSpec\Exception\Exception;

class WorkflowController extends Controller
{
    public function index()
    {
        $message = Session::get('message');
        $quote_requests = QuoteRequest::where('customer_id', '>', 0)
            ->where('status', '>', 0)->where('status', '<', 9)->get();

        $statuses = Status::all();

        $array = array();
        $i = 0;

        foreach($quote_requests as $quote_request)
        {
            $array[$i]['quote_number'] = $quote_request->id;
            $array[$i]['title'] = $quote_request->title;
            $array[$i]['description'] = $quote_request->summary;
            $array[$i]['artwork_image'] = $quote_request->artwork_image;
            $array[$i]['customer_id'] = $quote_request->customer_id;
            if(count($quote_request->customer) > 0){
                $array[$i]['customer_name'] = $quote_request->customer->customer_name;
            }
            else {
                $array[$i]['customer_name'] = '';
            }

            $array[$i]['request_date'] = $quote_request->request_date;
            $array[$i]['expiry_date'] = $quote_request->expiry_date;
            $array[$i]['status'] = $quote_request->get_status->value;
            $array[$i]['status_id'] = $quote_request->get_status->id;

            // Quotes
            if(isset($quote_request->get_quote->id)) {
                $array[$i]['supplier_id'] = $quote_request->get_quote->supplier->id;
                $array[$i]['supplier_name'] = $quote_request->get_quote->supplier->supplier_name;
            }
            else {
                $array[$i]['supplier_id'] = '';
                $array[$i]['supplier_name'] = '';
            }

            // Jobs
            if(isset($quote_request->job->id) && ($quote_request->quote_id != 0)) {
                $array[$i]['job_number'] = $quote_request->id;
                $array[$i]['quantity'] = $quote_request->job->job_item->quantity;
                $array[$i]['total'] = $quote_request->job->job_item->total;
                $array[$i]['request_date'] = $quote_request->job->updated_at->format('d/m/Y');

                if ($array[$i]['status'] == 'New Job')
                {
                    // Working on the stages of a new job
                    $count = $quote_request->job->outside_work * 100 + $quote_request->job->design * 10 + $quote_request->job->on_proof;
                    switch ($count) {
                        case 0:
                            $array[$i]['stage'] = '';
                            break;
                        case 1:
                            $array[$i]['stage'] = '<span class="label label-danger">On Proof</span>';
                            break;
                        case 10:
                            $array[$i]['stage'] = '<span class="label label-primary">Design</span>';
                            break;
                        case 11:
                            $array[$i]['stage'] = '<span class="label label-primary">Design</span>';
                            foreach ($array[$i] as $key => $item) {
                                $array[$i + 1][$key] = $array[$i][$key];
                            }
                            $i++;
                            $array[$i]['stage'] = '<span class="label label-danger">On Proof</span>';
                            break;
                        case 100:
                            $array[$i]['stage'] = '<span class="label label-success">Outside Work</span>';
                            break;
                        case 101:
                            $array[$i]['stage'] = '<span class="label label-success">Outside Work</span>';
                            foreach ($array[$i] as $key => $item) {
                                $array[$i + 1][$key] = $array[$i][$key];
                            }
                            $i++;
                            $array[$i]['stage'] = '<span class="label label-danger">On Proof</span>';
                            break;
                        case 110:
                            $array[$i]['stage'] = '<span class="label label-success">Outside Work</span>';
                            foreach ($array[$i] as $key => $item) {
                                $array[$i + 1][$key] = $array[$i][$key];
                            }
                            $i++;
                            $array[$i]['stage'] = '<span class="label label-primary">Design</span>';
                            break;
                        case 111:
                            $array[$i]['stage'] = '<span class="label label-success">Outside Work</span>';
                            foreach ($array[$i] as $key => $item) {
                                $array[$i + 1][$key] = $array[$i][$key];
                                $array[$i + 2][$key] = $array[$i][$key];
                            }
                            $i++;
                            $array[$i]['stage'] = '<span class="label label-primary">Design</span>';
                            $i++;
                            $array[$i]['stage'] = '<span class="label label-danger">On Proof</span>';
                            break;
                    }
                }
                else
                {
                    $array[$i]['stage'] = '';
                }
            }
            else {
                $array[$i]['job_number'] = '';
                $array[$i]['quantity'] = '';
                $array[$i]['stage'] = '';
                $array[$i]['total'] = '';
            }

            $i++;
        }

        return view('workflow.index', compact('array', 'message', 'statuses'));
    }

    public function change_status($id, $status)
    {
        try {
            $quote_request = QuoteRequest::find($id);
            $old = $quote_request->status;
            $quote_request->status = $status;
            $quote_request->save();

            return [$old, $status, $id];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function send_invoice($id)
    {
        // Get data for sending invoice
        $quote_request = QuoteRequest::find($id);

        define('BASE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define ( "XRO_APP_TYPE", "Private" );
        define ( "OAUTH_CALLBACK", 'http://printflow.local:8000/' );

        /* For Demo-Company
        define ( "OAUTH_CALLBACK", 'http://printflow.local:8000/' );
        $useragent = "Demo-Printflow";

        $signatures = array (
            'consumer_key'     => 'PMNK76GMVNQLNK945E385MVIDCCMVQ',
            'shared_secret'    => 'VUQPJDKRZ1ZUPRSBBHUZ3J8DOQTQEL',
            // API versions
            'core_version' => '2.0',
            'payroll_version' => '1.0'
        );
        */

        $useragent = env('USER_AGENT');

        $signatures = array (
            'consumer_key'     => env('XERO_KEY'),
            'shared_secret'    => env('XERO_SECRET'),
            // API versions
            'core_version' => '2.0',
            'payroll_version' => '1.0'
        );

        if (XRO_APP_TYPE == "Private" || XRO_APP_TYPE == "Partner") {
            $signatures ['rsa_private_key'] = BASE_PATH . '/certs/privatekey.pem';
            $signatures ['rsa_public_key'] = BASE_PATH . '/certs/publickey.cer';
        }

        $XeroOAuth = new \XeroOAuth(array_merge(array('application_type' => XRO_APP_TYPE, 'oauth_callback' => OAUTH_CALLBACK,
            'user_agent' => $useragent), $signatures));

        $initialCheck = $XeroOAuth->diagnostics();
        $checkErrors = count($initialCheck);
        if ($checkErrors > 0)
        {
            // you could handle any config errors here, or keep on truckin if you like to live dangerously
            foreach($initialCheck as $check) {
                echo 'Error: ' . $check . PHP_EOL;
            }
        }
        else
        {
            $session = $this->persistSession(array(
                'oauth_token' => $XeroOAuth->config['consumer_key'],
                'oauth_token_secret' => $XeroOAuth->config['shared_secret'],
                'oauth_session_handle' => ''
            ));
            $oauthSession = $this->retrieveSession();

            if (isset ($oauthSession['oauth_token'])) {
                $XeroOAuth->config['access_token'] = $oauthSession['oauth_token'];
                $XeroOAuth->config['access_token_secret'] = $oauthSession['oauth_token_secret'];

                if (isset($_REQUEST)) {
                    if(!isset($_REQUEST['where'])) $_REQUEST['where'] = "";
                }

                if (isset($_REQUEST['wipe'])) {
                    session_destroy();
                    header("Location: {$here}");

                    // already got some credentials stored?
                } elseif (isset($_REQUEST['refresh'])) {
                    $response = $XeroOAuth->refreshToken($oauthSession['oauth_token'], $oauthSession['oauth_session_handle']);
                    if ($XeroOAuth->response['code'] == 200) {
                        $session = $this->persistSession($response);
                        $oauthSession = $this->retrieveSession();
                    } else {
                        $this->outputError($XeroOAuth);
                        if ($XeroOAuth->response['helper'] == "TokenExpired") $XeroOAuth->refreshToken($oauthSession['oauth_token'], $oauthSession['session_handle']);
                    }

                } elseif (isset($oauthSession['oauth_token']) && isset($_REQUEST)) {

                    $XeroOAuth->config['access_token'] = $oauthSession['oauth_token'];
                    $XeroOAuth->config['access_token_secret'] = $oauthSession['oauth_token_secret'];
                    $XeroOAuth->config['session_handle'] = $oauthSession['oauth_session_handle'];

                    $xml = '<Invoices>
							  <Invoice>
								<Type>ACCREC</Type>
								<Contact>
								  <Name>' . $quote_request->customer->customer_name . '</Name>
								</Contact>
								<Status>DRAFT</Status>
								<Date>' . Carbon::now()->format('Y-m-d') . '</Date>
								<DueDate>' . Carbon::now()->addWeeks(2)->format('Y-m-d') . '</DueDate>
								<Reference>'. $quote_request->ref .'-'. $id .'</Reference>
								<LineAmountTypes>Exclusive</LineAmountTypes>
								<LineItems>
								  <LineItem>
									<JobNo>' . $id . '</JobNo>
									<Title>' . $quote_request->title . '</Title>
									<Description>' . $quote_request->title . '</Description>
									<UnitAmount>' . $quote_request->job->job_item->price / $quote_request->job->job_item->quantity . '</UnitAmount>
									<GST>' . $quote_request->job->job_item->gst . '</GST>
									<AccountCode>230/</AccountCode>
									<TotalIncGST>' . $quote_request->job->job_item->total . '</TotalIncGST>
									<Quantity>' . $quote_request->job->job_item->quantity . '</Quantity>
									<PONumber>'. $id .'</PONumber>
								  </LineItem>';
                    if(!empty($quote_request->summary)) {
                        $xml .= '<LineItem>
									<Description>' . $quote_request->summary . '</Description>
								  </LineItem>';
                    }
                    $xml .= '</LineItems>
							  </Invoice>
							</Invoices>';

                    $response = $XeroOAuth->request('POST', $XeroOAuth->url('Invoices', 'core'), array(), $xml);
                    if ($XeroOAuth->response['code'] == 200)
                    {
                        // Set status = 'completed' job
                        $quote_request->status = 9;
                        $quote_request->save();

                        return 'OK';

                    } else {
                        $this->outputError($XeroOAuth);
                        return 'ERROR';
                    }
                }
            }
        }

        return 'ERROR';
    }

    /**
     * Persist the OAuth access token and session handle
     */
    function persistSession($response)
    {
        if (isset($response)) {
            $_SESSION['access_token']       = $response['oauth_token'];
            $_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];
            if(isset($response['oauth_session_handle']))  $_SESSION['session_handle']     = $response['oauth_session_handle'];
        } else {
            return false;
        }

    }


    /**
     * Retrieve the OAuth access token and session handle
     */
    private function retrieveSession()
    {
        if (isset($_SESSION['access_token'])) {
            $response['oauth_token']            =    $_SESSION['access_token'];
            $response['oauth_token_secret']     =    $_SESSION['oauth_token_secret'];
            $response['oauth_session_handle']   =    $_SESSION['session_handle'];
            return $response;
        } else {
            return false;
        }

    }

    private function outputError($XeroOAuth)
    {
        //echo $customerName;
        echo 'Error: ' . $XeroOAuth->response['response'] . PHP_EOL;
        $this->pr($XeroOAuth);
    }

    /**
     * Debug function for printing the content of an object
     *
     * @param mixes $obj
     */
    private function pr($obj)
    {
        if (!$this->is_cli())
            echo '<pre style="word-wrap: break-word">';
        if (is_object($obj))
            print_r($obj);
        elseif (is_array($obj))
            print_r($obj);
        else
            echo $obj;
        if (!$this->is_cli())
            echo '</pre>';
    }

    private function is_cli()
    {
        return (PHP_SAPI == 'cli' && empty($_SERVER['REMOTE_ADDR']));
    }
}
