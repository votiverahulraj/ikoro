<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\Zipcode;
use App\Models\Task;
use App\Models\Country;
use App\Models\Booking;
use App\Models\Equipment;
use App\Models\EquipmentPrice;
use App\Models\Gig;
use App\Models\GigFeature;
use App\Models\Host;
use App\Models\DestinationSearchLog;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Unicodeveloper\Paystack\Facades\Paystack;

class HomeController extends Controller
{
    private function prepareData(Request $request)
    {
        $client = Auth::user()->id ?? "";
        $data = [
            'loggedIn' => "",
            'tasks' => [],
            'countries' => Country::all(),
            'user' => []
        ];

        if ($client != "") {
            $data['loggedIn'] = $client;
            $data['tasks'] = Task::all();
            $data['user'] = Auth::user();
        }

        $data['equipment_price_all'] = Equipment::get();
        $data['country'] = Country::where('name', 'Nigeria')->first();
        $data['state'] = State::where('name', 'Nigeria')->first();
        $data['cities'] = City::all();
        $data['gigs'] = collect([]);
        return $data;
    }

    public function getEquipmentPrices(Request $request)
    {
        $equipment_id = $request->input('equipment_id');
        $equipment_prices = EquipmentPrice::where('equipment_id', $equipment_id)->get();

        return response()->json($equipment_prices);
    }

     public function index(Request $request)
    {
        $data = $this->prepareData($request);

        $data['hosts'] = Host::with('gigs')
            ->where('recommended_sequence', '>', 0)
            ->where('status', 1)
            ->orderBy('recommended_sequence', 'asc')
            ->get();

        $data['host_profile'] = Host::with('gigs')
            ->where('recommended_sequence', '=', 2)
            ->first();

        $data['tasks'] = Task::all();

        // Get top searched destinations for map display
        $data['topDestinations'] = DestinationSearchLog::getTopDestinations(10);

        return view('home', $data);
    }

    public function searchLocations(Request $request)
    {
        $query = $request->input('query');
        $results = collect();

        // Search Zipcodes with full hierarchy
        $zipcodes = Zipcode::with(['city.state.country'])
            ->where('code', 'LIKE', "%{$query}%")
            ->take(5)
            ->get()
            ->map(function ($item) {
                $city = $item->city;
                $state = $city ? $city->state : null;
                $country = $state ? $state->country : null;

                $hierarchy = [];
                if ($country) $hierarchy[] = $country->name;
                if ($state) $hierarchy[] = $state->name;
                if ($city) $hierarchy[] = $city->name;
                $hierarchy[] = $item->code;

                return [
                    'id' => $item->id,
                    'name' => implode(', ', $hierarchy),
                    'type' => 'Zipcode',
                    'hierarchy' => [
                        'country' => $country ? $country->name : null,
                        'state' => $state ? $state->name : null,
                        'city' => $city ? $city->name : null,
                        'zipcode' => $item->code,
                    ],
                    'icon' => 'fa-map-pin'
                ];
            });

        // Search Cities with full hierarchy
        $cities = City::with(['state.country'])
            ->where('name', 'LIKE', "%{$query}%")
            ->take(5)
            ->get()
            ->map(function ($item) {
                $state = $item->state;
                $country = $state ? $state->country : null;

                $hierarchy = [];
                if ($country) $hierarchy[] = $country->name;
                if ($state) $hierarchy[] = $state->name;
                $hierarchy[] = $item->name;

                return [
                    'id' => $item->id,
                    'name' => implode(', ', $hierarchy),
                    'type' => 'City',
                    'hierarchy' => [
                        'country' => $country ? $country->name : null,
                        'state' => $state ? $state->name : null,
                        'city' => $item->name,
                        'zipcode' => null,
                    ],
                    'icon' => 'fa-city'
                ];
            });

        // Search States with hierarchy
        $states = State::with('country')
            ->where('name', 'LIKE', "%{$query}%")
            ->take(5)
            ->get()
            ->map(function ($item) {
                $country = $item->country;

                $hierarchy = [];
                if ($country) $hierarchy[] = $country->name;
                $hierarchy[] = $item->name;

                return [
                    'id' => $item->id,
                    'name' => implode(', ', $hierarchy),
                    'type' => 'State',
                    'hierarchy' => [
                        'country' => $country ? $country->name : null,
                        'state' => $item->name,
                        'city' => null,
                        'zipcode' => null,
                    ],
                    'icon' => 'fa-map-marker-alt'
                ];
            });

        // Search Countries
        $countries = Country::where('name', 'LIKE', "%{$query}%")
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => 'Country',
                    'hierarchy' => [
                        'country' => $item->name,
                        'state' => null,
                        'city' => null,
                        'zipcode' => null,
                    ],
                    'icon' => 'fa-globe'
                ];
            });

        // Merge all results, prioritize more specific locations
        $results = $zipcodes->merge($cities)->merge($states)->merge($countries)->take(10);

        return response()->json($results);
    }

    public function filterGigs(Request $request)
    {
        $where = [];

        if ($request->filled('city_id')) {
            $where['city_id'] = $request->input('city_id');
        }

        if ($request->filled('task_id')) {
            $where['task_id'] = $request->input('task_id');
        }
        if ($request->filled('equipment_id')) {
            $where['equipment_id'] = $request->input('equipment_id');
        }

        $gigs = Gig::where($where);
        if ($request->filled('gender')) {
            $gender = $request->input('gender');
            $gigs->whereHas('host', function ($query) use ($gender) {
                $query->where('gender', $gender);
            });
        }

        $gigs = $gigs->get();
        if ($gigs->isEmpty()) {
            return response()->json([
                'html' => '<span class="text-white"><b>No host found for the selected fields!</b></span>'
            ]);
        }
        $html = view('partials.gigs-list', compact('gigs'))->render();
        return response()->json(['html' => $html]);
    }

    public function filterHost(Request $request)
    {
        $data = $this->prepareData($request);
        $data['tasks'] = Task::all();
        $where = [];

        // if ($request->filled('city_id')) {
        //     $where['city_id'] = $request->input('city_id');
        // }

        // if ($request->filled('equipment_id')) {
        //     $where['equipment_id'] = $request->input('equipment_id');
        // }

        if ($request->filled('location_id') && $request->filled('location_type')) {
            $locationId = $request->input('location_id');
            $locationType = $request->input('location_type');
            $locationName = $request->input('location_name', '');

            // Track search frequency
            $relatedIds = [];
            if ($locationType === 'City') {
                $where['city_id'] = $locationId;
                $city = City::with(['state.country'])->find($locationId);
                if ($city) {
                    $relatedIds = [
                        'city_id' => $city->id,
                        'state_id' => $city->state_id,
                        'country_id' => $city->state->country_id ?? null,
                    ];
                }
            } elseif ($locationType === 'State') {
                $where['state_id'] = $locationId;
                $state = State::with('country')->find($locationId);
                if ($state) {
                    $relatedIds = [
                        'state_id' => $state->id,
                        'country_id' => $state->country_id,
                    ];
                }
            } elseif ($locationType === 'Country') {
                $where['country_id'] = $locationId;
                $relatedIds = ['country_id' => $locationId];
            } elseif ($locationType === 'Zipcode') {
                $where['zip_id'] = $locationId;
                $zipcode = Zipcode::with(['city.state.country'])->find($locationId);
                if ($zipcode) {
                    $relatedIds = [
                        'zip_id' => $zipcode->id,
                        'city_id' => $zipcode->city_id,
                        'state_id' => $zipcode->city->state_id ?? null,
                        'country_id' => $zipcode->city->state->country_id ?? null,
                    ];
                }
            }

            // Log the search
            DestinationSearchLog::logSearch($locationType, $locationId, $locationName, $relatedIds);
        }

        if ($request->filled('task_id')) {
            $where['task_id'] = $request->input('task_id');
        }     

        $gigs = Gig::where($where);

        // if ($request->filled('gender')) {
        //     $gender = $request->input('gender');
        //     $gigs->whereHas('host', function ($query) use ($gender) {
        //         $query->where('gender', $gender);
        //     });
        // }
        $gigs->whereHas('host', function ($query) use ($request) {
            $query->where('status', 1);

            if ($request->filled('gender')) {
                $query->where('gender', $request->input('gender'));
            }
        });
        
        if($request->filled('is_open')){           
            $is_open = strtolower(date('D')) . '_is_open' ? 1 : '';
            $gigs->whereHas('host', function ($query) use ($is_open) {
                $today_is_open = strtolower(date('D')) . '_is_open';
                $query->where($today_is_open, $is_open);
            });
        }

        $data['gigs'] = $gigs->paginate(6)->appends($request->all());
        // dd($data);
        return view('pages.gig-filter-host', $data);
    }

    public function getSelectedHost(Request $request)
    {
        $host_id = $request->input('host_id');
        $host_profile = Host::with('gigs')->where('id', $host_id)->first();

        if (!$host_profile) {
            return response()->json(['error' => 'Host not found'], 404);
        }
        $html = view('partials.selected-host', compact('host_profile'))->render();
        return response()->json(['html' => $html]);
    }

    public function hostProfileById($host_id)
    {
        $client = Auth::user()->id ?? "";
        $data = [
            'loggedIn' => "",
        ];
        if ($client != "") {
            $data['loggedIn'] = $client;
        }

        $data['host_profile'] = Host::with(['gigs' => function ($query) {
            $query->with(['features' => function ($q) {
                $q->latest()->take(3);
            }]);
        }])->findOrFail($host_id);  
        // dd($data['host_profile'] );
        return view('pages.host-profile', $data);
    }
    
    public function demoBookingDetailByGigId($gig_id)
    {
        $clientId = (Auth::check() && Auth::user()->role === 'user') ? Auth::id() : ''; // cleaner way
        $data = [
            'loggedIn' => $clientId ?? '',
        ];

        // Get the gig with all the required relationships
        $gig = Gig::with(['host','task', 'country', 'state', 'city', 'zip', 'equipmentPrice'])->findOrFail($gig_id);
        $data['gig'] = $gig;

        // Get the selected gig features
        $data['selectedGig'] = GigFeature::where('gig_id', $gig_id)
            ->pluck('gig_id')
            ->toArray();

        // Ensure equipmentPrice exists before accessing equipment_id
        $equipmentId = $gig->equipmentPrice->equipment_id ?? null;

        // Get selected equipment prices if available
        $data['selectedEquipmentPrices'] = $equipmentId
            ? EquipmentPrice::where('equipment_id', $equipmentId)->get()
            : collect(); // return empty collection if null
        // dd($data['loggedIn']);
        return view('pages.demo-booking-details', $data);
    }

    public function bookingDetailByGigId(Request $request, $gig_id)
    {       
        $clientId = (Auth::check() && Auth::user()->role === 'user') ? Auth::id() : ''; // cleaner way
        $data = [
            'loggedIn' => $clientId ?? '',
        ];

        // Get the gig with all the required relationships
        $gig = Gig::with(['host','task', 'country', 'state', 'city', 'zip', 'equipmentPrice'])->findOrFail($gig_id);
        $data['gig'] = $gig;

        // Get the selected gig features
        $data['selectedFeatureIds'] = (array) $request->query('features');
        // $data['selectedGig'] = GigFeature::where('gig_id', $gig_id)
        //     ->pluck('gig_id')
        //     ->toArray();        
        
        // $equipmentId = $gig->equipmentPrice->equipment_id ?? null;  
        // $data['selectedEquipmentPrices'] = $equipmentId
        //     ? EquipmentPrice::where('equipment_id', $equipmentId)->get()
        //     : collect(); 
        // dd($data['selectedFeatureIds']);
        return view('pages.booking-details', $data);
    }




    public function gigSearchedOnTask(Request $request)
    {
        $data = [];
        if ($request->input('data') != '') {
            $id = $request->input('data');
            $data = Gig::where('task_id', $id)->inRandomOrder()->get();
        }
        if ($request->ajax()) {
            $html = view('pages.gig-search-task', compact('data'))->render();
            return response()->json([
                'status' => 'success',
                'data' => $html,
            ]);
        }
    }


    public function homeDashboard(Request $request)
    {
        $data = $this->prepareData($request);
        return view('pages.dashboard', $data);
    }

    public function storeBooking(Request $request)
    {
        try {
            $data = $request->all();
            $action = $request->input('action');
            $id = $data['action'] ?? "";
            unset($data['action']);
            unset($data['id']);
            unset($data['_token']);
            $data['client_id'] = Auth::user()->id;

            if ($id == "") {
                Booking::create($data);
            }
            if ($action == 'pay') {
                return $this->redirectToGateway($data["total_cost"]);
                exit;
            }

            Session::flash('message', 'Booking saved successfuly, please wait for admin to assign to host');
            Session::flash('alert-class', 'alert-success');

            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('message', 'Error while saving booking.' . $e->getMessage());
            Session::flash('alert-class', 'alert-warning');

            return redirect()->back();
        }
    }

    public function redirectToGateway($total_cost)
    {
        $data = array(
            "amount" => $total_cost * 100,
            "id" => Auth::user()->id,
            "email" => Auth::user()->email,
            "currency" => "ZAR",
            "callback_url" => route('callback'),
        );

        try {
            return Paystack::getAuthorizationUrl($data)->redirectNow();
        } catch (\Exception $e) {
            Session::flash('message', 'The paystack token has expired. Please refresh the page and try again.' . $e->getMessage());
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back();
        }
    }

    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)->get();
        return response()->json($states);
    }

    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)->get();
        return response()->json($cities);
    }

    public function getZips($cityId)
    {
        $zips = Zipcode::where('city_id', $cityId)->get();
        return response()->json($zips);
    }
}
