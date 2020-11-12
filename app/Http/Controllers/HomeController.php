<?php

namespace App\Http\Controllers;

use App\Category;
use App\Notifications\RequestSignUpDetailNotifcation;
use App\Order;
use App\Product;
use App\Role;
use App\Store;
use App\StoreVisitor;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['getHomeData', 'subscribe']);
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


    public function getDashboardData()
    {
        $data = [];

        if (auth()->user()->hasRole(Role::SHOP_OWNER)) {
            return $this->getShopOwnerData();
        }

        return $this->getOthersData();
    }

    public function getShopOwnerData()
    {
        $range = \request()->get('range');
        $ranges = $this->getRanges();
        $format = 'Y-m-d H:i:s';
        $store = $this->getAuthUserStoreFromHeader();
        $storeLink = $store ? $store->url : '';
        $slug = $store->slug;

        if (!isset($ranges[$range])) {
            $range = 'today';
        }

        $beginningOfTime = $ranges[$range]['from'];
        $interval = $ranges[$range]['interval'];
        $unit = $ranges[$range]['unit'];
        $count = $ranges[$range]['count'];
        $dayFormat = $ranges[$range]['format'];
        $dayFormat1 = $ranges[$range]['format1'];

        $statistics = [
            'orders' => Order::query()->with(['orderItems' => function ($query) {
                $query->with('product');
            }])->owned()->latest()->take(10)->get(),
            'notifications' => auth()->user()->notifications()->unread()->latest()->take(15)->get(),
            'stats' => [
                'categories' => Category::query()->owned()->count(),
                'products' => Product::query()->owned()->count(),
                'orders' => Order::query()->owned()->count(),
                'today_visits' => StoreVisitor::query()
                    ->where('store', $slug)
                    ->where('created_at', '>=',
                        Carbon::now(1)->startOfDay()->format($format))->count(),
                'orders_today' => Order::query()->owned()
                    ->where('store', $slug)
                    ->where('created_at', '>=', date('Y-m-d ') . ' 00:00:00')->count(),
                'confirmed_orders' => Order::query()->owned()
                    ->where('store', $slug)->whereIn('status', ['delivered', 'delivery_confirmed'])
                    ->where('created_at', '>=', date('Y-m-d ') . ' 00:00:00')->count(),
                'months' => [
                    [], []
                ],
                'labels' => []
            ],
            'store_link' => $storeLink,
        ];

        for ($i = 0; $i < $count; $i++) {
            $startDateFormated = $beginningOfTime->format($dayFormat1);
            $fromDate = $beginningOfTime->format($format);
            if ($unit === 'day') {
                $end = $beginningOfTime->addDays($interval);
            } else if ($unit === 'month') {
                $end = $beginningOfTime->addMonths($interval);
            } else {
                $end = $beginningOfTime->addHours($interval);
            }

            $endDate = $end->format($format);

            $statistics['stats']['labels'][] = $startDateFormated . $end->format($dayFormat);

            $statistics['stats']['months'][0][] = [
                'meta' => 'Total Visitors', 'value' => StoreVisitor::query()
                    ->where('store', $slug)
                    ->whereBetween('created_at', [$fromDate, $endDate])->count()
            ];
            $statistics['stats']['months'][1][] = [
                'meta' => 'Total Orders',
                'value' => Order::query()
                    ->where('store', 'slug')
                    ->whereBetween('created_at', [$fromDate, $endDate])->count()
            ];

        }

        return $this->withJson($statistics);
    }

    private function getRanges()
    {
        return [
            'today' => [
                'from' => Carbon::now(1)->startOfDay(),
                'interval' => '2',
                'count' => 12,
                'format1' => "H:i - ",
                'format' => "H:i",
                'unit' => 'hour'
            ],
            'yesterday' => [
                'from' => Carbon::now(1)->subDay()->startOfDay(),
                'interval' => '2',
                'count' => 12,
                'format1' => "H:i - ",
                'format' => "H:i",
                'unit' => 'hour'
            ],
            'week' => [
                'from' => Carbon::now(1)->subWeek()->startOfDay(),
                'interval' => '1',
                'unit' => 'day',
                'count' => 7,
                'format1' => "D - ",
                'format' => "D",
            ],
            'month' => [
                'from' => Carbon::now(1)->subMonth()->startOfDay(),
                'interval' => '3',
                'count' => 10,
                'format1' => "d - ",
                'format' => "d M",
                'unit' => 'day'
            ],

            'year' => [
                'from' => Carbon::now(1)->subYear()->startOfMonth(),
                'interval' => '1',
                'count' => 13,
                'format1' => "M - ",
                'format' => "M y",
                'unit' => 'month'
            ],
        ];
    }

    public function getOthersData()
    {
        $range = \request()->get('range');
        $format = 'Y-m-d H:i:s';

        $ranges = $this->getRanges();

        if (!isset($ranges[$range])) {
            $range = 'today';
        }

        $beginningOfTime = $ranges[$range]['from'];
        $interval = $ranges[$range]['interval'];
        $unit = $ranges[$range]['unit'];
        $count = $ranges[$range]['count'];
        $dayFormat = $ranges[$range]['format'];
        $dayFormat1 = $ranges[$range]['format1'];
        $statistics = [
            'orders' => Order::query()->count(),
            'stores' => Store::query()->count(),
            'products' => Product::query()->count(),
            'categories' => Category::query()->count(),
            'visits' => [
                'week' => StoreVisitor::query()
                    ->where('created_at', '>=',
                        Carbon::now(1)->subWeek()->startOfDay()->format($format))->count(),
                'today' => StoreVisitor::query()
                    ->where('created_at', '>=',
                        Carbon::now(1)->startOfDay()->format($format))->count(),
                'yesterday' => StoreVisitor::query()
                    ->whereBetween('created_at', [
                        Carbon::now(1)->subDay()->startOfDay()->format($format),
                        Carbon::now(1)->subDay()->endOfDay()->format($format),
                    ])->count(),
                'year' => StoreVisitor::query()
                    ->where('created_at', '>=',
                        Carbon::now(1)->subYear()->startOfDay()->format($format))->count()
            ],
            'users' => [
                'week' => User::query()->whereHas('roles', function ($query) {
                    $query->where('name', Role::SHOP_OWNER);
                })->where('created_at', '>=',
                    Carbon::now(1)->subWeek()->startOfDay()->format($format))->count(),
                'today' => User::query()->whereHas('roles', function ($query) {
                    $query->where('name', Role::SHOP_OWNER);
                })->where('created_at', '>=',
                    Carbon::now(1)->startOfDay()->format($format))->count(),
                'yesterday' => User::query()->whereHas('roles', function ($query) {
                    $query->where('name', Role::SHOP_OWNER);
                })->whereBetween('created_at', [
                    Carbon::now(1)->subDay()->startOfDay()->format($format),
                    Carbon::now(1)->subDay()->endOfDay()->format($format),
                ])->count(),
                'year' => User::query()->whereHas('roles', function ($query) {
                    $query->where('name', Role::SHOP_OWNER);
                })->where('created_at', '>=',
                    Carbon::now(1)->subYear()->startOfDay()->format($format))->count()
            ],
            'months' => [
                [], [], []
            ],
            'labels' => []
        ];

        for ($i = 0; $i < $count; $i++) {
            $startDateFormated = $beginningOfTime->format($dayFormat1);
//            $beginningOfTime = Carbon::createFromFormat($format, $beginningOfYear)->addMonths($month);
            $fromDate = $beginningOfTime->format($format);
            if ($unit === 'day') {
                $end = $beginningOfTime->addDays($interval);
            } else if ($unit === 'month') {
                $end = $beginningOfTime->addMonths($interval);
            } else {
                $end = $beginningOfTime->addHours($interval);
            }

            $endDate = $end->format($format);

            $statistics['labels'][] = $startDateFormated . $end->format($dayFormat);

            $statistics['months'][0][] = [
                'meta' => 'Total Visitors', 'value' => StoreVisitor::query()
                    ->whereBetween('created_at', [$fromDate, $endDate])->count()
            ];
            $statistics['months'][1][] = [
                'meta' => 'New Users', 'value' => User::query()
                    ->whereBetween('created_at', [$fromDate, $endDate])->count()
            ];
            $statistics['months'][2][] = [
                'meta' => 'Store Visits', 'value' => StoreVisitor::query()
                    ->whereNotIn('store', ['', 'control-panel', 'store', 'setup'])
                    ->whereBetween('created_at', [$fromDate, $endDate])->count()
            ];

        }

        return $this->withJson([
            'notifications' => auth()->user()->notifications()->unread()->latest()->take(15)->get(),
            'data' => $statistics
        ]);
    }


    public function getHomeData($subdomain)
    {
        $store = $subdomain;
        $featuredList = Product::query()
            ->available()
            ->where('store', $store)->with('category')->where('is_featured', true)->get();
        $groups = Category::query()->whereHas('products', function ($query) use ($store) {
            $query->where('store', $store);
        })->with(['products' => function ($query) {
            $query->available()->orderBy('is_featured')->with('category');
        }])->get();

        return $this->withJson([
            'groups' => $groups,
            'featured' => $featuredList,
            'categories' => Category::query()->where('store', $store)->get()
        ]);
    }

    public function subscribe(Request $request, $subdomain)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);
        try {
            (new User($request->only('email')))->notify(new RequestSignUpDetailNotifcation());
            return $this->withMessage('Successful');
        } catch (\Exception $ex) {
        };

        return $this->withMessage('Unable to send a mail at the moment, try again', 400);
    }

}
